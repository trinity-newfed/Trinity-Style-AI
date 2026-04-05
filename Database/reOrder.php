<?php 
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";
$conn = new mysqli($host, $user, $password, $dbname);

if($conn->connect_error){
    die("error" .$conn->connect_error);
}

session_start();
$userID = $_SESSION['user_id'];
$id = $_POST['order_id'];

$items = $conn->prepare("SELECT
                        order_items.order_id as order_id,
                        order_items.product_id, products.id,
                        products.product_category, products.product_color,
                        order_items.size, order_items.quantity
                        FROM order_items
                        JOIN products
                        ON order_items.product_id = products.id
                        WHERE order_id = ?
                         ");
$items->bind_param("i", $id);
$items->execute();
$item = $items->get_result();
$row = $item->fetch_all(MYSQLI_ASSOC);
    foreach($row as $r){
        $cartCheck = $conn->prepare("SELECT user_id, product_category, product_color, cart_size, product_id FROM cart
                               WHERE user_id = ?
                               AND product_category = ?
                               AND product_color = ?
                               AND cart_size = ?
                               AND product_id = ?");
        $cartCheck->bind_param("isssi", $userID, $r['product_category'], $r['product_color'], $r['size'], $r['product_id']);
        $cartCheck->execute();
        $rows = $cartCheck->get_result();
        if($rows->num_rows > 0){
            $update = $conn->prepare("UPDATE cart SET quantity = quantity + ?
                                      WHERE user_id = ?
                                      AND product_category = ?
                                      AND product_color = ?
                                      AND cart_size = ?
                                      AND product_id = ?");
            $update->bind_param("iisssi", $r['quantity'], $userID, $r['product_category'], $r['product_color'], $r['size'], $r['product_id']);
            $update->execute();
        }else{
            $insert = $conn->prepare("INSERT INTO cart(user_id, product_category, product_color, cart_size, product_id, quantity)
                                      VALUES(?, ?, ?, ?, ?, ?)");
            $insert->bind_param("isssii", $userID, $r['product_category'], $r['product_color'], $r['size'], $r['product_id'], $r['quantity']);
            $insert->execute();
        }
    }
    header("Location: ../Pages/cart.php");
    exit;
?>