<?php
declare(strict_types=1);

/**
 * Partial <head> condiviso (solo markup + defaults).
 *
 * Emette il CONTENUTO del <head>; il tag <head>...</head> lo scrive chi lo include:
 *
 *   <?php $pageTitle = 'Privacy Policy - Citytel Sistem Srl'; ?>
 *   <head><?php require __DIR__ . '/../partials/head.php'; ?></head>
 *
 * Variabili opzionali da impostare PRIMA dell'include:
 *   string $pageTitle        Titolo della pagina.
 *   string $pageDescription  Meta description.
 *   bool   $pageOg           true = stampa anche i meta Open Graph (solo home).
 *
 * Tutti i percorsi asset sono ASSOLUTI (/assets/...): così funzionano
 * da qualunque profondità (index.php, sezioni/legal/*.php).
 * Presuppone che il sito stia nella root del dominio.
 */

require_once __DIR__ . '/../../includes/content-helper.php';

$pageTitle       ??= 'CityTel Sistem Srl';
$pageDescription ??= "CityTel Sistem Srl è un'azienda specializzata in telecomunicazioni e impianti, con un team tecnico esperto e qualificato. Offriamo soluzioni innovative e personalizzate per soddisfare le esigenze dei nostri clienti.";
$pageOg          ??= false;
?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= citytel_e($pageTitle) ?></title>
    <!-- SEO -->
    <meta name="description" content="<?= citytel_e($pageDescription) ?>">
    <meta name="author" content="CityTel Sistem Srl">
<?php if ($pageOg): ?>
    <!-- Open Graph -->
    <meta property="og:title" content="<?= citytel_e($pageTitle) ?>">
    <meta property="og:description" content="<?= citytel_e($pageDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/assets/img/og-image.jpg">
    <meta property="og:url" content="https://www.citytel.it">
<?php endif; ?>
    <!-- Favicon -->
    <link rel="icon" href="/assets/img/icons/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/icons/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/img/icons/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/img/icons/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/img/icons/favicon/site.webmanifest">
    <!-- ===== FRAMEWORKS ===== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
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
    <link rel="stylesheet" href="/assets/css/variables.css">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/layout-utils.css">
    <link rel="stylesheet" href="/assets/css/spacing-overrides.css">
    <link rel="stylesheet" href="/assets/css/page-specific.css">
    <link rel="stylesheet" href="/assets/css/smooth-css/style.css">
