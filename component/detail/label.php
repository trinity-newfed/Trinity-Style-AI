            <?php 
              foreach($product_variant as $variant):
              if($variant['product_id'] != $product['id']) continue; 
            ?>
            <label class="color bg-white text-black hover:border-black color text-xs md:text-sm uppercase border-solid border-black/20 border p-1 md:p-2 text-center cursor-pointer transition-all duration-300 hover:border-black"
              data-img="../<?=$variant['variant_img']?>"
              data-img1="../<?=$variant['variant_img1']?>"
              data-img2="../<?=$variant['variant_img2']?>"
              data-color="<?=$variant['product_color']?>"
              data-category="<?=$product['product_category']?>"
              data-price="<?=$variant['product_price']?>"
              data-stock="<?=$variant['product_stock']?>">
                <?=$variant['product_color']?>
            </label>
            <?php endforeach; ?>