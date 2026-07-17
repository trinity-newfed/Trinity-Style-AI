<?php
include('../Database/host.php');

session_start();

$id = $_GET['id'];
$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];

$sql = "SELECT * FROM userdata
            WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$sql = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$sql->bind_param("ii", $id, $userID);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();

$items = $conn->prepare("SELECT
                        order_items.order_id as order_id,
                        order_items.product_id, 
                        order_items.size, order_items.quantity,
                        
                        products.id,
                        order_items.price,
                        products.product_name,
                        products.product_category,
                        
                        product_variant.product_color, 
                        product_variant.product_id,
                        product_variant.product_img

                        FROM order_items
                        JOIN products
                        ON order_items.product_id = products.id
                        JOIN product_variant
                        ON order_items.product_id = product_variant.product_id
                        AND order_items.color = product_variant.product_color
                        WHERE order_id = ?
                         ");
$items->bind_param("i", $id);
$items->execute();
$item = $items->get_result();
$rows = $item->fetch_all(MYSQLI_ASSOC);
$count = 0;

foreach ($rows as $r) {
    $count = $count + $r['quantity'];
}

$order_name = $conn->execute_query("SELECT order_name FROM orders WHERE id = ? AND user_id = ?", [$id, $userID])
    ->fetch_assoc();

$status = $conn->execute_query("SELECT * FROM order_tracking WHERE user_id = ? AND order_name = ?", [$userID, $order_name['order_name']])
    ->fetch_all(MYSQLI_ASSOC);

//Cart fetch
$cart = $conn->execute_query("SELECT COUNT(*) as total_rows FROM cart WHERE user_id = ?", [$userID])
    ->fetch_assoc();
$noti = $cart['total_rows'] ?? 0;
?>