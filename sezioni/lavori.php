<section class="py-4 bg-white macro-section" id="lavori">
    <div class="container-fluid text-start section-padding-md px-3 d-flex flex-column h-100">
        <div class="section-divider-icon text-center"><i class="bi bi-box-arrow-up-right"></i></div>
        <h2 class="text-center section-title mb-5">I nostri lavori</h2>
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
        <div class="row g-3 mt-md-0 px-0 justify-content-around flex-grow-1">
            <?php foreach ($lavori as $l): ?>
                <div class="col-md-4 d-flex flex-column">
                    <?php render_lavoro($l); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>