<?php if(empty($product)): ?>
    <div class="flex flex-col items-center justify-center p-12 text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
        <span class="text-base font-medium">Nothing in product table</span>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php foreach($product as $p): ?>
            <?php $isDeleted = ($p['product_is_delete'] == 1); ?>
            
            <div class="items product group max-h-[fit-content] relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden <?= $isDeleted ? 'opacity-75 grayscale-[40%]' : '' ?>">
                
                <?php if($isDeleted): ?>
                    <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
                        <span class="px-4 py-1.5 bg-red-600/90 text-white font-bold text-lg tracking-wider uppercase rounded-lg shadow-lg transform rotate-[-12deg] backdrop-blur-sm">
                            Deleted
                        </span>
                    </div>
                <?php endif; ?>

                <div class="relative w-full h-48 bg-gray-50 overflow-hidden">
                    <img src="../<?= htmlspecialchars($p['product_img']) ?>" alt="<?= htmlspecialchars($p['product_name']) ?>" class="w-full h-full object-cover group-hover:cursor-pointer transition-transform duration-500">
                    
                    <div class="absolute top-3 right-3">
                        <?php if($p['product_stock'] < 5): ?>
                            <span class="px-2.5 py-1 text-xs font-semibold bg-red-50 text-red-600 border border-red-200 rounded-full shadow-sm">
                                Low Stock: <?= $p['product_stock'] ?>
                            </span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-full shadow-sm">
                                Stock: <?= $p['product_stock'] ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-grow justify-between">
                    <h3 class="font-semibold text-gray-800 text-base mb-4 line-clamp-2">
                        <?= htmlspecialchars($p['product_name']) ?>
                    </h3>

                    <div class="flex items-center gap-2 pt-3 border-t border-gray-100">
                        <?php if($isDeleted): ?>
                            <form action="restore_item_admin.php" method="post" class="flex-1">
                                <input type="submit" id="p-restore-<?= $p['id'] ?>" class="hidden">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <label for="p-restore-<?= $p['id'] ?>" class="w-full py-2 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-medium rounded-xl text-center cursor-pointer transition-colors block">
                                    Restore
                                </label>
                            </form>
                        <?php else: ?>
                            <form action="delete_item_admin.php" method="post" class="flex-1">
                                <input type="submit" id="p-delete-<?= $p['id'] ?>" class="hidden">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <label for="p-delete-<?= $p['id'] ?>" class="w-full py-2 px-3 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-xl text-center cursor-pointer transition-colors block">
                                    Delete
                                </label>
                            </form>
                        <?php endif; ?>

                        <span onclick="window.location.href='update.php?id=<?= $p['id'] ?>'" class="flex-1 py-2 px-3 bg-gray-50 hover:bg-gray-100 text-gray-700 text-xs font-medium rounded-xl text-center cursor-pointer transition-colors select-none">
                            Update
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>