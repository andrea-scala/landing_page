<?php
declare(strict_types=1);

/**
 * PAGINA DI LOGIN DELL'ADMIN
 * ==========================
 * GET  -> mostra il form.
 * POST -> controlla in ordine:
 *   1. il token CSRF (il form arriva davvero da questa pagina);
 *   2. che l'IP non sia già bloccato per troppi tentativi;
 *   3. la password.
 * Password giusta -> sessione admin e redirect all'editor.
 * Password sbagliata -> il tentativo viene contato e si rimostra il form.
 *
 * Il messaggio di errore è sempre generico: non dice mai se è sbagliato "un
 * pezzo" o un altro, e non rivela nulla sul server.
 */

require_once __DIR__ . '/../includes/auth.php';

// Intestazioni di sicurezza: niente cache della pagina, non incorporabile in
// un <iframe> altrui (clickjacking), il browser non "indovina" i tipi di file.
header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');

// Già loggato: inutile vedere il login.
if (adminLoggato()) {
    header('Location: /admin/');
    exit;
}

$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip           = indirizzoClient();
    $tokenInviato = $_POST['csrf'] ?? null;
    $password     = $_POST['password'] ?? '';

    // is_string(): se qualcuno manda "password[]=x" arriva un array, che non
    // deve mai finire nelle funzioni sotto.
    if (!is_string($tokenInviato) || !csrfValido($tokenInviato)) {
        http_response_code(400);
        $errore = 'Sessione scaduta. Riprova.';
    } elseif (tentativiBloccati($ip)) {
        http_response_code(429);
        $errore = 'Troppi tentativi falliti. Riprova tra circa '
            . intdiv(FINESTRA_TENTATIVI_SECONDI, 60) . ' minuti.';
    } elseif (is_string($password) && verificaPasswordAdmin($password)) {
        azzeraTentativi($ip);
        impostaAdminLoggato();   // cambia ID di sessione e token CSRF
        header('Location: /admin/');
        exit;
    } else {
        registraTentativoFallito($ip);
        http_response_code(401);
        $errore = 'Password non valida.';
    }
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <title>CityTelSistem · Editor Contenuti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Manrope:wght@200..800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="../assets/min/css/variables.min.css">
    <link rel="stylesheet" href="../assets/min/css/base.min.css">
    <link rel="stylesheet" href="../assets/min/css/components.min.css">
    <link rel="stylesheet" href="../assets/min/css/layout-utils.min.css">
    <link rel="stylesheet" href="../assets/min/css/spacing-overrides.min.css">
    <link rel="stylesheet" href="../assets/min/css/page-specific.min.css"></main>
    <link rel="stylesheet" href="style.css">
    <style>
        .card:hover{
            transform: unset;
        }
        .card-body img{
            height: auto;
        }
    </style>
</head>

<body style="background-color: var(--color-primary-dark);">
    <main class="container vh-100">
        <div class="row justify-content-center h-100">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 d-flex flex-column">
                <div class="card panel w-100 m-auto">
                    <div class="card-body p-5">
                        <img alt="Citytel Sistem Srl - Vai alla home" class="img-fluid navbar-logo m-auto mb-4"
                src="../assets/img/loghi/logo-citytel.svg" >
                        <p class="fs-5 mb-2 fw-medium">Accesso riservato</p>
                        <p class="text-secondary mb-3">Accedi per editare i contenuti del sito</p>

                        <?php if ($errore !== ''): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($errore, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="../admin/login.php">
                            <input type="hidden" name="csrf"
                                value="<?= htmlspecialchars(tokenCsrf(), ENT_QUOTES, 'UTF-8') ?>">

                            <div class="mb-3">
                                <label for="password" class="form-label fs-7 fw-medium">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    autocomplete="current-password" required autofocus>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">Accedi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
