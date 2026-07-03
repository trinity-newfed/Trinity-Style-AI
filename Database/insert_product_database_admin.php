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
if (!isset($_SESSION['role']) || ($_SESSION['role'] != "adminTan" && $_SESSION['role'] != "adminTrung")) {
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/");
    exit();
}

$file = fopen("products.csv", "r");
$variant = fopen("product_variant.csv", "r");

$stmt = $conn->prepare("INSERT INTO products 
    (product_name, product_group, product_price, product_category, product_type, product_describe, product_size, product_img, product_img1, product_img2) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
    $stmt->bind_param("sdssssssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9]);
    $stmt->execute();
}
$stmt->close();

$stmt2 = $conn->prepare("INSERT INTO product_variant 
    (product_id, product_price, product_color, product_size, product_img, product_img1, product_img2)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

while (($data = fgetcsv($variant, 1000, ",")) !== FALSE) {
    $stmt2->bind_param("sdsssss", $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6]);
    $stmt2->execute();
}
$stmt2->close();

fclose($file);
fclose($variant);
$conn->close();

header("Location: admin.php");
exit;
?>