<?php
require_once __DIR__ . '/../includes/content-helper.php';

$c = getContent('hero', [
  'badge' => '',
  'titolo' => '',
  'sottotitolo' => '',
  'cta' => [],
]);
?>
<!-- <section id="hero" class="hero-mt bg-tech-light p-0" style="background-image: url('assets/img/hero_bg.jpg');"> -->
<section id="hero" class="py-4 py-lg-12 hero-mt bg-tech-light" style="">
  <div class="container-fluid text-start row px-3 px-lg-10">
    <div class="col-12 col-lg-8"><span
        class="badge text-bg-primary-inverso px-0 mt-5 text-uppercase"><?= citytel_e($c['badge']) ?></span>
      <h1 class="display-5 mt-4 mb-0 fw-bold w-lg-100"><?= citytel_e($c['titolo']) ?></h1>
      <p class="lead fw-normal mt-4 mt-lg-4 mb-0 w-lg-70"><?= citytel_e($c['sottotitolo']) ?></p>
      <div class="d-flex flex-row my-0">
        <div class="col-12 col-lg-hero-cta d-flex column-gap-3">
          <?php foreach ($c['cta'] as $btn): ?>
            <a href="<?= citytel_e($btn['link'] ?? '#') ?>"
              class="btn btn-primary btn-lg my-5 rounded-pill fs-7 fs-lg-6 px-lg-4 py-lg-3 p-3 flex-grow-1">
              <?= citytel_e($btn['testo'] ?? '') ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-4 d-none d-lg-block">
      <video id="video-bg" data-keepplaying="" data-autoplay="" autoplay="" loop="" playsinline="" muted=""
        src="assets/video/hero_bg.mp4"
        style="translate: none; rotate: none; scale: none; opacity: 1; transform: translate(5%, 0px);"></video>
    </div>
    <!-- <div class="container-fluid text-start px-3 px-lg-10 my-4 my-lg-12"> -->

  </div>
</section>