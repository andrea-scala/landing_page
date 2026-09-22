<div class="card h-100 p-4 text-center">
    <img class="img-fluid mb-3" src="./assets/img/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($title) ?>">
    <h5><?= htmlspecialchars($title) ?></h5>
    <p><?= htmlspecialchars($description) ?></p>
    <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener" class="btn btn-outline-primary mt-2">
        Vai al sito <i class="bi bi-box-arrow-up-right ms-1"></i>
    </a>
</div>