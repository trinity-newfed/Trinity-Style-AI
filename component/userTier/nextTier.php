<?php 
$stmt = $conn->execute_query("SELECT 
                        COUNT(*) AS total_orders, 
                        SUM(order_final_price) AS total_spent 
                        FROM orders WHERE user_id = ?",[$userID]);

$orderData = $stmt->fetch_assoc();
$totalOrdersCount = $orderData['total_orders'] ?? 0;
$totalSpent = $orderData['total_spent'] ?? 0;
$nextTier = 0;

if($users['user_tier'] == 1){
    $nextTier = 500 - $totalSpent;
    $tierSpent = 500;
    $next = "Silver"; 
}
elseif($users['user_tier'] == 2){
    $nextTier = 2000 - $totalSpent;
    $tierSpent = 2000; 
    $next = "Gold";
}
elseif($users['user_tier'] == 3){
    $nextTier = 5000 - $totalSpent;
    $tierSpent = 5000;
    $next = "Diamond";
}
?>