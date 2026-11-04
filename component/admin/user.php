                    <?php if(empty($userdata)): ?>
                        <span>Nothing in user table</span>
                    <?php else: ?>
                        <?php foreach($userdata as $u): ?>
                            <div class="items user cursor-pointer">
                                <?php if(empty($u['img'])): ?>
                                    <img style="width: 60px; height: 60px; border-radius: 50%;" src="../Pictures/Banners/BA.webp" alt="">
                                <?php else: ?>
                                    <img style="width: 60px; height: 60px; border-radius: 50%; background: #b4b4b4;" src="../<?=$u['img']?>" alt="">
                                <?php endif; ?>
                                <span><?=$u['email']?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>