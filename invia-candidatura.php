<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const CITYTEL_UPLOAD_DIR = __DIR__ . '/uploads/candidature/';
const CITYTEL_MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB

function citytel_redirect_with_status(string $status, string $message = ''): void
{
    $params = ['cv_status' => $status];
    if ($message !== '') {
        $params['cv_msg'] = $message;
    }
    header('Location: /index.php?' . http_build_query($params) . '#lavora-con-noi');
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    citytel_redirect_with_status('error', 'Richiesta non valida.');
}

// Honeypot: se il campo nascosto e' valorizzato, e' quasi certamente un bot.
if (!empty($_POST['website'])) {
    citytel_redirect_with_status('error', 'Richiesta non valida.');
}

// Verifica CSRF: token monouso legato alla sessione corrente.
if (
    empty($_POST['csrf_token'])
    || empty($_SESSION['csrf_token'])
    || !hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token'])
) {
    citytel_redirect_with_status('error', 'Sessione scaduta, ricarica la pagina e riprova.');
}
unset($_SESSION['csrf_token']);

$nome = trim((string) ($_POST['nome'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$privacyAccepted = isset($_POST['privacy']);

if ($nome === '' || mb_strlen($nome) > 150) {
    citytel_redirect_with_status('error', 'Il nome inserito non è valido.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    citytel_redirect_with_status('error', 'L\'indirizzo email inserito non è valido.');
}

if (!$privacyAccepted) {
    citytel_redirect_with_status('error', 'Devi accettare l\'informativa privacy per procedere.');
}

if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
    citytel_redirect_with_status('error', 'Caricamento del curriculum non riuscito.');
}

if ($_FILES['cv']['size'] > CITYTEL_MAX_FILE_SIZE) {
    citytel_redirect_with_status('error', 'Il file supera i 5MB consentiti.');
}

// Validazione del tipo reale del file (non ci si fida del MIME dichiarato dal browser).
$finfo = new finfo(FILEINFO_MIME_TYPE);
$detectedMime = $finfo->file($_FILES['cv']['tmp_name']);

if ($detectedMime !== 'application/pdf') {
    citytel_redirect_with_status('error', 'Il file caricato deve essere un PDF valido.');
}

if (!is_dir(CITYTEL_UPLOAD_DIR) && !mkdir(CITYTEL_UPLOAD_DIR, 0755, true) && !is_dir(CITYTEL_UPLOAD_DIR)) {
    citytel_redirect_with_status('error', 'Errore interno, riprova più tardi.');
}

/**
 * Costruisce un nome file leggibile ma sicuro:
 * - slug del nome (solo lettere/numeri/trattini, niente path traversal)
 * - data di invio, utile per ordinare/cercare a colpo d'occhio
 * - suffisso hex casuale corto per evitare collisioni tra candidature omonime
 */
function citytel_slugify_filename(string $input): string
{
    // Traslittera gli accenti (é -> e, à -> a, ecc.) prima di rimuovere i caratteri non ammessi.
    $transliterated = @iconv('UTF-8', 'ASCII//TRANSLIT', $input);
    $ascii = $transliterated !== false ? $transliterated : $input;

    $slug = strtolower(trim($ascii));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $slug = trim((string) $slug, '-');

    // Fallback se il nome non produce alcun carattere valido (es. solo emoji/simboli).
    return $slug !== '' ? $slug : 'candidatura';
}

$nomeSlug = citytel_slugify_filename($nome);
$nomeSlug = mb_substr($nomeSlug, 0, 60); // limite di lunghezza per evitare filename assurdi

$safeFileName = date('Ymd') . '_' . $nomeSlug . '_' . bin2hex(random_bytes(4)) . '.pdf';
$destinationPath = CITYTEL_UPLOAD_DIR . $safeFileName;

if (!move_uploaded_file($_FILES['cv']['tmp_name'], $destinationPath)) {
    citytel_redirect_with_status('error', 'Errore durante il salvataggio del file.');
}

// NOTA: l'invio della notifica via email al reparto HR (mail()/SMTP) va configurato
// con le credenziali specifiche dell'hosting Aruba/PHP scelto in produzione.
// Il file e' comunque salvato in modo sicuro e non pubblicamente accessibile (vedi .htaccess).

citytel_redirect_with_status('ok');