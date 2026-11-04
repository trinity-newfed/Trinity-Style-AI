<?php if(empty($order)): ?>
    <div class="flex flex-col items-center justify-center p-12 text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
        <svg class="w-12 h-12 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        <span class="text-base font-medium">Nothing in order table</span>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($order as $o): 
            $state = strtolower($o['order_state'] ?? '');
            $stateBadge = match(true) {
                str_contains($state, 'delivery')=> 'bg-blue-50 text-blue-700 border-blue-200',
                str_contains($state, 'success') => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                str_contains($state, 'cancel')=> 'bg-red-50 text-red-700 border-red-200',
                default => 'bg-gray-50 text-gray-700 border-gray-200',
            };
        ?>
            <div class="items order max-h-[fit-content] bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md cursor-pointer transition-all duration-300 p-4 flex flex-col justify-between group">
                
                <div class="flex items-center gap-4">
                    <div class="relative w-20 h-20 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                        <img src="../<?= !empty($o['img']) ? htmlspecialchars($o['img']) : 'assets/images/default-product.png' ?>" 
                             alt="<?= htmlspecialchars($o['order_name']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    <div class="flex flex-col flex-grow justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-[.9rem] text-gray-800 text-base line-clamp-1">
                                    #<?= htmlspecialchars($o['order_name']) ?>
                                </h3>
                                <span class="px-2 py-0.5 mx-1 text-xs font-semibold rounded-full border <?= $stateBadge ?>">
                                    <?= htmlspecialchars($o['order_state']) ?>
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="font-medium text-indigo-600">$<?= number_format($o['order_original_price'], 2) ?></span>
                                <span>•</span>
                                <span>Qty: <strong class="text-gray-700"><?= htmlspecialchars($o['order_quantity']) ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-50 space-y-1.5 text-xs text-gray-500">
                    <?php if(!empty($o['discount']) && $o['discount'] > 0): ?>
                        <div class="flex justify-between items-center text-emerald-600">
                            <span>Product Discount:</span>
                            <span>-$<?= number_format($o['discount'], 2) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-center">
                        <span>Delivery Fee:</span>
                        <span class="font-medium text-gray-700">$<?= number_format($o['order_delivery_fee'], 2) ?></span>
                    </div>

                    <?php if(!empty($o['ship_discount']) && $o['ship_discount'] > 0): ?>
                        <div class="flex justify-between items-center text-emerald-600">
                            <span>Shipping Discount:</span>
                            <span>-$<?= number_format($o['ship_discount'], 2) ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Final:</span>
                    <span class="text-sm font-bold text-gray-900">
                        $<?= number_format($o['order_final_price'], 2) ?>
                    </span>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>