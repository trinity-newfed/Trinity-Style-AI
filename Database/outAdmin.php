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

unset($_SESSION['adminOtp']);
unset($_SESSION['otp'], $_SESSION['otp_email']);

header("Location: ../Pages/reglog.php");
exit;
?>