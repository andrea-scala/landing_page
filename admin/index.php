<?php
declare(strict_types=1);

/**
 * PAGINA DELL'EDITOR (solo admin)
 * Senza sessione valida richiediAdmin() rimanda al login ed esce subito,
 * prima che venga stampato qualsiasi HTML dell'editor.
 */
require_once __DIR__ . '/../includes/auth.php';

richiediAdmin();

header('Cache-Control: no-store');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="utf-8">
    <title>Editor Contenuti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="<?= htmlspecialchars(tokenCsrf(), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Manrope:wght@200..800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <nav class="navbar bg-white shadow-sm sticky-top">
        <div class="container-fluid px-4 px-lg-5 py-1">
            <span class="navbar-brand fw-bold m-0">Citytel Sistem <span class="text-primary">· Editor
                    Contenuti</span></span>
            <div class="d-flex align-items-center gap-2">
                <!-- Menu delle sezioni: viene riempito da editor.js all'avvio -->
                <select id="sezione-select" class="form-select form-select-sm" aria-label="Sezione da modificare">
                    <option>Caricamento…</option>
                </select>
                <span id="check" class="badge text-bg-secondary">…</span>
                <button id="btn-reset" class="btn btn-outline-secondary rounded-pill fs-7 px-3">
                    <i class="bi bi-arrow-counterclockwise"></i> Ripristina
                </button>
                <button id="btn-copy" class="btn btn-primary rounded-pill fs-7 px-4">
                    <i class="bi bi-clipboard"></i> Copia JSON
                </button>
                <button type="button" id="btn-save" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salva sul server
                </button>
                <form method="post" action="/admin/logout.php" class="m-0">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars(tokenCsrf(), ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" id="btn-logout" class="btn btn-outline-secondary rounded-pill fs-7 px-3">
                        <i class="bi bi-box-arrow-right"></i> Esci
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-4 px-lg-5 py-4">
        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="card panel">
                    <div class="card-body p-4">
                        <div id="editor_holder"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card panel panel-sticky">
                    <div class="card-body p-4">
                        <span class="badge text-bg-primary-inverso px-0 mb-3 text-uppercase">JSON risultante</span>
                        <pre id="output" class="output"></pre>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Libreria JSON Editor -->
    <script src="https://cdn.jsdelivr.net/npm/@json-editor/json-editor@2.15.2/dist/jsoneditor.min.js"></script>

    <!-- Schemi delle sezioni: ognuno definisce una costante { id, titolo, schema } -->
    <script src="sezioni/hero.js"></script>
    <script src="sezioni/servizi.js"></script>
    <script src="sezioni/chisiamo.js"></script>
    <script src="sezioni/clienti.js"></script>
    <script src="sezioni/lavori.js"></script>
    <script src="sezioni/footer.js"></script>

    <!-- Estensioni (editor personalizzati) e logica principale: devono stare DOPO gli schemi -->
    <script src="estensioni/iconpicker.js"></script>
    <script src="editor.js"></script>
</body>

</html>