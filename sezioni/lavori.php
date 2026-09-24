<section class="py-4 bg-white macro-section" id="lavori">
    <div class="container-fluid px-4 px-md-0 text-start section-padding-md py-4">
        <span class="badge text-bg-primary-inverso px-0 mt-0 mb-3 text-uppercase align-self-start">Lavori</span>
        <h1 class="mb-3 fw-bold w-md-65">I nostri lavori</h1>
        <p class="lead fw-normal mb-4 w-md-45 fs-6 text-justify manrope-paragrafi-regular">Le nostre attività
            raccontate nel dettaglio, ognuna sul proprio portale.</p>

        <?php
        $lavori = [
            [
                'img' => 'lavoro-1.png',
                'title' => 'Sito 1',
                'description' => '...',
                'url' => 'https://...',
            ],
            [
                'img' => 'lavoro-2.png',
                'title' => 'Sito 2',
                'description' => '...',
                'url' => 'https://...',
            ],
            [
                'img' => 'lavoro-3.png',
                'title' => 'Sito 3',
                'description' => '...',
                'url' => 'https://...',
            ],
        ];

        function render_lavoro(array $l): void
        {
            $img = $l['img'];
            $title = $l['title'];
            $description = $l['description'];
            $url = $l['url'];
            include __DIR__ . '/partials/lavoro-card.php';
        }
        ?>

        <div class="row g-3">
            <?php foreach ($lavori as $l): ?>
                <div class="col-12 col-md-4 d-flex flex-column">
                    <?php render_lavoro($l); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>