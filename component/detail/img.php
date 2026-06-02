        <?php if (!empty($product['product_img'])): ?>
          <img id="bigImg" src="../<?= $product['product_img'] ?>">
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