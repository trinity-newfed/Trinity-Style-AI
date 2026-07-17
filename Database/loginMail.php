<?php 
session_set_cookie_params(['path' => '/']);
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__)); 
$dotenv->load();

include('host.php');

if ($conn->connect_error){
    http_response_code(500);
    die("Database Connection Failed: " . $conn->connect_error);
}

ignore_user_abort(true); 
set_time_limit(120);

$email     = $_POST['email'] ?? 'lyt35457@gmail.com';
$timestamp = $_POST['timestamp'] ?? '';
$token     = $_POST['token'] ?? '';
$key       = "trinitySMTP2026";
$content   = $_POST['content'] ?? 'User';

$tokenCheck = hash_hmac('sha256', $email . $timestamp, $key);

if (!hash_equals($tokenCheck, $token)) {
    http_response_code(403);
    echo "Lỗi Xác Thực: Token HMAC không hợp lệ! Check email: '$email', timestamp: '$timestamp'";
    exit;
}

$expireTime = time() + 180;
$otp        = rand(100000, 999999);

function sendOtpEmail($targetEmail, $otpCode, $accountType = "User"){
    $mail = new PHPMailer(true);

    $mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {
        error_log("SMTP DEBUG: $str");
    };

    $mail->isSMTP();
    $mail->Host       = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $_ENV['SMTP_USER'] ?? '';
    $mail->Password   = $_ENV['SMTP_PASS'] ?? '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)($_ENV['SMTP_PORT'] ?? 587);

    $mail->Timeout    = 10; 

    $mail->setFrom($_ENV['SMTP_USER'] ?? 'triple3tbusiness@gmail.com', 'TRINITY Authentication');
    $mail->addAddress($targetEmail);

    $mail->isHTML(true);
    $mail->Subject = 'Your TRINITY Security Passcode';
    
    $currentYear = date("Y");
    $mail->Body = "
        <div style='margin:0; padding:0; background-color:#f9f9f9;'>
            <div style='max-width:480px; margin:40px auto; background:#ffffff; border:1px solid #eaebec; padding:40px 32px; font-family:Arial, sans-serif; color:#1c1917;'>
                <div style='text-align:center; margin-bottom:32px;'>
                    <div style='font-size:18px; font-weight:300; letter-spacing:4px; text-transform:uppercase;'>TRINITY ARCHIVE</div>
                </div>
                <p style='font-size:13px; line-height:1.6; color:#44403c;'>
                    Hello, [$accountType Account: $targetEmail]<br><br>
                    An authentication request requires verification. Please use the following one-time passcode:
                </p>
                <div style='text-align:center; margin:32px 0;'>
                    <span style='display:inline-block; font-size:26px; letter-spacing:8px; font-weight:bold; color:#000000; background:#f5f5f4; padding:14px 28px;'>
                        $otpCode
                    </span>
                </div>
                <p style='font-size:12px; color:#78716c; line-height:1.5; margin-bottom:24px;'>
                    This secure dynamic key expires in exactly 3 minutes. For security protocols, do not disclose this credential to any external entity.
                </p>    
                <p style='font-size:11px; color:#a8a29e; border-top:1px solid #f5f5f4;'>
                    If you did not execute this request, please safely disregard this automated transmission.
                </p>
                <div style='text-align:center; font-size:10px; color:#a8a29e; margin-top:32px; letter-spacing:1px;'>&copy; {$currentYear} TRINITY ARCHIVE GLOBAL</div>
            </div>
        </div>";

    $mail->send();
}

function syncOtpToDatabase($conn, $email, $otp, $expireTime){
    $hashedOtp   = password_hash($otp, PASSWORD_BCRYPT);
    $expireLimit = 86400;

    $sql = "INSERT INTO user_otp (email, otp, expire_at, max_otp) 
            VALUES (?, ?, ?, 1) 
            ON DUPLICATE KEY UPDATE 
                max_otp = IF(UNIX_TIMESTAMP() - expire_at > ?, 1, max_otp + 1),
                otp = VALUES(otp), 
                expire_at = VALUES(expire_at)";

    $conn->execute_query($sql, [$email, $hashedOtp, $expireTime, $expireLimit]);
}

try {
    $sql = $conn->execute_query("SELECT max_otp FROM user_otp WHERE email = ?", [$email])->fetch_assoc();
    
    if ($sql && $sql['max_otp'] >= 5) {
        http_response_code(429);
        echo "Lỗi: Đã vượt quá 5 lần gửi OTP cho email $email!";
        exit;
    }

    sendOtpEmail($email, $otp, $content);
    
    syncOtpToDatabase($conn, $email, $otp, $expireTime);

    http_response_code(200);
    echo "SUCCESS: OTP đã gửi tới $email";

} catch (Exception $e) {
    http_response_code(500);
    echo "Lỗi Gửi Mail SMTP: " . $e->getMessage();
} catch (\Throwable $e) {
    http_response_code(500);
    echo "Lỗi Hệ Thống: " . $e->getMessage();
}
?>