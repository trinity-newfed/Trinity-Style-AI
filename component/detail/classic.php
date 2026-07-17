<?php
// 1. Lọc trước các biến thể thỏa mãn tất cả các điều kiện
$filtered_variants = array_filter($product_variant, function ($variant) use ($product) {
    return $variant['product_color'] != $product['product_color']
        && $variant['product_type'] == $product['product_type']
        && $variant['product_id'] != $product['id'];
});
?>

<?php if (!empty($filtered_variants)): ?>
    <?php foreach ($filtered_variants as $variant): ?>
        <div
            class="items classic products-child group cursor-pointer w-[calc((100%-80px)/5)] shrink-0 min-w-[160px] product transition-all duration-500">
            <div class="items-left bg-[#F3F3F3]">
                <img class="img i1 w-full h-full object-cover" src="../<?= $variant['variant_img'] ?>" alt="">
                <img class="img i2 w-full h-full object-cover" src="../<?= $variant['variant_img1'] ?>" alt="">
            </div>
            <div class="items-right">
                <h5 class="text-[11px] uppercase tracking-wider"><?= ucfirst($variant['product_color']) ?>
                    <?= ucfirst($variant['product_name']) ?>
                </h5>
                <span class="text-[11px] text-gray-500 mt-0.5">$<?= $variant['product_price'] ?></span>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <?php foreach ($product_variant as $variant): ?>
        <div
            class="items classic products-child group cursor-pointer w-[calc((100%-80px)/5)] shrink-0 min-w-[160px] product transition-all duration-500">
            <div class="items-left bg-[#F3F3F3]">
                <img class="img i1 w-full h-full object-cover" src="../<?= $variant['variant_img'] ?>" alt="">
                <img class="img i2 w-full h-full object-cover" src="../<?= $variant['variant_img1'] ?>" alt="">
            </div>
            <div class="items-right">
                <h5 class="text-[11px] uppercase tracking-wider"><?= ucfirst($variant['product_color']) ?>
                    <?= ucfirst($variant['product_name']) ?>
                </h5>
                <span class="text-[11px] text-gray-500 mt-0.5">$<?= $variant['product_price'] ?></span>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>