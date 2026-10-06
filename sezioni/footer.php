<?php
require_once __DIR__ . '/../includes/icon-helper.php';
require_once __DIR__ . '/../includes/content-helper.php';

$c = getContent('footer', [
    'info' => [
        'titolo' => 'Citytel Sistem Srl',
        'voci' => [],
    ],
    'mappa' => [
        'src' => '',
        'titolo' => 'Mappa della sede',
    ],
    'sezioni' => [
        'titolo' => 'Sezioni',
        'voci' => [],
    ],
    'contatti' => [
        'titolo' => 'Contatti',
        'voci' => [],
    ],
    'social' => [
        'titolo' => 'Social',
        'voci' => [],
    ],
    'legale' => [
        'copyright' => 'Tutti i diritti riservati',
        'voci' => [],
    ],
]);

$info = $c['info'] ?? [];
$infoTitolo = $info['titolo'] ?? 'Citytel Sistem Srl';
$infoVoci = $info['voci'] ?? [];

$mappa = $c['mappa'] ?? [];
$mappaSrc = $mappa['src'] ?? '';
$mappaTitolo = $mappa['titolo'] ?? 'Mappa della sede';

$sezioni = $c['sezioni'] ?? [];
$sezioniTitolo = $sezioni['titolo'] ?? 'Sezioni';
$sezioniVoci = $sezioni['voci'] ?? [];

$contatti = $c['contatti'] ?? [];
$contattiTitolo = $contatti['titolo'] ?? 'Contatti';
$contattiVoci = $contatti['voci'] ?? [];

$social = $c['social'] ?? [];
$socialTitolo = $social['titolo'] ?? 'Social';
$socialVoci = $social['voci'] ?? [];

$legale = $c['legale'] ?? [];
$legaleCopyright = $legale['copyright'] ?? '';
$legaleVoci = $legale['voci'] ?? [];
?>
<!-- ===========================
     FOOTER LEGALE + MAPPA
=========================== -->
<footer class="bg-tech-dark text-white" id="footer">
    <div class="footer-map">
        <iframe src="<?= citytel_e($mappaSrc) ?>" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" title="<?= citytel_e($mappaTitolo) ?>">
        </iframe>
    </div>

    <div class="container-fluid d-flex flex-column pt-5 px-5">
        <div class="row g-4">
            <div class="col-lg-4">
                <h6 class="fw-bold mb-3"><?= citytel_e($infoTitolo) ?></h6>
                <ul class="list-group">
                    <?php foreach ($infoVoci as $v): ?>
                        <li class="list-group-item bg-transparent border-0 px-0 py-1 text-white">
                            <?php if (!empty($v['titolo'])): ?>
                                <?= citytel_e($v['titolo']) ?>:
                            <?php endif; ?>
                            <?php if (!empty($v['href'])): ?>
                                <a href="<?= citytel_e($v['href']) ?>"
                                    class="text-white"><?= citytel_e($v['testo'] ?? '') ?></a>
                            <?php else: ?>
                                <?= citytel_e($v['testo'] ?? '') ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-3">
                <h6 class="fw-bold mb-3"><?= citytel_e($sezioniTitolo) ?></h6>
                <ul class="list-group">
                    <?php foreach ($sezioniVoci as $v): ?>
                        <li class="list-group-item bg-transparent border-0 px-0 py-1">
                            <a href="<?= citytel_e($v['href'] ?? '#') ?>"
                                class="text-white text-decoration-none"><?= citytel_e($v['titolo'] ?? '') ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-3">
                <h6 class="fw-bold mb-3"><?= citytel_e($contattiTitolo) ?></h6>
                <ul class="list-group">
                    <?php foreach ($contattiVoci as $v): ?>
                        <li class="list-group-item bg-transparent border-0 px-0 py-1 text-white">
                            <?= citytel_e($v['titolo'] ?? '') ?>: <a href="<?= citytel_e($v['href'] ?? '#') ?>"
                                class="text-white"><?= citytel_e($v['testo'] ?? '') ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-lg-2">
                <h6 class="fw-bold mb-3"><?= citytel_e($socialTitolo) ?></h6>
                <ul class="list-group list-group-horizontal">
                    <?php foreach ($socialVoci as $v): ?>
                        <li class="list-group-item bg-transparent border-0 ps-0">
                            <a href="<?= citytel_e($v['href'] ?? '#') ?>" target="_blank" rel="noopener noreferrer"
                                aria-label="<?= citytel_e($v['ariaLabel'] ?? '') ?>"><?= citytel_load_icon($v['icona'] ?? '', 'text-white', 'social') ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <hr class="border-secondary mt-4 mb-0">
        <div id="copyright_container" class="row py-2 gx-0 align-items-center justify-content-center">
            <div class="col-12 col-lg-auto">
                <h6 class="small fw-normal text-center mb-2 mb-lg-0 me-lg-4">&copy; <?= date('Y') ?>
                    <?= citytel_e($legaleCopyright) ?></h6>
            </div>
            <div class="col-12 col-lg-auto">
                <ul class="list-group small list-group-horizontal justify-content-center flex-wrap">
                    <?php foreach ($legaleVoci as $v): ?>
                        <li class="list-group-item bg-transparent border-0 text-center py-1">
                            <a href="<?= citytel_e($v['href'] ?? '#') ?>"
                                class="text-white text-decoration-underline"><?= citytel_e($v['titolo'] ?? '') ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</footer>