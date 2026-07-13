<?php
header('Content-Type: application/json; charset=utf-8');

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
    echo json_encode([
        'status' => false,
        'color' => '#FFCCCC',
        'message' => 'Database connection failed.'
    ]);
    exit;
}

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $registerEmail = $_POST['registerEmail'] ?? "";
    $password = $_POST['user_password'] ?? "";
    $address = isset($_POST['user_address']) ? trim($_POST['user_address']) : "";
    $hotline = isset($_POST['user_hotline']) ? trim($_POST['user_hotline']) : "";
    $inputOtp = $_POST['registerOtp'] ?? null;

    if (isset($_SESSION['register_data']))
        $checkMail = $_SESSION['register_data']['email'];
    else
        $checkMail = $registerEmail;

    $stmt = $conn->prepare("SELECT id FROM userdata WHERE email = ?");
    $stmt->bind_param("s", $checkMail);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode([
            'status' => false,
            'color' => '#FFCCCC',
            'message' => 'Account Already Exists.'
        ]);
        $stmt->close();
        exit;
    }
    $stmt->close();

    if (empty($registerEmail) || empty($password) || empty($hotline) || empty($address)) {
        if (!isset($_SESSION['register_data'])) {
            echo json_encode([
                'status' => false,
                'color' => '#FFCCCC',
                'message' => 'Please Fill All Required Information'
            ]);
            exit;
        }
    } else {
        if (!isset($_SESSION['register_data'])) {
            $_SESSION['register_data'] = [
                'email' => $registerEmail,
                'password' => $password,
                'address' => $address,
                'hotline' => $hotline
            ];
        }
    }


    if (!isset($_SESSION['register_data'])) {
        echo json_encode([
            'status' => false,
            'color' => '#FFCCCC',
            'message' => 'Session Expired, Please Register Again.'
        ]);
        exit;
    }

    $data = $_SESSION['register_data'];

    $stmt = $conn->prepare("SELECT otp, max_otp FROM user_otp WHERE email = ?");
    $stmt->bind_param("s", $data['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();


    if ($inputOtp != '' && isset($_SESSION['register_data'])) {
        if ($row) {
            if (password_verify($inputOtp, $row['otp'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

                $stmt = $conn->prepare("
                        INSERT INTO userdata (email, user_password, user_address, user_hotline)
                        VALUES (?, ?, ?, ?)
                    ");
                $stmt->bind_param("ssss", $data['email'], $hashedPassword, $data['address'], $data['hotline']);

                if ($stmt->execute()) {
                    $stmtDel = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
                    $stmtDel->bind_param("s", $data['email']);
                    $stmtDel->execute();

                    unset($_SESSION['register_data'], $_SESSION['otp'], $_SESSION['otp_expire']);

                    echo json_encode([
                        'status' => 'success',
                        'color' => '#daffcc',
                        'redirect' => 'true',
                        'redirectLink' => '../Pages/reglog.php',
                        'message' => 'Register Success.'
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    'status' => 'OTP_failed',
                    'color' => '#FFCCCC',
                    'message' => 'Invalid OTP.'
                ]);
                exit;
            }
        }
    } else {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];

        $url = $protocol . $host . "/Trinity-Style-AI/Database/loginMail.php";

        $key = "trinitySMTP2026";
        $timestamp = time();
        $tempToken = hash_hmac('sha256', $registerEmail . $timestamp, $key);

        $param = [
            'email' => $registerEmail,
            'timestamp' => $timestamp,
            'token' => $tempToken,
            'content' => 'Register'
        ];

        try {
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

        $sql = $conn->execute_query("SELECT max_otp FROM user_otp WHERE email = ?", [$checkMail])
            ->fetch_assoc();

        if ($sql) {
            if ($sql['max_otp'] < 5) {

                $_SESSION['otp'] = 'true';
                $_SESSION['otp_expire'] = time() + 180;

                echo json_encode([
                    'status' => 'success',
                    'otp' => 'required',
                    'color' => '#daffcc',
                    'message' => 'Register OTP Has Been Sent To Your Email.'
                ]);

                exit;
            } else {
                echo json_encode([
                    'status' => false,
                    'otp' => 'none',
                    'color' => '#FFCCCC',
                    'message' => 'OTP Request Limit Reached, Please Try Again Later.'
                ]);

                exit;
            }
        } else {
            $_SESSION['otp'] = 'true';
            $_SESSION['otp_expire'] = time() + 180;

            echo json_encode([
                'status' => 'success',
                'otp' => 'required',
                'color' => '#daffcc',
                'message' => 'Register OTP Has Been Sent To Your Email.'
            ]);

            exit;
        }
    }
    $stmt->close();
}