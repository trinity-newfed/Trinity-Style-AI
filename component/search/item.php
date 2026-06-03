    <?php if(count($name) > 0): ?>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-2 sm:gap-x-5 p-4">
        <?php foreach($name as $item): ?>
            <div class="flex flex-col cursor-pointer" onclick="window.location.href='detail.php?id=<?=$item['id']?>&color=white'">
                <div class="h-[80%] flex justify-center items-end">
                    <img class="w-full h-full object-cover justify-center items-end bg-[#F3F3F3]" src="../<?=$item['product_img']?>" alt="">
                </div>
                
                <h3 class="text-xs uppercase tracking-wider text-gray-900"><?=$item['product_name']?></h3>
                <p class="text-xs text-gray-600 mt-1">$<?=$item['product_price']?></p>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-2 sm:gap-x-5 p-4">
        <?php foreach($color as $item): ?>
            <div class="flex flex-col cursor-pointer" onclick="window.location.href='detail.php?id=<?=$item['id']?>&color=<?=$item['product_color']?>'">
                <div class="h-[80%] flex justify-center items-end">
                    <img class="w-full h-full object-cover justify-center items-end bg-[#F3F3F3]" src="../<?=$item['product_img']?>" alt="">
                </div>
                
                <h3 class="text-xs uppercase tracking-wider text-gray-900"><?=$item['product_name']?></h3>
                <p class="text-xs text-gray-600 mt-1">$<?=$item['product_price']?></p>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>