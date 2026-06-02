<?php 
    $cart = $conn->execute_query("SELECT COUNT(*) as total_rows FROM cart WHERE user_id = ?",[$userID])
                 ->fetch_assoc();
    $noti = $cart['total_rows'] ?? 0;
?>