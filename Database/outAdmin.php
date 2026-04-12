<?php 
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("error " . $conn->connect_error);
}

unset($_SESSION['admin_otp']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_password']);

header("Location: ../Pages/reglog.php");
exit;
?>