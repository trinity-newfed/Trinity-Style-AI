<?php
session_set_cookie_params(['path' => '/']);
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__)); 
$dotenv->load();

session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error){
    die("Database Connection Failed: " . $conn->connect_error);
}

function sendAdminOtpEmail($targetEmail, $otpCode, $adminName) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('triple3tbusiness@gmail.com', 'TRINITY Admin Security');
        $mail->addAddress($targetEmail);

        $mail->isHTML(true);
        $mail->Subject = 'TRINITY ARCHIVE - Elevated MFA Access Key';
        
        $currentYear = date("Y");
        $mail->Body = "
            <div style='margin:0; padding:0; background-color:#111111;'>
                <div style='max-width:480px; margin:40px auto; background:#ffffff; border:1px solid #eaebec; padding:40px 32px; font-family:Arial, sans-serif; color:#1c1917;'>
                    <div style='text-align:center; margin-bottom:32px;'>
                        <div style='font-size:18px; font-weight:400; letter-spacing:4px; text-transform:uppercase; color:#000000;'>TRINITY EXECUTIVE Portal</div>
                    </div>
                    <p style='font-size:13px; line-height:1.6; color:#44403c;'>
                        Attention Admin: <strong>$adminName</strong> ($targetEmail)<br><br>
                        A request for elevated administrative terminal access has been initialized. Authorize using the secure one-time passcode below:
                    </p>
                    <div style='text-align:center; margin:32px 0;'>
                        <span style='display:inline-block; font-size:28px; letter-spacing:10px; font-weight:bold; color:#ffffff; background:#000000; padding:14px 28px;'>
                            $otpCode
                        </span>
                    </div>
                    <p style='font-size:12px; color:#78716c; line-height:1.5; margin-bottom:24px;'>
                        This cryptographic validation key is transient and expires in exactly 3 minutes.
                    </p>    
                    <div style='text-align:center; font-size:10px; color:#a8a29e; margin-top:32px; letter-spacing:1px;'>&copy; {$currentYear} TRINITY CONTROL SYSTEM</div>
                </div>
            </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Admin Security Mail Engine Failure: " . $mail->ErrorInfo);
        return false;
    }
}

function syncAdminOtpToDatabase($conn, $email, $otp, $expireTime) {
    $clear = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
    $clear->bind_param("s", $email);
    $clear->execute();
    $clear->close();

    $hashedOtp = password_hash($otp, PASSWORD_BCRYPT);

    $insert = $conn->prepare("INSERT INTO user_otp (email, otp, expire_at) VALUES (?, ?, ?)");
    $insert->bind_param("ssi", $email, $hashedOtp, $expireTime);
    $insert->execute();
    $insert->close();
}



    $email = $_SESSION['tempAdminEmail'];
    $inputOtp = $_POST['otp'] ?? "";

    if (isset($_SESSION['adminOtp']) && isset($_SESSION['pending_admin'])) {
        if (time() > ($_SESSION['adminOtp_expire'] ?? 0)) {
            unset($_SESSION['adminOtp'], $_SESSION['adminOtp_expire'], $_SESSION['adminOtp_email'], $_SESSION['pending_admin']);
            echo "<script>alert('Administrative OTP validation matrix expired.'); window.location.href='../Pages/reglog.php';</script>";
            exit;
        }

        $targetEmail = $_SESSION['adminOtp_email'];
        $adminUsername = $_SESSION['pending_admin'];

        $dbHashedOtp = $conn->execute_query("SELECT otp FROM user_otp WHERE email = ?", [$email])
                            ->fetch_assoc();

        if (password_verify($inputOtp, $dbHashedOtp['otp'])) {
            
            session_regenerate_id(true);

            $_SESSION['username'] = $adminUsername;
            $_SESSION['role'] = ($adminUsername == "Trung09") ? "adminTrung" : "adminTan";
            
            $clearOtpStmt = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
            $clearOtpStmt->bind_param("s", $targetEmail);
            $clearOtpStmt->execute();
            $clearOtpStmt->close();

            unset($_SESSION['adminOtp'], $_SESSION['adminOtp_expire'], $_SESSION['adminOtp_email'], $_SESSION['pending_admin']);
            
            header("Location: ../Database/dashboard.php");
            exit;
        } else {
            echo "<script>window.location.href='../Pages/adminLogin.html';</script>";
            exit;
        }
    }


    if ($email == "Tan1206" || $email == "Trung09") {
        
        $adminMail = $email == "Trung09" ? $_ENV['SMTP_EMAIL1'] : $_ENV['SMTP_EMAIL2'];

        $expireTime = time() + 180;
        $_SESSION['adminOtp_expire'] = $expireTime;
        $_SESSION['adminOtp_email'] = $adminMail; 
        $_SESSION['pending_admin'] = $email; 
        
        $otp = rand(100000, 999999);
        $_SESSION['adminOtp'] = $otp; 


        sendAdminOtpEmail($adminMail, $otp, $email);
        syncAdminOtpToDatabase($conn, $email, $otp, $expireTime);
        
        echo "<script>window.location.href='../Pages/adminLogin.html';</script>";
        exit;
    } else {

        echo $email;
        exit;
    }

?>