<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

session_start();

$id = $_POST['cartId'];


if(isset($_POST['cartId']) && isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
}

if($stmt->execute()){
    header("Location: ../Pages/cart.php");
    exit();
}else{
    echo "Xoá thất bại";
}

$stmt->close();
$conn->close();
?>