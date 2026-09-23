<section class="py-4 macro-section bg-light" id="perche-sceglierci">
    <div class="container-fluid text-start section-padding-md py-5 d-flex flex-column h-100">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start">Perché sceglierci</span>
        <h1 class="mb-3 fw-bold w-md-65">Perché scegliere Citytel Sistem</h1>
        <p class="lead fw-normal mb-4 w-md-45 fs-6 text-justify manrope-paragrafi-regular">Certificazioni, qualifiche e garanzie
            che rendono ogni progetto tracciabile e sicuro, dalla progettazione al collaudo.</p>
        <?php
        $credenziali = [
            [
                'icon' => 'certificate',
                'title' => 'Certificazioni ISO',
                'items' => ['ISO 9001 — Qualità', 'ISO 14001 — Ambiente', 'ISO 45001 — Sicurezza sul lavoro']
            ],
            [
                'icon' => 'bolt',
                'title' => 'Abilitazione Legge 46/90',
                'items' => ['Categorie a · b · c · d · e · f · g', 'Impianti a norma di legge']
            ],
            [
                'icon' => 'building',
                'title' => 'Attestazione SOA',
                'items' => ['OS19 / III', 'OS30 / II', 'OS5 / I']
            ],
        ];
        function render_credenziale(array $c): void
        {
            $icon_svg = file_get_contents(__DIR__ . "/../assets/img/icons/credenziali/{$c['icon']}.svg");
            $icon_svg = str_replace('<svg', '<svg class="icon-service m-0"', $icon_svg);
            $title = $c['title'];
            $items = $c['items'];
            include __DIR__ . '/partials/competenze-card.php';
        }
        ?>
        <div class="row mb-4 mt-md-0 px-0 justify-content-around g-1">
            <?php foreach ($credenziali as $c): ?>
                <div class="col-md-4 align-items-center d-flex flex-column gy-4">
                    <?php render_credenziale($c); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="d-flex flex-wrap gap-2 mb-4">
            <span class="badge text-bg-primary-inverso fs-6 fw-normal px-3 py-2 rounded-pill">Qualificati TIM</span>
            <span class="badge text-bg-primary-inverso fs-6 fw-normal px-3 py-2 rounded-pill">Qualificati Fastweb</span>
            <span class="badge text-bg-primary-inverso fs-6 fw-normal px-3 py-2 rounded-pill">Qualificati Vodafone</span>
            <span class="badge text-bg-primary-inverso fs-6 fw-normal px-3 py-2 rounded-pill">Qualificati Open Fiber</span>
            <span class="badge border border-primary text-primary fs-6 fw-normal px-3 py-2 rounded-pill">Leonardo — in fase di qualificazione</span>
        </div>
        <div class="border-start border-primary border-3 ps-3 py-2">
            <p class="m-0 fs-6 manrope-paragrafi-regular">
                <strong>Garanzia:</strong> correggiamo a nostre spese eventuali errori di progettazione.
            </p>
        </div>
    </div>
</section>