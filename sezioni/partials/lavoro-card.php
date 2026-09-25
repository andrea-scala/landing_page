<div class="card rounded-0 h-100 border-1 rounded-2 p-4 py-4 w-100 w-md-70">
    <div class="img-container d-flex flex-column justify-content-center" style="min-height: 50px; height: 50px;">
        <img src="./assets/img/<?= htmlspecialchars($img) ?>" class="card-img-top"
            alt="<?= htmlspecialchars($title) ?>">
    </div>
    <div class="text-primary px-2">
        <hr>
    </div>
    <div class="card-body flex-grow-1 d-flex flex-column justify-content-between gap-3 text-start p-0">
        <!-- <h5 class="card-title"><?= htmlspecialchars($title) ?></h5> -->
        <p class="card-text"><?= htmlspecialchars($description) ?></p>
        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"
            class="btn btn-primary btn-lg rounded-pill fs-6  flex-basis-0">Vai al sito</a>
    </div>
</div>