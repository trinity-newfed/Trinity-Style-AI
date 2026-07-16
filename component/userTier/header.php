<?php 
include('../Database/host.php');

session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "user"){
    header("Location: reglog.php");
    exit;
}

$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];

$usersql = "SELECT * FROM userdata WHERE id = ?";

$stmt = $conn->prepare($usersql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$userdata = $stmt->get_result();
$users = $userdata->fetch_assoc();;
$stmt->close();


$order = $conn->execute_query("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 2",[$userID])
              ->fetch_all(MYSQLI_ASSOC);
?>