<?php
$conn = new mysqli("localhost", "root", "", "TF_Database");
if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

$id      = $_POST['id'];
$name    = $_POST['product_name'];
$price   = $_POST['product_price'];
$type = $_POST['product_type'];
$des = $_POST['product_describe'];
$state = $_POST['product_state'];
$color  = $_POST['product_color'];

$sql = "UPDATE products SET
            product_name    = '$name',
            product_price   = '$price',
            product_type = '$type',
            product_describe = '$des',
            product_state = '$state',
            product_color  = '$color'
        WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: admin.php");
    exit();
} else {
    echo "Lỗi: " . $conn->error;
}
