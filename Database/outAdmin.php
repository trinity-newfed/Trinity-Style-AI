<?php 
session_start();

include('host.php');

if ($conn->connect_error) {
    die("error " . $conn->connect_error);
}

unset($_SESSION['adminOtp']);
unset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['register_data']);

header("Location: ../Pages/reglog.php");
exit;
?>