<?php if (!empty($user['img']) || $user['img'] != null): ?>
    <label for="uploadImg">
        <input type="file" name="img" class="absolute" hidden id="uploadImg">
        <img src="../<?= $user['img'] ?>"
            class="w-[60px] h-[60px] object-scale-down rounded-full border border-solid border-black-400 cursor-pointer">
    </label>
<?php else: ?>
    <label for="uploadImg">
        <input type="file" name="img" class="absolute" hidden id="uploadImg">
        <img src="../Pictures/Banners/BA.webp"
            class="w-[60px] h-[60px] object-scale-down rounded-full border border-solid border-black-400 cursor-pointer">
    </label>
<?php endif; ?>