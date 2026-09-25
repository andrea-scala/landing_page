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
<section id="hero" class="py-4 py-md-12 hero-mt bg-tech-light" style="">
  <div class="container-fluid text-start px-3 px-md-10">
  <!-- <div class="container-fluid text-start px-3 px-md-10 my-4 my-md-12"> -->
    <span class="badge text-bg-primary-inverso px-0 mt-5 text-uppercase"><?= citytel_e($c['badge']) ?></span>
    <h1 class="display-5 mt-4 mb-0 fw-bold w-md-65"><?= citytel_e($c['titolo']) ?></h1>
    <p class="lead fw-normal mt-4 mt-md-4 mb-0 w-md-50"><?= citytel_e($c['sottotitolo']) ?></p>
    <div class="d-flex flex-row my-0">
      <div class="col-12 col-md-hero-cta d-flex column-gap-3">
        <?php foreach ($c['cta'] as $btn): ?>
          <a href="<?= citytel_e($btn['link'] ?? '#') ?>"
             class="btn btn-primary btn-lg my-5 rounded-pill fs-6 px-md-4 py-md-3 p-3 flex-grow-1 flex-basis-0">
            <?= citytel_e($btn['testo'] ?? '') ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>