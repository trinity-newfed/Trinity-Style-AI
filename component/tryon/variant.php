<div class="flex gap-2">
    <?php
        $active = "";
        foreach ($variantQuery as $val): 
        $val['color'] == $productColor ? $active = "activeColor" : $active = "border-zinc-800";
    ?>
        <button data-color="<?=$val['color']?>" data-category="<?=$val['product_category']?>" data-id="<?=$val['product_id']?>" data-stock="<?=$val['product_stock']?>" data-path="../<?=$val['variant_img']?>"
            class="<?=$active?> variantBtn colors label w-10 h-10 rounded-lg border text-xs flex items-center justify-center text-zinc-400 hover:border-zinc-600"><?=$val['color']?></button>
        </button>


    <?php endforeach; ?>
</div>