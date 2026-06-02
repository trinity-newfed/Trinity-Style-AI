                        <?php foreach($baseProduct as $p):?>
                        <div class="item" data-name="<?=$p['product_name']?>">
                            <div class="item-Img">
                                <img src="../<?=$p['product_img']?>" alt="" onclick="window.location.href='detail.php?id=<?=$p['id']?>'">
                            </div>

                            <div>
                                <h4 onclick="window.location.href='detail.php?id=<?=$p['id']?>'"><?=$p['product_name']?></h4>
                                <span>$<?=$p['product_price']?></span>
                            </div>
                        </div>
                        
                    <?php endforeach; ?> 