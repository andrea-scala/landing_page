<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

$cv_status = $_GET['cv_status'] ?? null;
$cv_msg    = $_GET['cv_msg'] ?? '';
?>
<section class="py-4 bg-light macro-section" id="lavora-con-noi">
    <div class="container-fluid px-4 px-md-0 text-start section-padding-md py-5">
        <div class="row g-4">
            <div class="col-12 col-md-7">
                <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start">Lavora con noi</span>
                <h1 class="mb-3 fw-bold">Entra nel team Citytel Sistem</h1>
                <p class="lead w-md-65 fw-normal fs-6 text-justify manrope-paragrafi-regular m-0">Invia la tua candidatura:
                    valutiamo profili tecnici per i nostri progetti in ambito telecomunicazioni e impianti.</p>
            </div>

            <div class="col-12 col-md-5">
                <?php if ($cv_status === 'ok'): ?>
                    <div class="alert alert-success rounded-0" role="alert">
                        Candidatura inviata con successo. Ti ricontatteremo al più presto.
                    </div>
                <?php elseif ($cv_status === 'error'): ?>
                    <div class="alert alert-danger rounded-0" role="alert">
                        <?= $cv_msg !== '' ? htmlspecialchars($cv_msg) : 'Si è verificato un errore, riprova.' ?>
                    </div>
                <?php endif; ?>

                <form class="row g-3" action="invia-candidatura.php" method="post" enctype="multipart/form-data" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <!-- Honeypot anti-spam: campo nascosto via CSS, non deve mai essere compilato -->
                    <div class="d-none" aria-hidden="true">
                        <label for="cv-website">Lascia vuoto questo campo</label>
                        <input type="text" id="cv-website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="cv-nome" class="form-label fs-7">Nome e cognome</label>
                        <input type="text" class="form-control rounded-0" id="cv-nome" name="nome" maxlength="150" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="cv-email" class="form-label fs-7">Email</label>
                        <input type="email" class="form-control rounded-0" id="cv-email" name="email" required>
                    </div>
                    <div class="col-12">
                        <label for="cv-file" class="form-label fs-7">Curriculum (PDF, max 5MB)</label>
                        <input type="file" class="form-control rounded-0" id="cv-file" name="cv" accept="application/pdf" required
                               aria-describedby="cv-file-help">
                        <div id="cv-file-help" class="form-text fs-7">Formato accettato: PDF. Dimensione massima 5MB.</div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cv-privacy" name="privacy" required>
                            <label class="form-check-label fs-7" for="cv-privacy">
                                Ho letto l'<a href="sezioni/legal/privacy-policy.php" class="text-primary">informativa privacy</a> e acconsento al trattamento dei dati per finalità di selezione del personale.
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary rounded-pill align-self-center px-3 py-2">Invia candidatura</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>