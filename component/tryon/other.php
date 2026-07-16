<?php if (!empty($otherQuery)): ?>
    <?php foreach ($otherQuery as $row): ?>
        <div data-id="<?= $row['id'] ?>" data-color="<?= $row['color_display'] ?>"
            class="otherProducts cursor-pointer bg-zinc-900/50 p-2 rounded-xl border border-transparent cursor-pointer hover:border-zinc-800 transition flex flex-col gap-2">
            <div class="aspect-square rounded-lg overflow-hidden bg-zinc-950 opacity-60">
                <img src="../<?= $row['product_img'] ?>" alt="Product" class="w-full h-full object-cover">
            </div>
            <div class="text-[11px] truncate text-zinc-500"><?= $row['product_name'] ?></div>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <?php foreach ($alternativeQuery as $row): ?>
        <div data-id="<?= $row['id'] ?>" data-color="<?= $row['color_display'] ?>"
            class="otherProducts cursor-pointer bg-zinc-900/50 p-2 rounded-xl border border-transparent cursor-pointer hover:border-zinc-800 transition flex flex-col gap-2">
            <div class="aspect-square rounded-lg overflow-hidden bg-zinc-950 opacity-60">
                <img src="../<?= $row['product_img'] ?>" alt="Product" class="w-full h-full object-cover">
            </div>
            <div class="text-[11px] truncate text-zinc-500">
                <?= $row['product_name'] ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>