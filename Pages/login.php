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

function sendOtpEmail($targetEmail, $otpCode, $accountType = "User") {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('triple3tbusiness@gmail.com', 'TRINITY Authentication');
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
        return true;
    } catch (Exception $e) {
        error_log("Security Mail Engine Failure: " . $mail->ErrorInfo);
        return false;
    }
}


function syncOtpToDatabase($conn, $email, $otp, $expireTime) {
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


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'] ?? "";
    $plainPassword = $_POST['user_password'] ?? '';
    $passless = isset($_POST['passless']) ? (int)$_POST['passless'] : 0;
    $inputOtp = $_POST['otp'] ?? "";

    if($email == "Tan1206" || $email == "Trung09"){
        $email == "Trung09" ? $_SESSION['tempAdminEmail'] = "Trung09" : $_SESSION['tempAdminEmail'] = "Tan1206";
        echo "<script>confirm('Redirect To Another Pages?'); window.location.href='../Database/adminLogin.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM userdata WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows != 1) {
        echo "<script>alert('Credentials unrecognized in our archives.'); window.location.href='reglog.php';</script>";
        exit;
    }

    $row = $result->fetch_assoc();
    $count = (int)$row['user_limit_password'];
    $dbHashedPassword = $row['user_password'];

    if (isset($_SESSION['otp'])) {
        if (time() > ($_SESSION['otp_expire'] ?? 0)) {
            unset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['otp_email']);
            echo "<script>alert('One-time verification matrix expired.'); window.location.href='reglog.php';</script>";
            exit;
        }

        $dbHashedOtp = $conn->execute_query("SELECT otp FROM user_otp WHERE email = ?", [$email])
                            ->fetch_assoc();
        

        if (password_verify($inputOtp, $dbHashedOtp['otp'])) {
            
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $email;
            $_SESSION['role'] = 'user';

            $updateStmt = $conn->prepare("UPDATE userdata SET user_limit_password = 0 WHERE email = ?");
            $updateStmt->bind_param("s", $email);
            $updateStmt->execute();
            $updateStmt->close();

            $clearOtpStmt = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
            $clearOtpStmt->bind_param("s", $email);
            $clearOtpStmt->execute();
            $clearOtpStmt->close();

            unset($_SESSION['otp'], $_SESSION['otp_expire'], $_SESSION['otp_email']);
            header("Location: ../Pages/home.php");
            exit;
        } else {
            echo "<script>alert('Verification security code mismatch.'); window.location.href='reglog.php';</script>";
            exit;
        }
    }

    if ($passless == 0 && password_verify($plainPassword, $dbHashedPassword)) {
        if ($count < 5) {
            sleep(2); 
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $email;
            $_SESSION['role'] = 'user';

            $updateStmt = $conn->prepare("UPDATE userdata SET user_limit_password = 0 WHERE email = ?");
            $updateStmt->bind_param("s", $email);
            $updateStmt->execute();
            $updateStmt->close();

            $clearOtpStmt = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
            $clearOtpStmt->bind_param("s", $email);
            $clearOtpStmt->execute();
            $clearOtpStmt->close();

            header("Location: ../Pages/home.php");
            exit;
        }

        $expireTime = time() + 180;
        $_SESSION['otp_expire'] = $expireTime;
        $_SESSION['otp_email'] = $email;
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        sendOtpEmail($email, $otp, "Standard User Locked");
        syncOtpToDatabase($conn, $email, $otp, $expireTime);
        
        echo "<script>alert('Account under observation due to rapid attempts. Verification code sent to email.'); window.location.href='reglog.php';</script>";
        exit;

    } elseif ($passless == 1) {
        $expireTime = time() + 180;
        $_SESSION['otp_expire'] = $expireTime;
        $_SESSION['otp_email'] = $email;
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;

        sendOtpEmail($email, $otp, "Passwordless Access");
        syncOtpToDatabase($conn, $email, $otp, $expireTime);
        
        echo "<script>alert('Passwordless protocol active. Access code deployed to email.'); window.location.href='reglog.php';</script>";
        exit;

    } else {
        $count++;
        $updateStmt = $conn->prepare("UPDATE userdata SET user_limit_password = ? WHERE email = ?");
        $updateStmt->bind_param("is", $count, $email);
        $updateStmt->execute();
        $updateStmt->close();

        sleep(2);

        if ($count >= 5) {
            $expireTime = time() + 180;
            $_SESSION['otp_expire'] = $expireTime;
            $_SESSION['otp_email'] = $email;
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;

            sendOtpEmail($email, $otp, "Brute Force Protection");
            syncOtpToDatabase($conn, $email, $otp, $expireTime);

            echo "<script>alert('Maximum consecutive threshold reached. Dynamic OTP required.'); window.location.href='reglog.php';</script>";
        } else {
            echo "<script>alert('Invalid identifier tokens or access password.'); window.location.href='reglog.php';</script>";
        }
        header("Location: ../Pages/reglog.php");
        exit;
    }
}
?>