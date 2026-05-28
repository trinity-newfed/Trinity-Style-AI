<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "TF_DATABASE";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if((!isset($_SESSION['role']) && $_SESSION['role'] != "adminTan") || (!isset($_SESSION['role']) && $_SESSION['role'] != "adminTrung")){
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/");
    exit();
}

$file = fopen("products.csv", "r");
$variant = fopen("product_variant.csv", "r");

while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {

    $name = $data[0];
    $group = $data[1];
    $price = $data[2];
    $category = $data[3];
    $type = $data[4];
    $description = $data[5];
    $size = $data[6];
    $front = $data[7];
    $side = $data[8];
    $back = $data[9];

    $sql = "INSERT INTO products 
            (product_name, product_group, product_price, product_category, product_type, product_describe, product_size, product_img, product_img1, product_img2)
            VALUES 
            ('$name','$group','$price','$category','$type', '$description','$size','$front','$side','$back')";

    $conn->query($sql);
}
while (($data = fgetcsv($variant, 1000, ",")) !== FALSE) {

    $id = $data[0];
    $price = $data[1];
    $color = $data[2];
    $size = $data[3];
    $front = $data[4];
    $side = $data[5];
    $back = $data[6];

    $sql = "INSERT INTO product_variant 
            (product_id, product_price, product_color, product_size, product_img, product_img1, product_img2)
            VALUES 
            ('$id', '$price', '$color', '$size', '$front', '$side', '$back')";

    $conn->query($sql);
}

fclose($file);
fclose($variant);

header("Location: admin.php");
exit;

$conn->close();
?>
