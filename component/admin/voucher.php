<?php if(empty($voucher)): ?>
    <div class="flex flex-col items-center justify-center p-12 text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
        <span class="text-base font-medium">Nothing in voucher table</span>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($voucher as $v):
            $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
            $tier = (int)$v['voucher_min_tier'];
            $tierName = $tierNames[$tier - 1] ?? 'All';

            $badgeColor = match($tier) {
                2 => 'bg-slate-100 text-slate-700 border-slate-200',
                3 => 'bg-amber-50 text-amber-700 border-amber-200',
                4 => 'bg-cyan-50 text-cyan-700 border-cyan-200',
                default => 'bg-gray-100 text-gray-700 border-gray-200',
            };
        ?>
            <div class="items voucher max-h-[fit-content] relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 p-5 flex flex-col justify-between overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-2 bg-gradient-to-b from-indigo-800"></div>

                <div>
                    <div class="flex items-center justify-between mb-3 pl-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full border <?= $badgeColor ?>">
                            <?= $tierName ?>
                        </span>
                        <span class="text-xs font-medium uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
                            <?= htmlspecialchars($v['voucher_type']) ?>
                        </span>
                    </div>

                    <div class="pl-2 mb-4">
                        <div class="text-2xl font-bold text-gray-900">
                            $<?= htmlspecialchars($v['voucher_discount']) ?> <span class="text-sm font-normal text-gray-500">OFF</span>
                        </div>
                    </div>

                    <div class="space-y-2 pl-2 text-sm text-gray-600 border-t border-gray-100 pt-3">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Max Discount:</span>
                            <span class="font-medium text-gray-800">$<?= htmlspecialchars($v['voucher_max']) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Min Spend:</span>
                            <span class="font-medium text-gray-800">$<?= htmlspecialchars($v['voucher_condition']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>