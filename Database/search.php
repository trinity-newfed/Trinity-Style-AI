<?php
include('host.php');

if ($conn->connect_error) {
    die("Lỗi kết nối DB");
}

$content = $_GET['content'] ?? '';

$content = str_replace(' ', '%', $content);
$searchTerm = "%" . $content . "%";

if (strtolower($content) != "collections" && strtolower($content) != "all" && strtolower($content) != "new") {
    $name = $conn->execute_query("SELECT * FROM products WHERE product_name LIKE ?", [$searchTerm])
        ->fetch_all(MYSQLI_ASSOC);

    $color = $conn->execute_query("SELECT products.id AS id, products.product_name,

                                      product_variant.product_id, product_variant.product_color,
                                      product_variant.product_img, product_variant.product_price

                                      FROM products
                                      JOIN product_variant 
                                      ON products.id = product_variant.product_id
                                      WHERE product_color LIKE ?", [$searchTerm])
        ->fetch_all(MYSQLI_ASSOC);

    $results = array_merge($name, $color);

} elseif (strtolower($content) == "collections") {
    $product = $conn->execute_query("SELECT products.id AS id, products.product_name, 

                                     product_variant.product_price, product_variant.product_img,
                                     product_variant.product_color

                                     FROM products 
                                     JOIN product_variant
                                     ON products.id = product_variant.product_id
                                     WHERE product_category = ?", [$content])
        ->fetch_all(MYSQLI_ASSOC);

} elseif (strtolower($content) == "all") {
    $all = $conn->query("SELECT * FROM products")
        ->fetch_all(MYSQLI_ASSOC);

} elseif (strtolower($content) == "new") {
    $new = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 5")
        ->fetch_all(MYSQLI_ASSOC);
}
?>