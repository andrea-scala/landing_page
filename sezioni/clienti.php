<!-- ===========================
     VERSIONE A — CON LOGHI
=========================== -->
<section class="py-4 bg-white macro-section" id="clienti">
    <div class="container-fluid px-4 px-md-0 text-start section-padding-md py-5">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start">Clienti</span>
        <h1 class="mb-3 fw-bold w-md-65">Chi si affida a noi</h1>
        <p class="lead fw-normal mb-4 w-md-35 fs-6 text-justify manrope-paragrafi-regular">Enti pubblici e grandi
            realtà private che ci hanno scelto per progetti di infrastruttura e telecomunicazioni.</p>
        <?php
        $clienti = [
            ['nome' => 'Open Fiber', 'logo' => 'logo-openfiber.png'],
            ['nome' => 'TIM', 'logo' => 'logo-tim.svg'],
            ['nome' => 'Fondazione Policlinico Universitario Agostino Gemelli', 'logo' => 'logo-gemelli.png'],
            ['nome' => 'Terna', 'logo' => 'logo-terna.png'],
            ['nome' => 'Interporto Campano', 'logo' => 'logo-interporto.svg'],
            ['nome' => 'CIS', 'logo' => 'logo-cis.png'],
            ['nome' => 'Sirti', 'logo' => 'logo-sirti.svg'],
            ['nome' => 'Sielte', 'logo' => 'logo-sielte.png'],
            ['nome' => 'Valtellina', 'logo' => 'logo-valtellina.png'],
        ];
        ?>
        <div style="overflow:hidden">
        <div class="logo-strip gap-5 py-4">
            <?php foreach (array_merge($clienti, $clienti) as $c): ?>
                <img src="./assets/img/loghi/<?= htmlspecialchars($c['logo']) ?>"
                     alt="<?= htmlspecialchars($c['nome']) ?>"
                     class="logo-client flex-shrink-0" height="32" loading="lazy">
            <?php endforeach; ?>
        </div>
        </div>
    </div>
</section>