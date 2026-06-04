<?php 
include "../Database/createdatabase.php";
session_start();

if(!isset($_SESSION['role'])){
    $_SESSION['role'] = "guest";
}

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);


$product = $conn
  ->query("SELECT products.id AS id,
            products.product_name, products.product_group,
            products.product_price, products.product_category,
            products.product_type, products.product_describe,
            products.product_size, products.product_img,
            product_variant.product_price, product_variant.product_size,
            product_variant.product_img, product_variant.product_img1,
            product_variant.product_color

            FROM products
            JOIN product_variant
            ON products.id = product_variant.product_id
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

$bestSeller = $conn->query("SELECT 
                            product_sold.sold, product_sold.product_id,
                            products.product_name, products.product_img,
                            products.product_price,
                            products.id AS id
                            FROM product_sold 
                            JOIN products
                            ON product_sold.product_id = products.id
                            GROUP BY products.product_name")
                   ->fetch_all(MYSQLI_ASSOC);

$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

if(isset($_SESSION['error'])){
    echo "<script>alert('{$_SESSION['error']}');</script>";
    unset($_SESSION['error']);
}
?>