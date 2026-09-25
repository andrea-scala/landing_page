<?php
declare(strict_types=1);

require_once __DIR__ . '/auth-check.php';

$pageName = (string) ($_GET['page'] ?? '');

if (!citytel_valid_page_name($pageName)) {
    http_response_code(400);
    exit('Nome pagina non valido.');
}

$filePath = CITYTEL_CONTENT_DIR . $pageName . '.json';

if (!is_readable($filePath)) {
    http_response_code(404);
    exit('File di contenuto non trovato.');
}

$data = citytel_load_json_file($filePath);
$csrfToken = citytel_admin_csrf_token();

/**
 * Renderizza ricorsivamente un campo di form in base al tipo di valore.
 *
 * - array assoc                     -> fieldset con i figli ricorsivi
 * - array lista di array (oggetti)  -> un fieldset per elemento, ricorsivo
 * - array lista di scalari          -> textarea, una voce per riga
 * - scalare                         -> input o textarea in base a euristica
 */
function citytel_admin_render_field(string $name, mixed $value, string $label): void
{
    $id = trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name) ?? '', '-');

    if (is_array($value)) {
        if ($value === [] || !array_is_list($value)) {
            // Array assoc (o vuoto): fieldset con figli ricorsivi.
            echo '<fieldset class="border rounded-0 p-3 mb-3">';
            echo '<legend class="fs-6 fw-bold">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</legend>';
            foreach ($value as $key => $childValue) {
                $childLabel = citytel_admin_label_from_key((string) $key);
                citytel_admin_render_field($name . '[' . $key . ']', $childValue, $childLabel);
            }
            echo '</fieldset>';
            return;
        }

        $isScalarList = true;
        foreach ($value as $item) {
            if (!is_scalar($item) && $item !== null) {
                $isScalarList = false;
                break;
            }
        }

        if ($isScalarList) {
            $textareaValue = implode("\n", array_map('strval', $value));
            echo '<div class="mb-3">';
            echo '<label for="' . $id . '" class="form-label">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</label>';
            echo '<textarea class="form-control rounded-0" id="' . $id . '" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" rows="4" aria-describedby="' . $id . '-hint">' . htmlspecialchars($textareaValue, ENT_QUOTES, 'UTF-8') . '</textarea>';
            echo '<div id="' . $id . '-hint" class="form-text">Una voce per riga.</div>';
            echo '</div>';
            return;
        }

        // Lista di array (oggetti): un fieldset per elemento.
        echo '<fieldset class="border rounded-0 p-3 mb-3">';
        echo '<legend class="fs-6 fw-bold">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</legend>';
        foreach ($value as $index => $item) {
            echo '<div class="border-start border-3 ps-3 mb-3">';
            echo '<p class="text-secondary small fw-bold mb-2">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ' #' . ((int) $index + 1) . '</p>';
            citytel_admin_render_field($name . '[' . $index . ']', $item, $label);
            echo '</div>';
        }
        echo '</fieldset>';
        return;
    }

    // Scalare: scegli input o textarea in base a lunghezza/nome chiave.
    $stringValue = (string) $value;
    $longFieldHints = ['paragrafo', 'descrizione', 'sottotitolo', 'testo'];
    $isLong = mb_strlen($stringValue) > 80;

    foreach ($longFieldHints as $hint) {
        if (str_contains($name, $hint)) {
            $isLong = true;
            break;
        }
    }

    echo '<div class="mb-3">';
    echo '<label for="' . $id . '" class="form-label">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</label>';

    if ($isLong) {
        echo '<textarea class="form-control rounded-0" id="' . $id . '" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" rows="3">' . htmlspecialchars($stringValue, ENT_QUOTES, 'UTF-8') . '</textarea>';
    } else {
        echo '<input type="text" class="form-control rounded-0" id="' . $id . '" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($stringValue, ENT_QUOTES, 'UTF-8') . '">';
    }

    echo '</div>';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifica <?= htmlspecialchars(citytel_admin_label_from_key($pageName), ENT_QUOTES, 'UTF-8') ?> - Admin Citytel Sistem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/variables.css">
    <link rel="stylesheet" href="../assets/css/base.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand navbar-dark bg-tech-dark">
    <div class="container">
        <span class="navbar-brand mb-0 h1 fs-6">Citytel Sistem — Pannello Contenuti</span>
        <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill">&larr; Dashboard</a>
    </div>
</nav>

<div class="container py-5" style="max-width: 900px;">
    <h1 class="h4 mb-4">Modifica: <?= htmlspecialchars(citytel_admin_label_from_key($pageName), ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if (empty($data)): ?>
        <div class="alert alert-warning rounded-0" role="alert">
            Il file <code><?= htmlspecialchars($pageName, ENT_QUOTES, 'UTF-8') ?>.json</code> è vuoto o non è stato possibile leggerlo.
        </div>
    <?php endif; ?>

    <form method="post" action="save.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="page" value="<?= htmlspecialchars($pageName, ENT_QUOTES, 'UTF-8') ?>">

        <?php foreach ($data as $key => $value): ?>
            <?php citytel_admin_render_field('fields[' . $key . ']', $value, citytel_admin_label_from_key((string) $key)); ?>
        <?php endforeach; ?>

        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4">Salva modifiche</button>
            <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">Annulla</a>
        </div>
    </form>
</div>
</body>
</html>