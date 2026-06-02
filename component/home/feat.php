<?php if(count($bestSeller) >= 5): ?>

    <div class="flex justify-between items-center mb-8">
        <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">BEST SELLER</h2>
    </div>

    <div class="flex overflow-x-auto overflow-y-hidden gap-x-5 max-w-[100%] scrollbar-hide hide products animate-on-scroll">
        <?php foreach($bestSeller as $item): ?>
        

            <div class='products-child group cursor-pointer w-[calc((100%-80px)/5)] opacity-0 shrink-0 min-w-[160px] product transition-all duration-500'>
                <div class="relative bg-[rgb(233,233,233)] aspect-[3/4] mb-3">
                    <img class="w-full h-full object-cover" src='../<?=$item['product_img']?>'/>
                </div>

                <h3 class="text-[11px] uppercase tracking-wider"><?=$item['product_name']?></h3>
                <p class="text-[11px] text-gray-500 mt-0.5">$<?=$item['product_price']?></p>
            </div>
        <?php endforeach; ?>
    </div>


<?php 
    else: 
    $reversedProducts = array_reverse($baseProduct);
    $latestProducts = array_slice($reversedProducts, 0, 5);
?>
    
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">NEW ARRIVAL</h2>
    </div>

    <div class="flex overflow-x-auto overflow-y-hidden gap-x-5 max-w-[100%] scrollbar-hide hide products animate-on-scroll">
        <?php foreach($latestProducts as $item): ?>
        

            <div class='products-child group cursor-pointer w-[calc((100%-80px)/5)] opacity-0 shrink-0 min-w-[160px] product transition-all duration-500'>
                <div class="relative bg-[rgb(233,233,233)] aspect-[3/4] mb-3">
                    <img class="w-full h-full object-cover" src='../<?=$item['product_img']?>'/>
                </div>

                <h3 class="text-[11px] uppercase tracking-wider"><?=$item['product_name']?></h3>
                <p class="text-[11px] text-gray-500 mt-0.5">$<?=$item['product_price']?></p>
            </div>
        <?php endforeach; ?>
    </div>
    

<?php endif; ?>
