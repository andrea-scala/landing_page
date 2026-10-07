<div class="card h-100 border-1 px-5 py-5 w-100 lavoro-card">
    <div class="img-container lavoro-card-logo">
        <img src="./assets/img/<?= htmlspecialchars($img) ?>" class="card-img-top img-fluid"
            alt="<?= htmlspecialchars($title) ?>">
    </div>
    <div class="text-primary px-2">
        <hr>
    </div>
    <div class="card-body flex-grow-1 d-flex flex-column justify-content-between gap-3 text-start p-0">
        <p class="card-text"><?= htmlspecialchars($description) ?></p>
        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"
            class="text-primary fs-6  flex-basis-0">Vai al sito</a>
    </div>
</div>