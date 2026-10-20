<?php
declare(strict_types=1);

/**
 * AUTENTICAZIONE DELL'AREA ADMIN
 * ==============================
 * Contiene SOLO funzioni: includerlo non fa nulla da solo (non avvia sessioni,
 * non stampa niente). Le pagine e gli endpoint dell'admin lo includono e poi
 * chiamano:
 *
 *   richiediAdmin();        // in cima a ogni pagina admin
 *   richiediAdmin('api');   // in cima a ogni endpoint JSON (leggi/salva/...)
 *
 * Pezzi:
 *   1. sessione sicura
 *   2. token CSRF
 *   3. limite ai tentativi di login (rate limiting)
 *   4. verifica della password
 *   5. richiediAdmin()
 */

require_once __DIR__ . '/../admin/config.php';

/**
 * Errore di configurazione del server (cartella mancante, file segreto
 * assente...). Il dettaglio va nel log di PHP, NON nella pagina: chi guarda
 * da fuori non deve ricevere informazioni sui percorsi del server.
 */
function erroreConfigurazione(string $dettaglio): never
{
    error_log('auth: ' . $dettaglio);
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Errore di configurazione del server. Contatta l'amministratore.";
    exit;
}

// ===========================================================================
// 1. SESSIONE
// ===========================================================================

/**
 * Avvia la sessione dell'admin (se non è già avviata) con impostazioni sicure:
 *  - nome dedicato: non si mischia con la sessione del sito pubblico;
 *  - cookie HttpOnly (non leggibile da JavaScript);
 *  - SameSite=Lax (il browser non lo manda nelle richieste incrociate);
 *  - Secure quando il sito è in HTTPS (il cookie non viaggia in chiaro);
 *  - cookie valido solo sotto /admin/;
 *  - use_strict_mode: PHP rifiuta ID di sessione inventati dal client.
 *
 * Se la sessione era scaduta (troppo tempo senza richieste, o più di 8 ore dal
 * login) la svuota e ne crea una nuova: l'utente risulta non loggato.
 */
function avviaSessioneAdmin(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (!is_dir(CARTELLA_SESSIONI) || !is_writable(CARTELLA_SESSIONI)) {
        erroreConfigurazione('cartella sessioni mancante o non scrivibile: ' . CARTELLA_SESSIONI);
    }

    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    session_name('CITYTEL_ADMIN');
    session_save_path(CARTELLA_SESSIONI);

    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    // Pulizia delle vecchie sessioni: in questa cartella non la fa il sistema,
    // quindi la facciamo noi (circa 1 richiesta su 100 ripulisce quelle scadute).
    ini_set('session.gc_maxlifetime', (string) SESSIONE_INATTIVITA_SECONDI);
    ini_set('session.gc_probability', '1');
    ini_set('session.gc_divisor', '100');

    session_set_cookie_params([
        'lifetime' => 0,            // cookie di sessione: sparisce chiudendo il browser
        'path'     => '/admin/',
        'secure'   => $https,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();

    // Controllo scadenza (solo se c'era un admin loggato).
    if (isset($_SESSION['admin'])) {
        $adesso   = time();
        $inattivo = $adesso - (int) ($_SESSION['ultimo_accesso'] ?? 0);
        $durata   = $adesso - (int) ($_SESSION['login_time'] ?? 0);

        if ($inattivo > SESSIONE_INATTIVITA_SECONDI || $durata > SESSIONE_DURATA_MASSIMA_SECONDI) {
            $_SESSION = [];
            session_regenerate_id(true);
        } else {
            $_SESSION['ultimo_accesso'] = $adesso;
        }
    }
}

/**
 * True se c'è un admin loggato con sessione valida.
 */
function adminLoggato(): bool
{
    // Nessun cookie di sessione (e nessuna sessione già aperta in questa
    // richiesta): non può esserci un admin loggato, quindi NON si avvia la
    // sessione. Evita che richieste anonime creino un file di sessione ciascuna.
    if (session_status() !== PHP_SESSION_ACTIVE && !isset($_COOKIE['CITYTEL_ADMIN'])) {
        return false;
    }

    avviaSessioneAdmin();
    return ($_SESSION['admin'] ?? false) === true;
}

/**
 * Da chiamare DOPO una password corretta. Cambia l'ID di sessione (così un
 * eventuale ID noto prima del login non vale più) e il token CSRF.
 */
function impostaAdminLoggato(): void
{
    avviaSessioneAdmin();
    session_regenerate_id(true);

    unset($_SESSION['csrf']);
    $_SESSION['admin']          = true;
    $_SESSION['login_time']     = time();
    $_SESSION['ultimo_accesso'] = time();
}

/**
 * Chiude la sessione: svuota i dati, cancella il cookie, distrugge il file.
 */
function logoutAdmin(): void
{
    avviaSessioneAdmin();
    $_SESSION = [];

    $p = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires'  => time() - 42000,
        'path'     => $p['path'],
        'secure'   => $p['secure'],
        'httponly' => $p['httponly'],
        'samesite' => 'Lax',
    ]);

    session_destroy();
}

