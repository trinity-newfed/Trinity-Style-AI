        <?php if (!empty($product['product_img'])): ?>
          <div class="relative">
            <img id="bigImg" src="../<?= $product['product_img'] ?>">
            <span id="stock" class="stock absolute z-[2] top-4 left-4 bg-black/70 backdrop-blur-md text-white text-xs font-semibold uppercase tracking-wider px-3 py-1.5 rounded-md hidden">
              Out of Stock
            </span>
          </div>
        <?php endif; ?>

        <div class="thumb-list">
          <?php
            $index = 0;
            $thumbs = array_filter([$product['product_img1'] ?? null, $product['product_img2'] ?? null]);
            foreach($thumbs as $img)
              {
                $index = $index + 1;
                $formattedNum = str_pad($index, 2, '0', STR_PAD_LEFT);
                echo '<img class="smallImg img-' . $index . '" src="../' . $img . '">';
              }
          ?>