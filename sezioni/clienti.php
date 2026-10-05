<?php
require_once __DIR__ . '/../includes/content-helper.php';

$c = getContent('clienti', [
    'badge'       => 'Clienti',
    'titolo'      => '',
    'sottotitolo' => '',
    'lista'       => [],
]);
?>
<section class="py-4 bg-light macro-section" id="clienti">
    <div class="container-fluid px-4 px-lg-0 text-start section-padding-lg py-5">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start"><?= citytel_e($c['badge']) ?></span>
        <h2 class="mb-2 fw-bold w-lg-65"><?= citytel_e($c['titolo']) ?></h1>
        <p class="lead fw-normal mb-4 w-lg-35 fs-6 text-justify manrope-paragrafi-regular"><?= citytel_e($c['sottotitolo']) ?></p>
        <div style="overflow:hidden">
        <div class="logo-strip gap-5 py-4">
            <?php foreach (array_merge($c['lista'], $c['lista']) as $cliente): ?>
                <img src="./assets/img/loghi/<?= citytel_e($cliente['logo'] ?? '') ?>"
                     alt="<?= citytel_e($cliente['nome'] ?? '') ?>"
                     class="logo-client flex-shrink-0" height="32" loading="lazy">
            <?php endforeach; ?>
        </div>
        </div>
    </div>
</section>