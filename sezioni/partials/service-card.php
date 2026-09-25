<div class="card service-card w-100 w-md-80 h-100 py-5 px-5 text-center rounded-2 d-flex flex-column gap-3 gap-md-1">
    <?= $icon_svg ?>
    <h5 class="m-0"><?= htmlspecialchars($title) ?></h5>
    <div class="text-primary">
        <hr>
    </div>
    <ul class="list-group border-0 rounded-0 text-start fs-6 flex-grow-1 m-0">
        <?php foreach ($items as $item): ?>
            <li class="list-group-item border-0 px-0"><?= htmlspecialchars($item) ?></li>
        <?php endforeach; ?>
    </ul>
</div>