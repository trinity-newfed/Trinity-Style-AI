<?php if(count($name) > 1): ?>
    <?=count($name)?> results found for "<?=$content?>"
<?php elseif(count($name) == 1): ?>
    <?=count($name)?> result found for "<?=$content?>"
<?php else: ?>
    <?=count($color)?> results found for "<?=$content?>"
<?php endif; ?>