<?php
declare(strict_types=1);

require_once __DIR__ . '/auth-check.php';

$jsonFiles = glob(CITYTEL_CONTENT_DIR . '*.json') ?: [];
sort($jsonFiles);

$status  = $_GET['status'] ?? '';
$page    = $_GET['page'] ?? '';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - Citytel Sistem Srl</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/base.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand navbar-dark bg-tech-dark">
    <div class="container">
        <span class="navbar-brand mb-0 h1 fs-6">Citytel Sistem — Pannello Contenuti</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white small">Utente: <?= htmlspecialchars((string) ($_SESSION['admin_username'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill">Esci</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <?php if ($status === 'ok'): ?>
        <div class="alert alert-success rounded-0" role="alert">
            Contenuti di "<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>" salvati correttamente.
        </div>
    <?php elseif ($status === 'error'): ?>
        <div class="alert alert-danger rounded-0" role="alert">
            Si è verificato un errore durante il salvataggio. Riprova.
        </div>
    <?php endif; ?>

    <h1 class="h4 mb-4">Pagine gestibili</h1>

    <?php if (empty($jsonFiles)): ?>
        <p class="text-secondary">Nessun file di contenuto trovato in <code>content/</code>.</p>
    <?php else: ?>
        <div class="list-group rounded-0">
            <?php foreach ($jsonFiles as $filePath): ?>
                <?php $pageName = basename($filePath, '.json'); ?>
                <a href="edit.php?page=<?= urlencode($pageName) ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center rounded-0">
                    <span class="fw-medium"><?= htmlspecialchars(citytel_admin_label_from_key($pageName), ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="text-secondary small"><?= htmlspecialchars(basename($filePath), ENT_QUOTES, 'UTF-8') ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>