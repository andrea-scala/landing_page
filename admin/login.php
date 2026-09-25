<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$errorMessage = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $csrfToken = (string) ($_POST['csrf_token'] ?? '');
    $username  = trim((string) ($_POST['username'] ?? ''));
    $password  = (string) ($_POST['password'] ?? '');

    if (!citytel_admin_csrf_validate($csrfToken)) {
        $errorMessage = 'Sessione scaduta, ricarica la pagina e riprova.';
    } elseif (
        hash_equals(ADMIN_USERNAME, $username)
        && password_verify($password, ADMIN_PASSWORD_HASH)
    ) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in']     = true;
        $_SESSION['admin_username']      = $username;
        $_SESSION['admin_last_activity'] = time();
        header('Location: index.php');
        exit;
    } else {
        // Piccolo ritardo per mitigare tentativi automatizzati.
        usleep(500000);
        $errorMessage = 'Credenziali non valide.';
    }
}

$csrfToken = citytel_admin_csrf_token();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accesso Admin - Citytel Sistem Srl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/base.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-8 col-lg-5 col-lg-4">
            <h1 class="h4 mb-4 text-center">Pannello Admin</h1>

            <?php if ($errorMessage !== ''): ?>
                <div class="alert alert-danger rounded-0" role="alert"><?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="post" action="login.php" novalidate class="card p-4 rounded-0">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Nome utente</label>
                    <input type="text" class="form-control rounded-0" id="username" name="username" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control rounded-0" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill">Accedi</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>