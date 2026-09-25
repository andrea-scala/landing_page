<?php
require_once __DIR__ . '/partials/content-helper.php';

$c = getContent('home', [
    'badge'       => '',
    'titolo'      => '',
    'sottotitolo' => '',
    'cta'         => [],
]);
?>
<!-- <section id="hero" class="hero-mt bg-tech-light p-0" style="background-image: url('assets/img/hero_bg.jpg');"> -->
<section id="hero" class="py-4 py-lg-12 hero-mt bg-tech-light" style="">
  <div class="container-fluid text-start px-3 px-lg-10">
  <!-- <div class="container-fluid text-start px-3 px-lg-10 my-4 my-lg-12"> -->
    <span class="badge text-bg-primary-inverso px-0 mt-5 text-uppercase"><?= citytel_e($c['badge']) ?></span>
    <h1 class="display-5 mt-4 mb-0 fw-bold w-lg-65"><?= citytel_e($c['titolo']) ?></h1>
    <p class="lead fw-normal mt-4 mt-lg-4 mb-0 w-lg-50"><?= citytel_e($c['sottotitolo']) ?></p>
    <div class="d-flex flex-row my-0">
      <div class="col-12 col-lg-hero-cta d-flex column-gap-3">
        <?php foreach ($c['cta'] as $btn): ?>
          <a href="<?= citytel_e($btn['link'] ?? '#') ?>"
             class="btn btn-primary btn-lg my-5 rounded-pill fs-6 px-lg-4 py-lg-3 p-3 flex-grow-1 flex-basis-0">
            <?= citytel_e($btn['testo'] ?? '') ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>