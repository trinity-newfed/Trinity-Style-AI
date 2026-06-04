<?php 
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
session_start();

if(!isset($_SESSION['username']) || $_SESSION['role'] != "user"){
    header("Location: reglog.php");
    exit;
}

$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);

$usersql = "SELECT * FROM userdata WHERE id = ?";

$stmt = $conn->prepare($usersql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$userdata = $stmt->get_result();
$users = $userdata->fetch_assoc();;
$stmt->close();

$voucher = $conn
  ->query("SELECT * FROM vouchers")
  ->fetch_all(MYSQLI_ASSOC);

$user_claim = "SELECT * FROM user_voucher WHERE user_id = ?";
$stmt = $conn->prepare($user_claim);
$stmt->bind_param("i", $userID);
$stmt->execute();
$user_all = $stmt->get_result();
$claimed = [];
while($row = $user_all->fetch_assoc()){
    $claimed[] = $row['voucher_id'];
}
$stmt->close();

$used_voucher = $conn->prepare("SELECT * FROM used_voucher WHERE user_id = ?");
$used_voucher->bind_param("i", $userID);
$used_voucher->execute();
$useds = $used_voucher->get_result();
$used = $useds->fetch_all(MYSQLI_ASSOC);
$used_ids = [];
foreach($used as $u){
    $used_ids[] = $u['voucher_id'];
}
$used_voucher->close();
?>