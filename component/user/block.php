            <?php if(!empty($groupedOrders)): ?>
            <?php foreach($groupedOrders as $order_id => $order): 
                $info = $order['order_info']; 
                $state = strtolower($info['order_state']);
                $time = date('j-n', strtotime($info['created_at']));
                $count++;
            ?>
            <div class="order-block bg-[#F9F9F9] p-5 justify-around">
                <button class="absolute right-[20px] z-[100] hidden cursor-pointer reBuy-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#A96424">
                        <path d="M263.33-429.62q-21.04 0-35.61-14.78-14.56-14.78-14.56-35.81 0-21.04 14.78-35.61 14.77-14.56 35.81-14.56 21.04 0 35.61 14.78 14.56 14.78 14.56 35.81 0 21.04-14.78 35.61-14.78 14.56-35.81 14.56Zm216.46 0q-21.04 0-35.61-14.78-14.56-14.78-14.56-35.81 0-21.04 14.78-35.61 14.78-14.56 35.81-14.56 21.04 0 35.61 14.78 14.56 14.78 14.56 35.81 0 21.04-14.78 35.61-14.78 14.56-35.81 14.56Zm216.46 0q-21.04 0-35.61-14.78-14.56-14.78-14.56-35.81 0-21.04 14.78-35.61 14.78-14.56 35.81-14.56 21.04 0 35.61 14.78 14.56 14.78 14.56 35.81 0 21.04-14.78 35.61-14.77 14.56-35.81 14.56Z"/>
                    </svg>
                </button>
                
                <div class="order-state max-w-[350px] m-auto border-1 rounded-[5px] w-[100%] h-fit py-4 bg-[whitesmoke] opacity-[0.9] justify-start px-2 flex flex-col">
                <div>
                    <span class="state font-medium text-sm"><?=ucfirst($info['order_state'])?></span>
                </div>
                <span class="text-xs"><?=$time?></span>
            </div>
            
                <div class="order-img mt-[20px]" onclick="window.location.href='orderItem.php?id=<?=$info['id']?>'">
                    <img class="bg-[whitesmoke] border-1 opacity-[0.9]" src="../<?= htmlspecialchars($info['img'])?>" alt="">
                </div>

                

                    <div class="order-info">
                        <div class="order-img-info w-[100%] h-[80%]">
                            <div>
                                <span class="font-medium"><?=$order['total_items']?> item</span>
                                <h3 class="order-name font-light text-xs">#<?= htmlspecialchars($info['order_name']) ?></h3>
                            </div>

                            <span class="font-medium text-lg">$<?=number_format($info['order_final_price'])?></span>
                        </div>
                    </div>


                    <form action="../Database/reOrder.php" class="w-[100%] flex justify-center h-[60px]" method="POST" id="blockForm">
                        <input type="hidden" name="order_id" value="<?=$info['id']?>">
                        <button class="re-order w-[100%] h-[100%] border-black-100 border border-solid rounded-[5px]" type="submit">Re-Buy</button>
                    </form>
                </div>  
            <?php endforeach; ?>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-20 px-5 text-center">
                <svg class="text-neutral-400 mb-5 transition-colors duration-300 hover:text-black" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
    
                <p class="font-sans text-sm font-light tracking-wider text-neutral-600 mb-8">You haven't placed any orders yet.</p>
    
                <a href="products.php" class="inline-block bg-black text-white text-xs font-medium tracking-widest uppercase px-9 py-3.5 border border-black transition-all duration-300 ease-in-out hover:bg-white hover:text-black">Start Shopping</a>
            </div>
            <?php endif; ?>