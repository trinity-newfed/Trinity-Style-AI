<?php 
    if($users['user_tier'] == 1) $tier = "New Member";
    elseif($users['user_tier'] == 2) $tier = "Silver";
    elseif($users['user_tier'] == 3) $tier = "Gold";
    elseif($users['user_tier'] == 4) $tier = "Diamond";
?>

<?php 
    if($users['user_tier'] == 1){
        $color = "rgb(232,226,217)";
        $secondColor = "rgb(215, 207, 194)";
    }
    elseif($users['user_tier'] == 2){
        $color = "rgb(241,243,245)";
        $secondColor = "rgb(235, 245, 255)";
    }
    elseif($users['user_tier'] == 3){
        $color = "rgb(242,237,222)";
        $secondColor = "rgb(255, 245, 215)";
    }
    elseif($users['user_tier'] == 4){
        $color = "rgb(26,26,26)";
        $secondColor = "rgb(15, 15, 15)";
    }
?>
