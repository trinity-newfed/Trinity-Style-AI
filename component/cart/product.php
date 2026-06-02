<?php 
                    foreach($data as $item): 
                    $active = ($item['product_is_delete'] == 0 && $item['product_state'] == "active") ? 1 : 0;
                ?>
                                        <div class="cartItem relative" data-id="<?=$item['cart_id']?>" data-color=<?=$item['variant_color']?> data-active="<?=$active?>">

                        <div class="item-left">
                            <div class="item-img-container relative">
                                <span class="notice absolute w-[100%] text-red-700 hidden text-center z-[100] top-[50%] left-[50%] translate-y-[-50%] translate-x-[-50%]">OUT OF STOCK</span>
                                <img src="../<?=$item['variant_img']?>" alt="">
                            </div>

                            <div class="item-info-container">
                                <span class="item-name"><?=$item['product_name']?></span>
                                <span class="items-price-container" data-price="<?=$item['product_price']?>"></span>
                                
                                <!--Item selection-->
                                <div class="item-option">
                                    <span class="hidden">COLOR</span>
                                    
                                    <div class="flex gap-[5px] w-fit md:w-[100%]">
                                        <div class="colorContainer">
                                            <div class="color" style="background: <?=$item['variant_color']?>"></div>

                                            <select name="cart_color" class="cartColor" data-id="<?=$item['cart_id']?>">
                                                <option class="active" value="<?=$item['variant_color']?>" data-img="../<?=$item['variant_img']?>" data-stock=<?=$item['variant_stock']?>><?=ucfirst($item['variant_color'])?></option>

                                                <?php foreach($product as $variant): ?>
                                                    <?php 
                                                        if($variant['variant_id'] != $item['product_id']) continue; 
                                                        if($variant['product_color'] == $item['variant_color']) continue;
                                                    ?>
                                                        <option value="<?=$variant['product_color']?>" 
                                                            data-img="../<?=$variant['product_img']?>"
                                                            data-stock=<?=$variant['variant_stock']?>>
                                                            <?=ucfirst($variant['product_color'])?>
                                                        </option>
                                                <?php endforeach; ?>
                                            
                                            </select>
                                        </div>

                                        <div class="sizeContainer">
                                            <select name="cart_size" class="cartSize" data-id="<?=$item['cart_id']?>">
                                                <?php $sizes = ["S", "M", "L", "XL"];
                                                  $currentSize = $item['cart_size'];
                                                  foreach($sizes as $size):
                                                  $selected = ($size === $currentSize) ? 'selected' : '';

                                                ?>

                                                <option value="<?=$size?>" <?=$selected?>><?=$size?></option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!--Item quantity-->
                                <div id="items-quantity-container">
                                    <span>QUANTITY</span>
                                    <div>
                                        <button style="cursor: pointer;" type="button" id="minus-input" class="operation-button" data-id="<?=$item['cart_id']?>" data-action="minus">
                                            <svg class="icon operate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32z"/></svg>
                                        </button>
                                    
                                        <span class="item-quantity" style="font-weight: 550;"><?=$item['quantity']?></span>

                                        <button style="cursor: pointer;" type="button" id="plus-input" class="operation-button" data-id="<?=$item['cart_id']?>" data-action="plus">
                                            <svg class="icon operate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 160-160 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32l0-160 160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0 0-160z"/></svg>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="item-right">
                            <div class="deleteItem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                    <path d="M55.1 73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L147.2 256 9.9 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192.5 301.3 329.9 438.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.8 256 375.1 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192.5 210.7 55.1 73.4z"/>
                                </svg>
                            </div>

                            <div class="items-total-container"></div>
                        </div>
                    </div>
                <?php endforeach; ?>