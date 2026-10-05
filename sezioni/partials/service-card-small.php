<div class="card service-card w-100 w-lg-90 h-100 py-3 py-lg-5  px-3 px-lg-5 text-center rounded-2 d-flex flex-column gap-2 justify-content-evenly">
    <?= $icon_svg ?>
    <h6 class="m-0 fs-6 fs-lg-5"><?= htmlspecialchars($title) ?></h6>
    <div class="text-primary">
        <hr class="hr-sm">
    </div>
    <ul class="list-group-sm border-0 rounded-0 text-start fs-6 fs-lg-5 m-0 flex-grow-1">
        <?php foreach ($items as $item): ?>
            <li class="list-group-item border-0  py-1 py-lg-2"><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
