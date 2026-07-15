<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

$collection = $conn->query("SELECT * FROM products")
  ->fetch_all(MYSQLI_ASSOC);

$baseProduct = $conn->query("SELECT * FROM products 
                             WHERE product_category != 'collections' 
                             ORDER BY id DESC
                             LIMIT 10")
  ->fetch_all(MYSQLI_ASSOC);

$product = $conn
  ->query("SELECT products.id AS id,
            products.id AS id,
            products.product_name, 
            products.product_group,
            products.product_price, 
            products.product_category,
            products.product_type, 
            products.product_describe,
            products.color_display,

            product_variant.product_stock,
            product_variant.product_img,
            product_variant.product_color

            FROM products
            JOIN product_variant
            ON products.id = product_id
            ")
  ->fetch_all(MYSQLI_ASSOC);

$product_variant = $conn->query("SELECT 
                                 product_variant.product_id, product_variant.product_price,
                                 product_variant.product_img AS variant_img,
                                 product_variant.product_color, product_variant.product_size,
                                 product_variant.product_stock, products.product_name,
                                 products.product_category

                                 FROM product_variant
                                 JOIN products
                                 ON product_variant.product_id = products.id")
  ->fetch_all(MYSQLI_ASSOC);

$sql = $conn->prepare("SELECT * FROM user_policy_agreement
                       WHERE user_id = ?");
$sql->bind_param("i", $userID);
$sql->execute();
$agreement = $sql->get_result();
if ($agreement->num_rows > 0) {
  $agree = 1;
} else {
  $agree = 0;
}
$sql->close();
?>