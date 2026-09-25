<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- SEO -->
    <meta name="description"
        content="EasyTrack: Monitoraggio, Sicurezza e Ottimizzazione per infrastrutture e processi sanitari.">
    <meta name="author" content="EasyTrack">
    <!-- Open Graph -->
    <meta property="og:title" content="EasyTrack ® Monitoraggio, Sicurezza e Ottimizzazione">
    <meta property="og:description"
        content="Soluzione integrata per monitoraggio, sicurezza e ottimizzazione operativa.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/assets/img/og-image.jpg">
    <meta property="og:url" content="https://www.easytrack.it">
    <!-- Favicon -->
    <link rel="icon" href="/assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <title>EasyTrack® Monitoraggio, Sicurezza e Ottimizzazione</title>
    <!-- ===== FRAMEWORKS ===== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- ===== FONTS ===== -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Inter:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
    <!-- ===== DESIGN SYSTEM LAYER ===== -->
    <link rel="stylesheet" href="./assets/css/variables.css">
    <link rel="stylesheet" href="./assets/css/base.css">
    <link rel="stylesheet" href="./assets/css/components.css">
    <link rel="stylesheet" href="./assets/css/layout-utils.css">
    <link rel="stylesheet" href="./assets/css/spacing-overrides.css">
    <link rel="stylesheet" href="./assets/css/page-specific.css">
</head>

<body>
    <!-- ===========================
         NAVBAR
    =========================== -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container-fluid mx-md-5">
            <a class="navbar-brand fw-bold" href="#">
                <img alt="placeholder_alt_text" class="img-fluid" style="height:50px;width:auto"
                    src="./assets/img/loghi/logo-citytel.png">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#chi-siamo">Chi siamo</a></li>
                    <li class="nav-item"><a class="nav-link" href="#servizi">Servizi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lavori">Lavori</a></li>
                    <li class="nav-item"><a class="nav-link" href="#perche-sceglierci">Perchè sceglierci</a></li>
                    <li class="nav-item"><a class="nav-link" href="#clienti">Clienti</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lavora-con-noi">Lavora con noi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#footer">Contatti</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <?php require 'sezioni/hero.php'; ?>
        <?php require 'sezioni/chisiamo.php'; ?>
        <?php require 'sezioni/servizi.php'; ?>
        <?php require 'sezioni/lavori.php'; ?>
        <?php require 'sezioni/perche-sceglierci.php'; ?>
        <?php require 'sezioni/clienti.php'; ?>
        <?php require 'sezioni/lavora-con-noi.php'; ?>
    </main>
    <?php require 'sezioni/footer.php'; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>