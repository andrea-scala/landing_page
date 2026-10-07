<div class="card service-card w-100 w-lg-80 h-100 py-4 py-lg-5 px-3 px-lg-5 text-center d-flex flex-column gap-0 gap-lg-1 justify-content-evenly border-1">
    <?= $icon_svg ?>
    <h5 class="m-0 fs-6 fs-lg-5"><?= htmlspecialchars($title) ?></h5>
    <div class="text-primary">
        <hr>
    </div>
    <ul class="list-group border-0 text-start fs-6 fs-lg-5 m-0 flex-grow-1">
        <?php foreach ($items as $item): ?>
            <li class="list-group-item border-0  py-1 py-lg-2"><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
</div>