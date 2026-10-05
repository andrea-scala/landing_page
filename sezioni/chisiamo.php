<?php
require_once __DIR__ . '/../includes/icon-helper.php';
require_once __DIR__ . '/../includes/content-helper.php';

$c = getContent('chisiamo', [
    'badge'     => 'Chi siamo',
    'titolo'    => '',
    'paragrafo' => '',
    'img_alt'   => 'Team tecnico Citytel Sistem al lavoro',
    'timeline'  => [],
]);
?>
<section class="py-4 bg-light macro-section" id="chi-siamo">
    <div class="container-fluid text-start section-padding-lg py-5 px-4 px-lg-0 row gy-5 gx-0 gx-lg-4">
        <div class="col-12 col-lg-6 order-2 order-lg-1">
            <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase"><?= citytel_e($c['badge']) ?></span>
            <h2 class="mb-2 fw-bold w-lg-65"><?= citytel_e($c['titolo']) ?></h1>
            <p class="lead fw-normal mb-4 w-lg-65 fs-6 text-justify manrope-paragrafi-regular"><?= citytel_e($c['paragrafo']) ?></p>
        </div>
        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-lg-6 order-2 order-lg-1">
            <img src="./assets/img/chi-siamo.jpg" class="img-fluid img-responsive rounded-2" alt="<?= citytel_e($c['img_alt']) ?>">
        </div>
        <div class="col-12 order-3">
            <div class="timeline-horizontal position-relative">
                <div class="row g-0 text-center">
                    <?php foreach ($c['timeline'] as $tappa): ?>
                        <div class="col-4">
                            <div class="timeline-icon-div mx-auto mb-3">
                                <?php // Icona SVG inline (decorativa): l'helper ritorna il markup completo, niente <i class="bi ..."> ?>
                                <?= citytel_load_icon($tappa['icon'] ?? '', 'icon-timeline', 'timeline') ?>
                            </div>
                            <div class="fs-2 fw-bold"><?= citytel_e($tappa['anno'] ?? '') ?></div>
                            <div class="text-secondary small px-2"><?= citytel_e($tappa['testo'] ?? '') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>