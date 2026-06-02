<div class="voucher-list" id="all" style="display: none;">
    <?php foreach($voucher as $v): ?>
        <?php if($users['user_tier'] >= $v['voucher_min_tier'] && $v['voucher_type'] == "order"): ?>
            <?php if(in_array($v['id'], $used_ids)) continue; ?>
            <div class="voucher-card">
                <div class="voucher-brand">
                    <h4>TRINITY</h4>
                    <?php
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                    ?>
                        <span class="tier tier-<?=$tier?>"><?=$tierNames[$tier - 1] ?? "Unknown"?></span>
                </div>
                <div class="voucher-discount"><?=$v['voucher_discount']?>% OFF</div>
                <div class="voucher-condition">On orders over $<?=$v['voucher_condition']?></div>
                <div class="voucher-condition">Max discount $<?=$v['voucher_max']?></div>
                <div class="voucher-footer">
                <?php if(in_array($v['id'], $claimed)): ?>
                    <button disabled class="claim">✔ Claimed</button>
                <?php elseif((int)$users['user_tier'] >= (int)$v['voucher_min_tier']): ?>
                    <form action="../Database/user_voucher.php" method="POST">
                    <input type="hidden" name="voucher_id" value="<?=$v['id']?>">
                    <button type="submit" class="unclaim">Claim</button>
                    </form>
                <?php endif; ?>
                </div>
            </div>
        <?php elseif($users['user_tier'] >= $v['voucher_min_tier'] && $v['voucher_type'] == "shipping"): ?>
            <?php if(in_array($v['id'], $used_ids)) continue; ?>
            <div class="voucher-card">
                <div class="voucher-brand">
                    <h4>TRINITY</h4>
                    <?php
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                    ?>
                    <div style="display: flex; gap: 3px;">
                        <span class="tier shipping">
                            <svg class="icon" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                            Shipping
                        </span>
                        <span class="tier tier-<?=$tier?>"><?=$tierNames[$tier - 1] ?? "Unknown"?></span>
                    </div>
                </div>
                <?php if($v['voucher_discount'] == 25): ?>
                    <div class="voucher-discount">Free Ship</div>
                <?php else: ?>
                    <div class="voucher-discount">$<?=$v['voucher_discount']?> OFF</div>
                <?php endif; ?>
                <div class="voucher-condition">On orders over $<?=$v['voucher_condition']?></div>
                <div class="voucher-footer">
                <?php if(in_array($v['id'], $claimed)): ?>
                    <button disabled class="claim">✔ Claimed</button>
                <?php elseif((int)$users['user_tier'] >= (int)$v['voucher_min_tier']): ?>
                    <form action="../Database/user_voucher.php" method="POST">
                    <input type="hidden" name="voucher_id" value="<?=$v['id']?>">
                    <button type="submit" class="unclaim">Claim</button>
                    </form>
                <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<div class="voucher-list" id="available" style="display: none;">
    <?php foreach($voucher as $v): ?>
        <?php if($users['user_tier'] >= $v['voucher_min_tier'] && $v['voucher_type'] == "order"): ?>
            <?php if(!in_array($v['id'], $claimed)) continue; ?>
            <div class="voucher-card">
                <div class="voucher-brand">
                    <h4>TRINITY</h4>
                    <?php
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                    ?>
                        <span class="tier tier-<?=$tier?>"><?=$tierNames[$tier - 1] ?? "Unknown"?></span>
                </div>
                <div class="voucher-discount"><?=$v['voucher_discount']?>% OFF</div>
                <div class="voucher-condition">On orders over $<?=$v['voucher_condition']?></div>
                <div class="voucher-condition">Max discount $<?=$v['voucher_max']?></div>
                <div class="voucher-footer">
                <?php if(in_array($v['id'], $claimed)): ?>
                    <button disabled class="claim">✔ Claimed</button>
                <?php elseif((int)$users['user_tier'] >= (int)$v['voucher_min_tier']): ?>
                    <form action="../Database/user_voucher.php" method="POST">
                    <input type="hidden" name="voucher_id" value="<?=$v['id']?>">
                    <button type="submit" class="unclaim">Claim</button>
                    </form>
                <?php endif; ?>
                </div>
            </div>
        <?php elseif($users['user_tier'] >= $v['voucher_min_tier'] && $v['voucher_type'] == "shipping"): ?>
            <?php if(!in_array($v['id'], $claimed)) continue; ?>
            <div class="voucher-card">
                <div class="voucher-brand">
                    <h4>TRINITY</h4>
                    <?php
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                    ?>
                    <div style="display: flex; gap: 3px;">
                        <span class="tier shipping">
                            <svg class="icon" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                            Shipping
                        </span>
                        <span class="tier tier-<?=$tier?>"><?=$tierNames[$tier - 1] ?? "Unknown"?></span>
                    </div>
                </div>
                <?php if($v['voucher_discount'] == 25): ?>
                    <div class="voucher-discount">Free Ship</div>
                <?php else: ?>
                    <div class="voucher-discount">$<?=$v['voucher_discount']?> OFF</div>
                <?php endif; ?>
                <div class="voucher-condition">On orders over $<?=$v['voucher_condition']?></div>
                <div class="voucher-footer">
                <?php if(in_array($v['id'], $claimed)): ?>
                    <button disabled class="claim">✔ Claimed</button>
                <?php elseif((int)$users['user_tier'] >= (int)$v['voucher_min_tier']): ?>
                    <form action="../Database/user_voucher.php" method="POST">
                    <input type="hidden" name="voucher_id" value="<?=$v['id']?>">
                    <button type="submit" class="unclaim">Claim</button>
                    </form>
                <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<div class="voucher-list" id="expired" style="display: none;"></div>
<div class="voucher-list" id="used" style="display: none;">
    <?php foreach($voucher as $v): ?>
        <?php if(in_array($v['id'], $used_ids) && $v['voucher_type'] == "order"): ?>
            <div class="voucher-card">
                <div class="voucher-brand">
                    <h4>TRINITY</h4>
                    <?php
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                    ?>
                        <span class="tier tier-<?=$tier?>"><?=$tierNames[$tier - 1] ?? "Unknown"?></span>
                </div>
                <div class="voucher-discount"><?=$v['voucher_discount']?>% OFF</div>
                <div class="voucher-condition">On orders over $<?=$v['voucher_condition']?></div>
                <div class="voucher-condition">Max discount $<?=$v['voucher_max']?></div>
                <div class="voucher-footer">
                </div>
            </div>
        <?php elseif(in_array($v['id'], $used_ids) && $v['voucher_type'] == "shipping"): ?>
            <div class="voucher-card">
                <div class="voucher-brand">
                    <h4>TRINITY</h4>
                    <?php
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                    ?>
                    <div style="display: flex; gap: 3px;">
                        <span class="tier shipping">
                            <svg class="icon" fill="white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/></svg>
                            Shipping
                        </span>
                        <span class="tier tier-<?=$tier?>"><?=$tierNames[$tier - 1] ?? "Unknown"?></span>
                    </div>
                </div>
                <?php if($v['voucher_discount'] == 25): ?>
                    <div class="voucher-discount">Free Ship</div>
                <?php else: ?>
                    <div class="voucher-discount">$<?=$v['voucher_discount']?> OFF</div>
                <?php endif; ?>
                <div class="voucher-condition">On orders over $<?=$v['voucher_condition']?></div>
                <div class="voucher-footer">
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>