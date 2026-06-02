        <?php foreach($product_variant as $variant): ?>
            <?php 
              if($variant['product_id'] != $product['id']) continue;
            ?>
              <div class="items products-child group cursor-pointer w-[calc((100%-80px)/5)] shrink-0 min-w-[160px] product transition-all duration-500">
                <div class="items-left bg-[#F3F3F3]">
                    <img class="img i1" src="../<?=$variant['variant_img']?>" alt="">
                    <img class="img i2" src="../<?=$variant['variant_img1']?>" alt="">
                </div>
                <div class="items-right">
                    <h5 class="text-[11px] uppercase tracking-wider"><?=ucfirst($variant['product_color'])?> <?=ucfirst($variant['product_name'])?></h5>
                    <span class="text-[11px] text-gray-500 mt-0.5">$<?=$variant['product_price']?></span>
                </div>
            </div>
        <?php endforeach; ?>           