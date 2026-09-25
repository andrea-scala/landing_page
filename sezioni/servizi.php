<?php
require_once __DIR__ . '/partials/service-icon-helper.php';
require_once __DIR__ . '/partials/content-helper.php';

$c = getContent('servizi', [
    'badge'       => 'Servizi',
    'titolo'      => '',
    'sottotitolo' => '',
    'evidenza'    => [],
    'altri'       => [],
]);
?>
<section class="py-4 bg-light macro-section" id="servizi">
    <div class="container-fluid px-4 px-lg-0 text-start section-padding-lg py-5 d-flex flex-column h-100">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start"><?= citytel_e($c['badge']) ?></span>
        <h1 class="mb-2 fw-bold w-lg-65"><?= citytel_e($c['titolo']) ?></h1>
        <p class="lead fw-normal mb-4 w-lg-45 fs-6 text-justify manrope-paragrafi-regular"><?= citytel_e($c['sottotitolo']) ?></p>

        <div class="row mb-3 mt-lg-0 px-0 justify-content-around g-1">
            <?php foreach ($c['evidenza'] as $s): ?>
                <div class="col-lg-4 align-items-center d-flex flex-column gy-4">
                    <?php citytel_render_service_card($s, 'default'); ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center">
            <button class="btn btn-primary btn-lg my-5 rounded-pill fs-6 px-3 py-2 flex-grow-1 flex-basis-0"
                type="button" id="btnAltriServizi" data-bs-toggle="collapse" data-bs-target="#altriServizi"
                aria-expanded="false" aria-controls="altriServizi">
                Altri servizi
            </button>
        </div>

        <div class="collapse mb-3" id="altriServizi">
            <div class="row mt-lg-0 px-0 justify-content-around g-1">
                <?php foreach ($c['altri'] as $s): ?>
                    <div class="col-lg-3 align-items-center d-flex flex-column gy-4">
                        <?php citytel_render_service_card($s, 'small'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>