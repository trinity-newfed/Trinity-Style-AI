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
                        order_items.product_id, 
                        order_items.size, order_items.quantity,
                        
                        products.id,
                        order_items.price,
                        products.product_name,
                        products.product_category,
                        
                        product_variant.product_color, 
                        product_variant.product_id,
                        product_variant.product_stock,
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
$row = $item->fetch_all(MYSQLI_ASSOC);

foreach($row as $r){
    $conn->execute_query("INSERT INTO cart (user_id, product_category, product_color, cart_size, product_id, quantity)
                          VALUES (?, ?, ?, ?, ?, ?)
                          ON DUPLICATE KEY UPDATE quantity = IF(quantity + VALUES(quantity) > ?, ?, quantity + VALUES(quantity))", 

                          [
                            $userID, $r['product_category'], 
                            $r['product_color'], $r['size'], 
                            $r['product_id'], 
                            $r['quantity'],
                            $r['product_stock'], 
                            $r['product_stock']
                          ]);
}
    header("Location: ../Pages/cart.php");
    exit;
?>