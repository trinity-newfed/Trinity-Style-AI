<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";
$conn = new mysqli($host, $user, $password, $dbname);

if($conn->connect_error){
    die("error" .$conn->connect_error);
}

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;


$products = $conn
  ->query("SELECT * FROM products")
  ->fetch_all(MYSQLI_ASSOC);


$sql = $conn->prepare("SELECT * FROM user_policy_agreement
                       WHERE user_id = ?");
$sql->bind_param("i", $userID);
$sql->execute();
$agreement = $sql->get_result();
$agree = ($agreement->num_rows > 0) ? 1 : 0;
$sql->close();

$response = [
    "userInfo" => [
        "id" => $userID,
        "username" => $username
    ],
    "products" => $products,
    "agree" => $agree
];

echo json_encode($response);
exit();
?>