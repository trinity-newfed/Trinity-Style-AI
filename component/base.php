                    <?php 
                    $host = "localhost";
                    $user = "root";
                    $password = "";
                    $dbname = "TF_Database";

                    $conn = new mysqli($host, $user, $password, $dbname);

                    $keyword = $_GET['keyword'] ?? '';
                    $keyword = str_replace(' ', '%', $keyword);
                    $searchItem = "%" . $keyword . "%";

                    $name = $conn->execute_query("SELECT * FROM products WHERE product_name LIKE ?",[$searchItem])
                                 ->fetch_all(MYSQLI_ASSOC);

                    $color = $conn->execute_query("SELECT products.id AS id, products.product_name,

                                                   product_variant.product_id, product_variant.product_color,
                                                   product_variant.product_img, product_variant.product_price

                                                   FROM products
                                                   JOIN product_variant 
                                                   ON products.id = product_variant.product_id
                                                   WHERE product_color LIKE ?",[$searchItem])
                                 ->fetch_all(MYSQLI_ASSOC);
                    ?>
                    
                    
                    <?php if(count($name) > 0): ?>
                        <?php foreach($name as $item): ?>
                        
                        <div class="item" data-name="<?=$item['product_name']?>">
                            <div class="item-Img">
                                <img src="../<?=$item['product_img']?>" alt="" onclick="window.location.href='detail.php?id=<?=$item['id']?>&color=white'">
                            </div>

                            <div>
                                <h4 onclick="window.location.href='detail.php?id=<?=$item['id']?>&color=white'"><?=$item['product_name']?></h4>
                                <span>$<?=$item['product_price']?></span>
                            </div>
                        </div>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <?php foreach($color as $item): ?>
                        
                        <div class="item" data-name="<?=$item['product_name']?>">
                            <div class="item-Img">
                                <img src="../<?=$item['product_img']?>" alt="" onclick="window.location.href='detail.php?id=<?=$item['id']?>&color=<?=$item['product_color']?>'">
                            </div>

                            <div>
                                <h4 onclick="window.location.href='detail.php?id=<?=$item['id']?>&color=white'"><?=$item['product_name']?></h4>
                                <span>$<?=$item['product_price']?></span>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    <?php endif ?> 