<?php require_once __DIR__ . '/partials/service-icon-helper.php'; ?>
<section class="py-4 bg-light macro-section" id="servizi">
    <div class="container-fluid px-4 px-md-0 text-start section-padding-md py-5 d-flex flex-column h-100">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start">Servizi</span>
        <h1 class="mb-3 fw-bold w-md-65">Reti, impianti, sicurezza.</h1>
        <p class="lead fw-normal mb-4 w-md-45 fs-6 text-justify manrope-paragrafi-regular">Massima qualità e affidabilità in ogni
            progetto: soluzioni su misura per ogni sfida tecnologica, dalla progettazione alla messa in servizio.</p>
        <?php
        $servizi_evidenza = [
            [
                'icon' => 'antenna',
                'title' => 'Progettazione FTTH',
                'items' => ['Fibra ottica fino a casa del cliente', 'Piani di Numerazione Internet TIM e Open Fiber', 'Progettazione su misura']
            ],
            [
                'icon' => 'code',
                'title' => 'Sviluppo software',
                'items' => ['Applicazioni personalizzate', 'Gestionali su misura per le esigenze operative dell\'azienda']
            ],
            [
                'icon' => 'tools',
                'title' => 'Impianti',
                'items' => ['Progettazione', 'Installazione', 'Messa in servizio']
            ],
            [
                'icon' => 'network',
                'title' => 'Cablaggi e reti LAN/WAN',
                'items' => ['Reti dati robuste e scalabili', 'Manutenzione apparati attivi']
            ],
            [
                'icon' => 'plug',
                'title' => 'Posa e collaudo',
                'items' => ['Posa e giunzione', 'Terminazione e collaudo', 'Bassa latenza, alta affidabilità']
            ],
            [
                'icon' => 'road',
                'title' => 'Infrastrutture stradali e ripristini',
                'items' => ['Opere stradali', 'Ripristini a norma', 'Standard di qualità e sicurezza']
            ],
        ];
        $servizi_altri = [
            [
                'icon' => 'solar-panel',
                'title' => 'Impianti fotovoltaici',
                'items' => ['Installazione', 'Manutenzione', 'Alta efficienza energetica']
            ],
            [
                'icon' => 'flame',
                'title' => 'Sicurezza e antincendio',
                'items' => ['Impianti antincendio', 'Antintrusione', 'Controllo accessi']
            ],
            [
                'icon' => 'video',
                'title' => 'Videosorveglianza',
                'items' => ['Progettazione sistemi', 'Controllo continuo degli ambienti']
            ],
            [
                'icon' => 'cpu',
                'title' => 'Cavi e precablati',
                'items' => ['Cavi preconnettorizzati', 'Quadristica precablata testata e pronta all\'uso']
            ],
        ];
        ?>
        <div class="row mb-3 mt-md-0 px-0 justify-content-around g-1">
            <?php foreach ($servizi_evidenza as $s): ?>
                <div class="col-md-4 align-items-center d-flex flex-column gy-4">
                    <?php citytel_render_service_card($s, 'default'); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <button class="btn btn-primary btn-lg my-5 rounded-pill fs-6 px-3 py-2 flex-grow-1 flex-basis-0"
                type="button" id="btnAltriServizi" data-bs-toggle="collapse" data-bs-target="#altriServizi"
                aria-expanded="false" aria-controls="altriServizi">
                Altri servizi
            </button>
        </div>
        <div class="collapse mb-3" id="altriServizi">
            <div class="row mt-md-0 px-0 justify-content-around g-1">
                <?php foreach ($servizi_altri as $s): ?>
                    <div class="col-md-3 align-items-center d-flex flex-column gy-4">
                        <?php citytel_render_service_card($s, 'small'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>