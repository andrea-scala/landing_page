<?php
require_once __DIR__ . '/partials/content-helper.php';
$c = getContent('lavori', [
    'badge' => 'Lavori',
    'titolo' => '',
    'sottotitolo' => '',
    'lista' => [],
]);
?>
<section class="py-4 bg-white macro-section" id="lavori">
    <div class="container-fluid px-4 px-lg-0 text-start section-padding-lg py-5">
        <span
            class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start"><?= citytel_e($c['badge']) ?></span>
        <h1 class="mb-2 fw-bold w-lg-65"><?= citytel_e($c['titolo']) ?></h1>
        <p class="lead fw-normal mb-4 w-lg-55 fs-6 text-justify manrope-paragrafi-regular">
            <?= citytel_e($c['sottotitolo']) ?>
        </p>
        <div class="row py-4 gy-4 gy-lg-0">
            <?php foreach ($c['lista'] as $l): ?>
                <div class="col-12 col-lg-4 d-flex flex-column align-items-center">
                    <?php
                    $img = $l['img'];
                    $title = $l['title'];
                    $description = $l['description'];
                    $url = $l['url'];
                    include __DIR__ . '/partials/lavoro-card.php';
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>