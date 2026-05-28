<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit;
}

$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);
if ($data) {
    $_POST = $data;
}

$cart_ids   = isset($_POST['cart_ids']) ? $_POST['cart_ids'] : [];
$cart_size  = isset($_POST['cart_size']) ? $_POST['cart_size'] : [];
$cart_color = isset($_POST['cart_color']) ? $_POST['cart_color'] : [];
$voucher    = isset($_POST['id']) ? $_POST['id'] : null;

$_SESSION['checkout_cart_ids'] = $cart_ids;
$_SESSION['checkout_size']     = $cart_size;
$_SESSION['checkout_color']    = $cart_color;
$_SESSION['voucher_id']        = $voucher;

if(empty($_SESSION['checkout_cart_ids'])){
    echo json_encode([
        'status' => 'error',
        'message' => 'No items selected!'
    ]);
    exit;
}else{
    echo json_encode([
        'status' => 'success',
        'redirect' => '../Pages/payment.php'
    ]);
    exit;
}


header("Location: ../Pages/payment.php");
exit;
?>