<div class="card lavoro-card w-100 h-100 rounded-0 d-flex flex-column overflow-hidden">
    <img class="w-100" style="height:140px;object-fit:cover"
         src="./assets/img/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($title) ?>">
    <div class="p-3 d-flex flex-column align-items-start flex-grow-1">
        <h5 class="fs-6 fw-bold mb-1"><?= htmlspecialchars($title) ?></h5>
        <p class="fs-7 text-secondary flex-grow-1 mb-2"><?= htmlspecialchars($description) ?></p>
        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener"
           class="fs-7 text-primary text-decoration-none">
            Vai al sito <i class="bi bi-box-arrow-up-right ms-1"></i>
        </a>
    </div>
</div>

