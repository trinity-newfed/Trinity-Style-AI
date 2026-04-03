<?php 
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("error " . $conn->connect_error);
}


session_start();
unset($_SESSION['register_data']);
unset($_SESSION['otp']);

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>