// ===========================================================================
// 2. TOKEN CSRF
// ===========================================================================
// CSRF = un sito malevolo che, mentre sei loggato, fa partire dal tuo browser
// una richiesta verso /admin/ (es. un salvataggio). Difesa: ogni form e ogni
// salvataggio devono includere un token segreto, legato alla tua sessione, che
// il sito malevolo non può conoscere.

/**
 * Token CSRF della sessione corrente (lo crea alla prima richiesta).
 */
function tokenCsrf(): string
{
    avviaSessioneAdmin();

    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

/**
 * True se il token ricevuto coincide con quello della sessione.
 * hash_equals() confronta in tempo costante (non rivela quanti caratteri
 * coincidono).
 */
function csrfValido(?string $token): bool
{
    avviaSessioneAdmin();

    $atteso = $_SESSION['csrf'] ?? '';

    return is_string($token) && $atteso !== '' && hash_equals($atteso, $token);
}

// ===========================================================================
// 3. LIMITE AI TENTATIVI DI LOGIN
// ===========================================================================
// Per ogni IP c'è un piccolo file in private/rate-limit/ con il numero di
// tentativi falliti e l'istante del primo. Il nome del file è l'hash dell'IP
// (niente caratteri strani nel nome, e l'IP non si legge in chiaro sul disco).
//
// NOTA: si usa REMOTE_ADDR, l'unico dato che il client non può falsificare.
// Se il sito andrà dietro un proxy/CDN, REMOTE_ADDR sarà quello del proxy e
// questo punto andrà rivisto.

/**
 * IP del client che sta facendo la richiesta.
 */
function indirizzoClient(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? 'sconosciuto';
}

/**
 * Controlla che la cartella dei contatori esista e sia scrivibile.
 */
function verificaCartellaTentativi(): void
{
    if (!is_dir(CARTELLA_TENTATIVI) || !is_writable(CARTELLA_TENTATIVI)) {
        erroreConfigurazione('cartella rate-limit mancante o non scrivibile: ' . CARTELLA_TENTATIVI);
    }
}

function percorsoTentativi(string $ip): string
{
    return CARTELLA_TENTATIVI . '/' . hash('sha256', $ip) . '.json';
}

/**
 * Legge lo stato dei tentativi di un IP: ['n' => tentativi, 'inizio' => istante
 * del primo]. Se il file manca, è illeggibile o la finestra è scaduta, riparte
 * da zero.
 *
 * @return array{n: int, inizio: int}
 */
function leggiTentativi(string $ip): array
{
    $vuoto    = ['n' => 0, 'inizio' => 0];
    $percorso = percorsoTentativi($ip);

    if (!is_readable($percorso)) {
        return $vuoto;
    }

    $dati = json_decode((string) file_get_contents($percorso), true);

    if (!is_array($dati) || !isset($dati['n'], $dati['inizio'])) {
        return $vuoto;
    }

    if (time() - (int) $dati['inizio'] > FINESTRA_TENTATIVI_SECONDI) {
        return $vuoto;
    }

    return ['n' => (int) $dati['n'], 'inizio' => (int) $dati['inizio']];
}

/**
 * True se questo IP ha già esaurito i tentativi ed è bloccato.
 */
function tentativiBloccati(string $ip): bool
{
    verificaCartellaTentativi();
    return leggiTentativi($ip)['n'] >= MAX_TENTATIVI_LOGIN;
}

/**
 * Registra un tentativo fallito. Il file viene bloccato (flock) mentre si
 * aggiorna, così due richieste insieme non si pestano i piedi.
 */
function registraTentativoFallito(string $ip): void
{
    verificaCartellaTentativi();

    $percorso = percorsoTentativi($ip);
    $file     = fopen($percorso, 'c+');   // lo crea se non esiste

    if ($file === false) {
        erroreConfigurazione('impossibile scrivere il file dei tentativi: ' . $percorso);
    }

    flock($file, LOCK_EX);

    $adesso = time();
    $dati   = json_decode((string) stream_get_contents($file), true);

    // Primo tentativo, file rovinato o finestra scaduta: si riparte da zero.
    if (
        !is_array($dati)
        || !isset($dati['n'], $dati['inizio'])
        || $adesso - (int) $dati['inizio'] > FINESTRA_TENTATIVI_SECONDI
    ) {
        $dati = ['n' => 0, 'inizio' => $adesso];
    }

    $dati['n'] = (int) $dati['n'] + 1;

    ftruncate($file, 0);
    rewind($file);
    fwrite($file, json_encode($dati));
    fflush($file);
    flock($file, LOCK_UN);
    fclose($file);

    pulisciTentativiVecchi();
}

/**
 * Login riuscito: cancella il contatore di questo IP.
 */
function azzeraTentativi(string $ip): void
{
    $percorso = percorsoTentativi($ip);

    if (is_file($percorso)) {
        unlink($percorso);
    }
}

/**
 * Cancella i contatori più vecchi della finestra (non servono più): evita che
 * la cartella si riempia di file di IP che non tornano.
 */
function pulisciTentativiVecchi(): void
{
    $limite = time() - FINESTRA_TENTATIVI_SECONDI;

    foreach (glob(CARTELLA_TENTATIVI . '/*.json') ?: [] as $percorso) {
        if (filemtime($percorso) < $limite) {
            unlink($percorso);
        }
    }
}

// ===========================================================================
// 4. VERIFICA DELLA PASSWORD
// ===========================================================================

/**
 * Confronta la password digitata con l'hash salvato in private/admin-secret.php.
 * Rifiuta password vuote o più lunghe di 72 byte (limite di bcrypt: oltre,
 * PHP ignorerebbe il resto, e due password diverse risulterebbero uguali).
 */
function verificaPasswordAdmin(string $password): bool
{
    if (!is_readable(FILE_SEGRETO_ADMIN)) {
        erroreConfigurazione('file segreto mancante o illeggibile: ' . FILE_SEGRETO_ADMIN);
    }

    // Il file contiene "return ['hash' => '...'];": require restituisce l'array.
    $segreto = require FILE_SEGRETO_ADMIN;

    if (!is_array($segreto) || !isset($segreto['hash']) || !is_string($segreto['hash'])) {
        erroreConfigurazione('formato non valido del file segreto: ' . FILE_SEGRETO_ADMIN);
    }

    if ($password === '' || strlen($password) > 72) {
        return false;
    }

    return password_verify($password, $segreto['hash']);
}

// ===========================================================================
// 5. PROTEZIONE DI PAGINE ED ENDPOINT
// ===========================================================================

/**
 * Da chiamare in CIMA a ogni pagina o endpoint dell'admin.
 * Se non c'è un admin loggato, interrompe tutto:
 *   - 'pagina' (default): rimanda alla pagina di login;
 *   - 'api': risponde 401 con un JSON (l'editor lo mostra come errore).
 */
function richiediAdmin(string $modo = 'pagina'): void
{
    if (adminLoggato()) {
        return;
    }

    if ($modo === 'api') {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success'   => false,
            'messaggio' => 'Sessione scaduta o accesso non effettuato. Accedi di nuovo.',
        ]);
        exit;
    }

    header('Location: /admin/login.php');
    exit;
}