<div>
    <span class="text-[10px] uppercase tracking-widest text-zinc-500 font-semibold block mb-1">
        <?php if($productQuery['product_category'] == 'collections'): 
            if($productQuery['product_name'] == "Summer Shirt"):    
        ?>
            Summer Collection
            <?php elseif($productQuery['product_name'] == "Winter Coat"): ?>
            Winter Collection
        <?php endif; endif;?>
    </span>
    <h1 class="text-xl font-light tracking-wide text-white"><?=$productQuery['product_name']?></h1>
    <div class="mt-2 flex items-baseline gap-3">
        <span class="text-lg font-medium text-zinc-200">$<?=$productQuery['product_price']?></span>
    </div>
</div>