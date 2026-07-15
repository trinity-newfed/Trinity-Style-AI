<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

//PRODUCT SEARCH
$product = $conn
  ->query("SELECT products.id AS id,
            products.product_name, products.product_group,
            products.product_price, products.product_category,
            products.product_type, products.product_describe,
            
            product_variant.product_price, product_variant.product_id AS variant_id,
            product_variant.product_size, product_variant.product_img,
            product_variant.product_stock AS variant_stock, product_variant.product_color

            FROM products
            JOIN product_variant
            ON products.id = product_variant.product_id
            ")
  ->fetch_all(MYSQLI_ASSOC);

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);

//VOUNCHER FETCH
if(isset($_SESSION['user_id'])){

    $stmt = $conn->prepare("SELECT
    vouchers.id AS id,
    vouchers.voucher_condition,
    vouchers.voucher_discount,
    vouchers.voucher_type,  
    vouchers.voucher_max,
    user_voucher.voucher_id
FROM vouchers
JOIN user_voucher
    ON vouchers.id = user_voucher.voucher_id
WHERE user_id = ?
");

    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $voucher = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}else{
    $voucher = [];
}



//CART FETCH
if(isset($_SESSION['user_id'])){

    $stmt = $conn->prepare("SELECT 
    cart.id AS cart_id,
    cart.product_id,
    products.product_name,
    products.product_price,
    products.product_category,
    products.product_state,
    products.product_is_delete,
    cart.cart_size,
    cart.quantity,
    
    product_variant.product_color AS variant_color,
    product_variant.product_img AS variant_img,
    product_variant.product_stock AS variant_stock
    
    FROM cart
    JOIN products 
    ON cart.product_id = products.id
    JOIN product_variant 
    ON cart.product_id = product_variant.product_id 
    AND cart.product_color = product_variant.product_color
    WHERE cart.user_id = ?
");

$stmt->bind_param("i", $userID);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);



} else {
    $data = [];
}
$address = 0;
if(isset($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
    $distance = $conn->prepare("SELECT user_address FROM userdata
                            WHERE id = ?");
    $distance->bind_param("i", $userId);
    $distance->execute();
    $userAddress = $distance->get_result();

    if($userAddress->num_rows > 0){
        $row = $userAddress->fetch_assoc();
        $address = $row['user_address'];
    }
}

?>