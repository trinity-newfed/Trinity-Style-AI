<?php 
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../Pages/');
$dotenv->load();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
if($conn->connect_error){
    die("error " . $conn->connect_error);
}

session_start();
$email = $_POST['email'] ?? null;
if(!$email){
    echo "<script>
            alert('Please enter email!');
            window.location.href='../Pages/resetPass.php';
          </script>";
    exit;
}

$_SESSION['resetOTP'] = 1;
$_SESSION['resetEmail'] = $email;


$cooldownTime = 30;
$otpExpire    = 180;
$maxAttempt   = 3;

$sql = $conn->prepare("SELECT * FROM userdata WHERE email = ?");
$sql->bind_param("s", $email);
$sql->execute();
$row = $sql->get_result();
if($row->num_rows == 0){
    echo "<script>
            alert('Account not found!');
            window.location.href='../Pages/resetPass.php';
          </script>";
    exit;
}

$stmt = $conn->prepare("SELECT otp, expire_at, max_otp, created_at FROM user_otp WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$check = $result->fetch_assoc();

$now = time();

if($check){
    $lastSent = strtotime($check['created_at'] ?? 0);
    $maxOtp = (int)($check['max_otp'] ?? 0);

    if($lastSent && $now > $lastSent + 300){
        $maxOtp = 0;
    }

    if($lastSent && $now < $lastSent + $cooldownTime){
        echo "<script>
                alert('Please wait $cooldownTime seconds before requesting OTP again!');
                window.location.href='../Pages/resetPass.php';
              </script>";
        exit;
    }

    if($maxOtp >= $maxAttempt){
        echo "<script>
                alert('Too many requests, please try again later!');
                window.location.href='../Pages/resetPass.php';
              </script>";
        exit;
    }

    $otp = random_int(100000, 999999);
    $hashedOtp = password_hash($otp, PASSWORD_DEFAULT);
    $expire = $now + $otpExpire;
    $created_at = date('Y-m-d H:i:s');
    $maxOtp++;

    $stmt = $conn->prepare("UPDATE user_otp SET otp = ?, expire_at = ?, max_otp = ?, created_at = ? WHERE email = ?");
    $stmt->bind_param("sisss", $hashedOtp, $expire, $maxOtp, $created_at, $email);
    $stmt->execute();

}else{
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


try{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('triple3tbusiness@gmail.com', 'Trinity Verify');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = '
        <div style="margin:0; padding:0; background-color:#f2f2f2;">
            <div style="max-width:480px; margin:40px auto; background:#ffffff; border-radius:8px; padding:32px; font-family:Arial, sans-serif; color:#202124; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <div style="text-align:center; margin-bottom:24px;">
                    <div style="font-size:20px; font-weight:500; color:#202124;">Verification Code</div>
                </div>
                <p style="font-size:14px; line-height:1.6; margin-bottom:20px;">
                    Hello, '.$email.'<br><br>
                    We received an OTP request.
                </p>
                <div style="text-align:center; margin:24px 0;">
                    <span style="display:inline-block; font-size:28px; letter-spacing:6px; font-weight:bold; color:#202124; background:#f1f3f4; padding:12px 24px; border-radius:6px;">
                        '.$otp.'
                    </span>
                </div>
                <p style="font-size:13px; color:#5f6368; margin-bottom:20px;">
                    This code will expire in 3 minutes. Do not share this code with anyone.
                </p>    
                <p style="font-size:12px; color:#9aa0a6;">
                    If you didn’t request this, you can safely ignore this email.
                </p>
            </div>
            <div style="text-align:center; font-size:11px; color:#9aa0a6; margin-top:12px;">© '.date("Y").' TRINITY STYLE AI</div>
        </div>
    ';
    $mail->send();

    echo "<script>
            alert('OTP sent successfully!');
            window.location.href='../Pages/resetPass.php';
          </script>";
    exit;

}catch (Exception $e){
    error_log($mail->ErrorInfo);
    echo "Send mail failed";
}
?>
