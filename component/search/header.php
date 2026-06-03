<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);
?>