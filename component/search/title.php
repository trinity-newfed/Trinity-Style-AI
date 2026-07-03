<?php if(strtolower($content) != "collections" && strtolower($content) != "all" && strtolower($content) != "new"): ?>
    <?=count($results)?> results found for "<?=$content?>"

<?php elseif(strtolower($content) == "collections"): ?>
    <?=count($product)?> results found for "<?=$content?>"

<?php elseif(strtolower($content) == "all"): ?>
    <?=count($all)?> results found for "<?=$content?>"

<?php elseif(strtolower($content) == "new"): ?>
    <?=count($new)?> results found for "<?=$content?>"
<?php endif; ?>