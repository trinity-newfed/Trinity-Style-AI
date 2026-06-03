<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Lỗi kết nối DB");
}

$content = $_GET['content'] ?? '';

$content = str_replace(' ', '%', $content);
$searchTerm = "%" . $content . "%";

$name = $conn->execute_query("SELECT * FROM products WHERE product_name LIKE ?",[$searchTerm])
             ->fetch_all(MYSQLI_ASSOC);

$color = $conn->execute_query("SELECT products.id AS id, products.product_name,

                                      product_variant.product_id, product_variant.product_color,
                                      product_variant.product_img, product_variant.product_price

                                      FROM products
                                      JOIN product_variant 
                                      ON products.id = product_variant.product_id
                                      WHERE product_color LIKE ?",[$searchTerm])
             ->fetch_all(MYSQLI_ASSOC);
?>