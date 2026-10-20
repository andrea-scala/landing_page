<?php
declare(strict_types=1);

/**
 * LOGOUT DELL'ADMIN
 * =================
 * Solo POST con token CSRF (un link GET potrebbe essere attivato da un sito
 * esterno, per esempio con un'immagine, e disconnetterti a tua insaputa).
 *
 * - sessione già scaduta o assente  -> si va direttamente al login;
 * - token CSRF non valido           -> 403, la sessione NON viene toccata;
 * - tutto regolare                  -> sessione distrutta e redirect al login.
 */

require_once __DIR__ . '/../includes/auth.php';

header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit;
}

if (!adminLoggato()) {
    header('Location: /admin/login.php');
    exit;
}

$tokenInviato = $_POST['csrf'] ?? null;

if (!is_string($tokenInviato) || !csrfValido($tokenInviato)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Richiesta non valida.';
    exit;
}

logoutAdmin();
header('Location: /admin/login.php');
exit;
