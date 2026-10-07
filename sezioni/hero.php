<?php
require_once __DIR__ . '/../includes/content-helper.php';

$c = getContent('hero', [
  'badge' => '',
  'titolo' => '',
  'sottotitolo' => '',
  'cta' => [],
]);
?>
<section id="hero" class="hero-mt bg-tech-light bg-white d-flex flex-grow-1 align-items-center py-6 py-lg-8">
  <div class="container-fluid px-3 px-lg-12">
    <div class="row align-items-center">

      <!-- Colonna Testo -->
      <div class="col-12 col-lg-8 d-flex flex-column align-items-start gap-3 text-start">
        <span class="badge text-bg-primary-inverso px-0 text-uppercase"><?= citytel_e($c['badge']) ?></span>

        <!-- Usa la classe BEM/Component pulita -->
        <h1 class="hero-title w-lg-90"><?= citytel_e($c['titolo']) ?></h1>

        <p class="lead fw-normal w-lg-70"><?= citytel_e($c['sottotitolo']) ?></p>

        <div class="d-flex flex-row my-0 w-100">
          <div class="col-12 col-lg-hero-cta d-flex column-gap-3">
            <?php foreach ($c['cta'] as $btn): ?>
              <a href="<?= citytel_e($btn['link'] ?? '#') ?>"
                class="btn btn-primary btn-lg rounded-pill fs-7 fs-lg-6 px-lg-4 py-lg-3 p-3 flex-grow-1">
                <?= citytel_e($btn['testo'] ?? '') ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Colonna Video -->
      <div class="col-12 col-lg-4 d-none d-lg-flex justify-content-center align-items-center">
        <!-- Video puramente decorativo: nascosto alle tecnologie assistive -->
        <video id="video-bg" class="hero-video img-fluid rounded" data-keepplaying="" data-autoplay="" autoplay=""
          loop="" playsinline="" muted="" aria-hidden="true" src="assets/video/hero_bg.mp4"></video>
      </div>

    </div>
  </div>
</section>