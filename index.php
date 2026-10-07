<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Parametri del partial head (home: con Open Graph).
$pageOg = true;
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <?php require __DIR__ . '/sezioni/partials/head.php'; ?>
</head>

<body>
    <?php require __DIR__ . '/sezioni/partials/navbar.php'; ?>
    <main>
        <?php require 'sezioni/hero.php'; ?>
        <?php require 'sezioni/chisiamo.php'; ?>
        <?php require 'sezioni/servizi.php'; ?>
        <?php require 'sezioni/lavori.php'; ?>
        <?php require 'sezioni/perche-sceglierci.php'; ?>
        <?php require 'sezioni/clienti.php'; ?>
        <?php require 'sezioni/lavora-con-noi.php'; ?>
        <?php require 'sezioni/partials/back-to-top.php'; ?>
    </main>
    <?php require 'sezioni/footer.php'; ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/min/js/main.min.js"></script>
    <script src="./assets/min/js/smooth-js/main.min.js"></script>
</body>

</html>