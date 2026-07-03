<?php foreach ($baseProduct as $item):
    usort($baseProduct, function ($a, $b) {
        return $b['id'] <=> $a['id'];
    });
?>

    <?php if ($item['product_name'] !== 'Summer Shirt' && $item['product_name'] !== 'Winter Coat')
        continue; ?>
    <div class="flex justify-between items-center py-8 group cursor-pointer reveal-target">
        <div class="flex items-center space-x-8 md:space-x-16">
            <div class="w-14 h-16 overflow-hidden bg-neutral-100 hidden md:block">
                <img src="../<?= $item['product_img'] ?>" alt="Item Preview"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            </div>
            <span class="text-[10px] font-mono text-neutral-400">01 /</span>
            <h4
                class="text-sm md:text-lg font-light tracking-wide uppercase transition-all duration-300 group-hover:translate-x-3">
                <?= $item['product_name'] ?>
            </h4>
        </div>
        <a href="search.php?content=<?=$item['product_name']?>"
            class="text-[10px] tracking-widest text-neutral-400 opacity-0 group-hover:opacity-100 transition-opacity">Explore
            &rarr;</a>
    </div>
<?php endforeach; ?>