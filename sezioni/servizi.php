<section class="py-4 bg-light macro-section" id="servizi">
    <div class="container-fluid text-start section-padding-md py-5 d-flex flex-column h-100">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start">Servizi</span>
        <h1 class="mb-3 fw-bold w-md-65">Reti, impianti, sicurezza.</h1>
        <p class="lead fw-normal mb-3 w-md-45 fs-6 text-justify manrope-paragrafi-regular">Massima qualità e affidabilità in ogni
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
        function render_servizio(array $s): void
        {
            $icon_svg = file_get_contents(__DIR__ . "/../assets/img/icons/services/{$s['icon']}.svg");
            //aggiungi altezza e width a 21px
            $icon_svg = str_replace('<svg', '<svg class="icon-service m-0"', $icon_svg);
            $title = $s['title'];
            $items = $s['items'];
            include __DIR__ . '/partials/service-card.php';
        }
        function render_servizio_small(array $s): void
        {
            $icon_svg = file_get_contents(__DIR__ . "/../assets/img/icons/services/{$s['icon']}.svg");
            $icon_svg = str_replace('<svg', '<svg class="icon-service-sm m-0"', $icon_svg);
            $title = $s['title'];
            $items = $s['items'];
            include __DIR__ . '/partials/service-card-small.php';
        }
        ?>
        <div class="row mb-3 mt-md-0 px-0 justify-content-around g-1">
            <?php foreach ($servizi_evidenza as $s): ?>
                <div class="col-md-4 align-items-center d-flex flex-column gy-4">
                    <?php render_servizio($s); ?>
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
                        <?php render_servizio_small($s); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 4v8" />
                        <path d="M16 4.5v7" />
                        <path d="M12 5v16" />
                        <path d="M8 5.5v5" />
                        <path d="M4 6v4" />
                        <path d="M20 8h-16" />
                    </svg>
                    <h5>Progettazione FTTH</h5>
                    <ul>
                        <li>Fibra ottica fino a casa del cliente</li>
                        <li>Piani di Numerazione Internet TIM e Open Fiber</li>
                        <li>Progettazione su misura</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 8l-4 4l4 4" />
                        <path d="M17 8l4 4l-4 4" />
                        <path d="M14 4l-4 16" />
                    </svg>
                    <h5>Sviluppo software su misura</h5>
                    <ul>
                        <li>Applicazioni personalizzate</li>
                        <li>Gestionali su misura per le esigenze operative dell'azienda</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4" />
                        <path d="M14.5 5.5l4 4" />
                        <path d="M12 8l-5 -5l-4 4l5 5" />
                        <path d="M7 8l-1.5 1.5" />
                        <path d="M16 12l5 5l-4 4l-5 -5" />
                        <path d="M16 17l-1.5 1.5" />
                    </svg>
                    <h5>Impianti chiavi in mano</h5>
                    <ul>
                        <li>Progettazione</li>
                        <li>Installazione</li>
                        <li>Messa in servizio</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9a6 6 0 1 0 12 0a6 6 0 0 0 -12 0" />
                        <path d="M12 3c1.333 .333 2 2.333 2 6s-.667 5.667 -2 6" />
                        <path d="M12 3c-1.333 .333 -2 2.333 -2 6s.667 5.667 2 6" />
                        <path d="M6 9h12" />
                        <path d="M3 20h7" />
                        <path d="M14 20h7" />
                        <path d="M10 20a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M12 15v3" />
                    </svg>
                    <h5>Cablaggi strutturati e reti LAN/WAN</h5>
                    <ul>
                        <li>Reti dati robuste e scalabili</li>
                        <li>Manutenzione apparati attivi</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9.785 6l8.215 8.215l-2.054 2.054a5.81 5.81 0 1 1 -8.215 -8.215l2.054 -2.054" />
                        <path d="M4 20l3.5 -3.5" />
                        <path d="M15 4l-3.5 3.5" />
                        <path d="M20 9l-3.5 3.5" />
                    </svg>
                    <h5>Posa e collaudo cavi rame/fibra</h5>
                    <ul>
                        <li>Posa e giunzione</li>
                        <li>Terminazione e collaudo</li>
                        <li>Bassa latenza, alta affidabilità</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 p-4 text-center">
                    <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19l4 -14" />
                        <path d="M16 5l4 14" />
                        <path d="M12 8v-2" />
                        <path d="M12 13v-2" />
                        <path d="M12 18v-2" />
                    </svg>
                    <h5>Infrastrutture stradali e ripristini</h5>
                    <ul>
                        <li>Opere stradali</li>
                        <li>Ripristini a norma</li>
                        <li>Standard di qualità e sicurezza</li>
                    </ul>
                </div>
            </div>
        </div> -->
        <!-- <div class="collapse mt-4" id="altriServizi">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card h-100 p-4 text-center">
                        <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M4.28 14h15.44a1 1 0 0 0 .97 -1.243l-1.5 -6a1 1 0 0 0 -.97 -.757h-12.44a1 1 0 0 0 -.97 .757l-1.5 6a1 1 0 0 0 .97 1.243" />
                            <path d="M4 10h16" />
                            <path d="M10 6l-1 8" />
                            <path d="M14 6l1 8" />
                            <path d="M12 14v4" />
                            <path d="M7 18h10" />
                        </svg>
                        <h5>Impianti fotovoltaici</h5>
                        <ul>
                            <li>Installazione</li>
                            <li>Manutenzione</li>
                            <li>Alta efficienza energetica</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 p-4 text-center">
                        <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M12 10.941c2.333 -3.308 .167 -7.823 -1 -8.941c0 3.395 -2.235 5.299 -3.667 6.706c-1.43 1.408 -2.333 3.294 -2.333 5.588c0 3.704 3.134 6.706 7 6.706c3.866 0 7 -3.002 7 -6.706c0 -1.712 -1.232 -4.403 -2.333 -5.588c-2.084 3.353 -3.257 3.353 -4.667 2.235" />
                        </svg>
                        <h5>Sicurezza e antincendio</h5>
                        <ul>
                            <li>Impianti antincendio</li>
                            <li>Antintrusione</li>
                            <li>Controllo accessi</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 p-4 text-center">
                        <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M15 10l4.553 -2.276a1 1 0 0 1 1.447 .894v6.764a1 1 0 0 1 -1.447 .894l-4.553 -2.276v-4" />
                            <path d="M3 8a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2l0 -8" />
                        </svg>
                        <h5>Videosorveglianza</h5>
                        <ul>
                            <li>Progettazione sistemi</li>
                            <li>Controllo continuo degli ambienti</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card h-100 p-4 text-center">
                        <svg class="icon-service mb-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M5 6a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1l0 -12" />
                            <path d="M9 9h6v6h-6l0 -6" />
                            <path d="M3 10h2" />
                            <path d="M3 14h2" />
                            <path d="M10 3v2" />
                            <path d="M14 3v2" />
                            <path d="M21 10h-2" />
                            <path d="M21 14h-2" />
                            <path d="M14 21v-2" />
                            <path d="M10 21v-2" />
                        </svg>
                        <h5>Cavi e quadristica precablata</h5>
                        <ul>
                            <li>Cavi preconnettorizzati</li>
                            <li>Quadristica precablata</li>
                            <li>Testata e pronta all'uso</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</section>