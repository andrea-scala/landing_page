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
<section id="hero" class="hero-mt bg-tech-light bg-white d-flex flex-grow-1 justify-content-center py-0">
  <div class="container-fluid text-start row px-3 px-lg-10 py-lg-12 reveal visible d-flex">
    <div class="col-12 col-lg-8 d-flex flex-column align-items-start justify-content-evenly py-9 gap-3"><span
        class="badge text-bg-primary-inverso px-0 text-uppercase"><?= citytel_e($c['badge']) ?></span>
      <h1 class="display-5 fw-bold w-lg-90 align-self-start"><?= citytel_e($c['titolo']) ?></h1>
      <p class="lead fw-normal w-lg-70 flex-grow-1"><?= citytel_e($c['sottotitolo']) ?></p>
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
    <div class="col-12 col-lg-4 d-none d-lg-flex justify-content-center h-100">
      <video id="video-bg" data-keepplaying="" data-autoplay="" autoplay="" loop="" playsinline="" muted=""
        src="assets/video/hero_bg.mp4"
        style="translate: none;rotate: none;scale: .9;opacity: 1;/* transform: translate(5%, 0px); */"></video>
    </div>
    <!-- <div class="container-fluid text-start px-3 px-lg-10 my-4 my-lg-12"> -->

  </div>
</section>