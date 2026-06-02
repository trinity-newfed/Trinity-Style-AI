            <?php if(!empty($user['img']) || $user['img'] != null): ?>
                <img src="../<?=$user['img']?>" class="w-[40px] h-[40px] object-scale-down rounded-full border border-solid border-black-400 cursor-pointer" onclick="window.location.href='user.php?#profile'" alt="">
            <?php else: ?>
                <img class="w-[40px] h-[40px] object-scale-down rounded-full border border-solid border-black-400 cursor-pointer" src="../Pictures/Banners/BA.webp" onclick="window.location.href='user.php?#profile'" alt="">
            <?php endif; ?>