<?php
declare(strict_types=1);

/**
 * GENERATORE DELL'HASH DELLA PASSWORD ADMIN
 * =========================================
 * Script da RIGA DI COMANDO (non dal browser). Chiede la password due volte,
 * poi stampa il contenuto da incollare nel file private/admin-secret.php.
 *
 * Uso:   php tools/genera-hash.php
 *
 * Perché un hash: nel file segreto non resta mai la password in chiaro, ma
 * solo un "riassunto" (bcrypt) da cui non si torna alla password. Il login
 * confronta quello che scrivi con l'hash usando password_verify().
 *
 * Limiti sulla password:
 *   - minimo 12 caratteri;
 *   - massimo 72 BYTE: bcrypt ignora in silenzio tutto ciò che sta oltre,
 *     quindi meglio rifiutare le password più lunghe che troncarle di nascosto.
 */

// Se qualcuno lo aprisse dal browser, si ferma subito (difesa in più:
// la cartella tools/ ha anche un .htaccess che nega l'accesso).
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit;
}

/**
 * Legge una riga da tastiera SENZA mostrarla a schermo.
 * Usa "stty -echo" (Linux/macOS/WSL) per nascondere i caratteri digitati.
 */
function leggiPasswordNascosta(string $domanda): string
{
    fwrite(STDOUT, $domanda);
    shell_exec('stty -echo');
    $valore = rtrim((string) fgets(STDIN), "\r\n");
    shell_exec('stty echo');
    fwrite(STDOUT, PHP_EOL);

    return $valore;
}

$password = leggiPasswordNascosta('Nuova password admin: ');
$conferma = leggiPasswordNascosta('Ripeti la password:    ');

if ($password !== $conferma) {
    fwrite(STDERR, "Errore: le due password non coincidono.\n");
    exit(1);
}

if (strlen($password) < 12) {
    fwrite(STDERR, "Errore: servono almeno 12 caratteri.\n");
    exit(1);
}

// strlen() conta i byte (non i caratteri): è proprio il limite di bcrypt.
if (strlen($password) > 72) {
    fwrite(STDERR, "Errore: massimo 72 byte (limite di bcrypt).\n");
    exit(1);
}

// PASSWORD_DEFAULT = l'algoritmo consigliato da PHP (oggi bcrypt).
$hash = password_hash($password, PASSWORD_DEFAULT);

// var_export() scrive la stringa tra apici in modo sicuro: nell'hash ci sono
// dei "$" che PHP, tra apici singoli, non interpreta.
echo PHP_EOL;
echo "Copia TUTTO quello che segue nel file private/admin-secret.php:" . PHP_EOL;
echo "----------------------------------------------------------------" . PHP_EOL;
echo "<?php" . PHP_EOL;
echo "return ['hash' => " . var_export($hash, true) . "];" . PHP_EOL;
echo "----------------------------------------------------------------" . PHP_EOL;