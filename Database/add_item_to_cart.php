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

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Pages/reglog.php");
    exit();
}

$userID = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$product_category = $_POST['product_category'];
$product_color = $_POST['product_color'];
$cart_size = $_POST['cart_size'];
$quantity = $_POST['quantity'] ?? 1;

$product = $conn->execute_query("SELECT 
                            product_variant.product_stock, product_variant.product_color,
                            products.id, product_variant.product_id 
                            FROM products
                            JOIN product_variant ON products.id = product_variant.product_id
                            WHERE products.id = ? AND product_variant.product_color = ?", [$product_id, $product_color])
                ->fetch_assoc();

$stmt = $conn->execute_query("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND product_category = ? AND product_color = ? AND cart_size = ?", [$userID, $product_id, $product_category, $product_color, $cart_size])
             ->fetch_assoc();

$stock = $product['product_stock'] ?? 0;

$conn->execute_query("INSERT INTO cart (user_id, product_id, product_category, product_color, cart_size, quantity)
                      VALUES (?, ?, ?, ?, ?, ?)
                      ON DUPLICATE KEY UPDATE quantity = IF(quantity + ? <= ?, quantity + ?, ?)", [$userID, $product_id, $product_category, $product_color, $cart_size, $quantity, $quantity, $stock, $quantity, $stock]);

header('Content-Type: application/json; charset=utf-8');
if($conn->affected_rows === 0){
    echo json_encode([
        "status" => "failed",
        "message" => "Max stock reached or item could not be updated."
    ]);
}else{
    echo json_encode([
        "status" => "success",
        "message" => "Item successfully added to bag."
    ]);
}
exit();
?>
