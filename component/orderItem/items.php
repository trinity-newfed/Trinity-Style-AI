        <?php 
          foreach($rows as $r): 
        ?>
        <div class="ItemRow p-2">
          <div class="ItemInfoContainer">
            <img src="../<?=$r['product_img']?>" alt="Product">
            <div class="ItemInfo">
              <p class="ItemName"><?=ucfirst($r['product_name'])?></p>
              <p class="text-[11px] opacity-[0.6] m-[3px 0 0]"><?=ucfirst($r['product_color'])?> / <?=$r['size']?></p>
            </div>
          </div>
          <div class="ItemPrice" data-price="<?=$r['price']?>"></div>
          <div class="ItemQuantity">x<?=$r['quantity']?></div>
        </div>
         
        <?php endforeach; ?>