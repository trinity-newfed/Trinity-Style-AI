<?php
header('Content-Type: application/json; charset=utf-8');
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

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $email = $_POST['email'] ?? "";
    $plainPassword = $_POST['user_password'] ?? '';
    $passless = isset($_POST['passless']) ? (int)$_POST['passless'] : 0;
    $inputOtp = $_POST['otp'] ?? "";
    $_SESSION['otp_email'] = $email;

    if($email == "Tan1206" || $email == "Trung09"){
        $email == "Trung09" ? $_SESSION['tempAdminEmail'] = "Trung09" : $_SESSION['tempAdminEmail'] = "Tan1206";
        echo "<script>confirm('Redirect To Another Pages?'); window.location.href='../Database/adminLogin.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM userdata WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if(!$result || $result->num_rows != 1){

        echo json_encode([
            'status' => false,
            'otp' => 'none',
            'color' => '#FFCCCC',
            'message' => 'Account not found.'
        ]);
        exit;
    }

    $row = $result->fetch_assoc();
    $count = (int)$row['user_limit_password'];
    $dbHashedPassword = $row['user_password'];

    $expired = $conn->execute_query("SELECT expire_at FROM user_otp WHERE email = ?",[$email])
                    ->fetch_assoc();

    if(isset($_SESSION['otp'])){
        if (time() > (int)$expired['expire_at']) {

            echo json_encode([
                'status' => false,
                'otp' => 'none',
                'color' => '#FFCCCC',
                'message' => 'OTP expired.'
            ]);
            exit;
        }

        $dbHashedOtp = $conn->execute_query("SELECT otp FROM user_otp WHERE email = ?", [$email])
                            ->fetch_assoc();
        

        if(password_verify($inputOtp, $dbHashedOtp['otp'])){
            
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $email;
            $_SESSION['role'] = 'user';

            $updateStmt = $conn->execute_query("UPDATE userdata SET user_limit_password = 0 WHERE email = ?",[$email]);

            $clearOtpStmt = $conn->execute_query("DELETE FROM user_otp WHERE email = ?",[$email]);


            echo json_encode([
                'status' => 'OTP_success',
                'otp' => 'none',
                'color' => '#daffcc',
                'message' => 'Verify success.'
            ]);

            exit;
        }else{

            echo json_encode([
                'status' => false,
                'otp' => 'none',
                'color' => '#FFCCCC',
                'message' => 'Verification security code mismatch.'
            ]);
            exit;
        }
    }

    if ($passless == 0 && password_verify($plainPassword, $dbHashedPassword)) {
        if ($count < 5) {
            sleep(1); 
            session_regenerate_id(true);

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $email;
            $_SESSION['role'] = 'user';

            $updateStmt = $conn->execute_query("UPDATE userdata SET user_limit_password = 0 WHERE email = ?",[$email]);

            $clearOtpStmt = $conn->execute_query("DELETE FROM user_otp WHERE email = ?",[$email]);

            header("Location: ../Pages/home.php");
            exit;
        }     

        echo json_encode([
            'status' => false,
            'otp' => 'none',
            'color' => '#FFCCCC',
            'message' => 'Failed: Account suspended.'
        ]);
        exit;

    }elseif ($passless == 1){
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];

        $url = $protocol . $host . "/Trinity-Style-AI/Database/loginMail.php";

        $key = "trinitySMTP2026";
        $timestamp = time();
        $tempToken = hash_hmac('sha256', $email . $timestamp , $key);

        $param = [
            'email' => $email,
            'timestamp' => $timestamp,
            'token' => $tempToken,
            'content' => 'Passwordless OTP Login'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_exec($ch);
        curl_close($ch);

        $_SESSION['otp'] = 'true';
        $_SESSION['otp_expire'] = time() + 180;

        echo json_encode([
            'status' => 'success',
            'otp' => 'required',
            'color' => '#daffcc',
            'message' => 'Passwordless login by OTP is deployed in email.'
        ]);
 
        exit;

    } else {
        $num = 1;
        $updateStmt = $conn->execute_query("UPDATE userdata SET user_limit_password = user_limit_password + ? WHERE email = ?",[$num, $email]);

        sleep(1);

        if ($count >= 5) {

            echo json_encode([
                'status' => false,
                'otp' => 'required',
                'color' => '#FFCCCC',
                'message' => 'Failed: Maximum consecutive threshold reached. Dynamic OTP required.'
            ]);
            exit;
        } else {

            echo json_encode([
                'status' => false,
                'otp' => 'none',
                'color' => '#FFCCCC',
                'message' => 'Failed: Invalid email or password.'
            ]);
            exit;
        }
    }
}
?>