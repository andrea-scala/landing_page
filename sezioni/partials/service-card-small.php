<div class="card service-card w-100 h-100 py-5 px-5 text-center rounded-2 d-flex flex-column  gap-3">
    <?= $icon_svg ?>
    <h6 class="m-0"><?= htmlspecialchars($title) ?></h6>
    <div class="text-primary">
        <hr class="hr-sm">
    </div>
    <ul class="list-group list-group-sm border-0 rounded-0 text-start fs-7 flex-grow-1 m-0">
        <?php foreach ($items as $item): ?>
            <li class="list-group-item border-0 px-0"><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
</div>