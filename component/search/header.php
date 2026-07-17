<?php
include('../Database/host.php');

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);
?>