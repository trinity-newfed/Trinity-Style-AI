<?php if(strtolower($content) != "collections" && strtolower($content) != "all" && strtolower($content) != "new"): ?>
    <?php if(count($name) > 1): ?>
        <?=count($name)?> results found for "<?=$content?>"
    <?php elseif(count($name) == 1): ?>
        <?=count($name)?> result found for "<?=$content?>"
    <?php else: ?>
        <?=count($color)?> results found for "<?=$content?>"
    <?php endif; ?>

<?php elseif(strtolower($content) == "collections"): ?>
    <?=count($product)?> results found for "<?=$content?>"

<?php elseif(strtolower($content) == "all"): ?>
    <?=count($all)?> results found for "<?=$content?>"

<?php elseif(strtolower($content) == "new"): ?>
    <?=count($new)?> results found for "<?=$content?>"
<?php endif; ?>