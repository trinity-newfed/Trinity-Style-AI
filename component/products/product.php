            <?php foreach($baseProduct as $base): ?>
                <?php if($base['product_category'] !== "collections") continue; ?>
                <div class="group cursor-pointer collections-child">
                <div class="relative bg-[#F3F3F3] aspect-[3/4] mb-4 overflow-hidden">
                    <span class="absolute top-2 left-2 bg-white text-[9px] uppercase tracking-widest px-2 py-0.5 z-10">Limited</span>
                    <img src="../<?=$base['product_img']?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Product">
                </div>
                <h3 class="text-xs uppercase tracking-wider text-gray-900">TRINITY <?=$base['product_name']?></h3>
                <p class="text-xs text-gray-600 mt-1">$ <?=$base['product_price']?></p>
                </div>
            <?php endforeach; ?>

            <?php foreach($product as $key => $item): ?>
                <?php if($item['product_category'] !== "collections") continue; ?>
                                    <div class="group cursor-pointer collections-child"
                         data-id="<?=$item['id']?>" 
                         data-img="../<?=$item['product_img']?>"
                         data-name="<?=$item['product_color']?> <?=$item['product_name']?>"
                         data-price="<?=$item['product_price']?>"
                         data-category="<?=$item['product_category']?>"
                         data-color="<?=$item['product_color']?>"
                         data-stock="<?=$item['product_stock']?>">

                        <div class="bg-[#F3F3F3] aspect-[3/4] mb-4 overflow-hidden">
                            <img src="../<?=$item['product_img']?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Product">
                        </div>

                        <h3 class="text-xs uppercase tracking-wider text-gray-900"><?=$item['product_color']?> <?=$item['product_name']?></h3>
                        <p class="text-xs text-gray-600 mt-1">$<?=$item['product_price']?></p>

                        <?php foreach($product_variant as $variant): 
                        ?>
                            <?php if($variant['product_id'] == $item['id']): 
                                $activeClass = ($variant['product_color'] === $item['product_color']) ? 'active' : '';
                            ?>
                                <div class="variants <?=$activeClass?>" 
                                     data-id="<?=$variant['product_id']?>"
                                     data-variant="<?=$variant['product_color']?>"
                                     data-img="../<?=$variant['variant_img']?>" 
                                     data-name="<?=$variant['product_color']?> <?=$variant['product_name']?>"
                                     data-price="<?=$variant['product_price']?>"
                                     data-category="<?=$variant['product_category']?>"
                                     data-color="<?=$variant['product_color']?>"
                                     data-stock="<?=$variant['product_stock']?>">
                                </div>
                            <?php endif; ?>
                        <?php 
                          endforeach;
                        ?>
                    </div>
            <?php endforeach; ?>