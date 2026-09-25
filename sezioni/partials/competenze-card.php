<div class="card competenze-card w-100 w-lg-55 p-4 text-center rounded-0 d-flex flex-column gap-3 gap-lg-1">
    <?= $icon_svg ?>
    <h6 class="m-0"><?= htmlspecialchars($title) ?></h6>
    <div class="text-primary">
        <hr>
    </div>
    <ul class="list-group border-0 rounded-0 text-start fs-8 flex-grow-1 m-0">
        <?php foreach ($items as $item): ?>
            <li class="list-group-item border-0 px-0"><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
