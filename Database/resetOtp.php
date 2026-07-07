<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("error " . $conn->connect_error);
}

session_start();
$email = $_SESSION['otp_email'];

if (!$email) {
    die("Email not found in session.");
}

$cooldownTime = 30;
$otpExpire = 180;
$maxAttempt = 3;

$stmt = $conn->prepare("SELECT otp, expire_at, max_otp, created_at FROM user_otp WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$check = $result->fetch_assoc();

$now = time();

if ($check) {
    $lastSent = strtotime($check['created_at'] ?? 0);
    $expired = (int) ($check['expire_at'] ?? 0);
    $maxOtp = (int) ($check['max_otp'] ?? 0);

    if ($lastSent === false || $lastSent === 0) {
        echo "Sai";
    }

    if ($now > ($lastSent + 300)) {
        $maxOtp = 0;
    }

    if (($lastSent + $cooldownTime) > $now) {
        echo "Chưa hết cooldown. Hết hạn lúc: " . date('Y-m-d H:i:s', $lastSent + $cooldownTime) . " | Giờ hiện tại: " . date('Y-m-d H:i:s', $now);
        exit;
    }

    if ($maxOtp >= $maxAttempt) {
        echo "<script>
                alert('Too many requests, please try again later!');
                window.location.href='../Pages/reglog.php?otp=1';
              </script>";
        exit;
    }

    $otp = random_int(100000, 999999);
    $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
    $expire = $now + $otpExpire;
    $created_at = date('Y-m-d H:i:s');

    $maxOtp++;
    $stmt = $conn->prepare("UPDATE user_otp 
        SET otp = ?, expire_at = ?, max_otp = ?, created_at = ?
        WHERE email = ?");
    $stmt->bind_param("sisss", $hashedOtp, $expire, $maxOtp, $created_at, $email);
    $stmt->execute();

} else {
    $otp = random_int(100000, 999999);
    $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
    $expire = $now + $otpExpire;
    $created_at = date('Y-m-d H:i:s');
    $maxOtp = 1;

    $stmt = $conn->prepare("INSERT INTO user_otp (email, otp, expire_at, max_otp, created_at)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $email, $hashedOtp, $expire, $maxOtp, $created_at);
    $stmt->execute();
}

try {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    
    $url = $protocol . $host . "/Trinity-Style-AI/Database/loginMail.php";

    $key = "trinitySMTP2026";
    $timestamp = time();
    $tempToken = hash_hmac('sha256', $email . $timestamp, $key);

    $param = [
        'email' => $email,
        'timestamp' => $timestamp,
        'token' => $tempToken,
        'content' => 'Reset OTP'
    ];

    $redis = new Predis\Client([
        'scheme' => 'tcp',
        'host' => '127.0.0.1',
        'port' => 6379,
    ]);

    $job_data = json_encode([
        'url' => $url,
        'param' => $param,
        'created_at' => time()
    ]);

    $redis->rpush('smtp_mail_queue', $job_data);
} catch (Exception $e) {
    error_log("Redis Queue Error: " . $e->getMessage());
}
?>