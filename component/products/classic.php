<?php
$count = 0;
foreach ($baseProduct as $item):
    $count++;
    ?>
    <?php if ($item['product_category'] == "collections")
        continue; ?>
    <div class="products-child group cursor-pointer product transition-all duration-500" <?php
    foreach ($product_variant as $variant):
        if ($variant['product_id'] == $item['id']):
            ?> data-id="<?= $item['id'] ?>"
                data-img="../<?= $item['product_img'] ?>" data-name="<?= $item['product_name'] ?>"
                data-price="<?= $item['product_price'] ?>" data-color="<?=  $item['color_display'] ?>"
                data-category="<?= $item['product_category'] ?>" <?php
        endif;
    endforeach;
    ?>>

        <div class="text-[10px] text-gray-400 mb-1"><?= $count ?></div>
        <div class="relative bg-[#F3F3F3] aspect-[3/4] mb-3">
            <img src="../<?= $item['product_img'] ?>" class="w-full h-full object-cover" alt="">
        </div>

        <h3 class="text-[11px] uppercase tracking-wider"><?= $item['product_name'] ?></h3>
        <p class="text-[11px] text-gray-500 mt-0.5">$ <?= $item['product_price'] ?></p>

        <?php foreach ($product_variant as $variant):
            ?>
            <?php if ($variant['product_id'] == $item['id']):
                $activeClass = ($variant['product_color'] == $item['color_display']) ? 'active' : '';
                ?>
                <div class="variants <?= $activeClass ?>" data-id="<?= $variant['product_id'] ?>"
                    data-variant="<?= $variant['product_color'] ?>" data-img="../<?= $variant['variant_img'] ?>"
                    data-name="<?= $variant['product_color'] ?> <?= $variant['product_name'] ?>"
                    data-price="<?= $variant['product_price'] ?>" data-category="<?= $variant['product_category'] ?>"
                    data-color="<?= $variant['product_color'] ?>" data-stock="<?= $variant['product_stock'] ?>">
                </div>
            <?php endif; ?>
            <?php
        endforeach;
        ?>
    </div>
<?php endforeach; ?>