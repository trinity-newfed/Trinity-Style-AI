<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
if($conn->connect_error){
    die("error " . $conn->connect_error);
}

session_start();
$email = $_SESSION['resetEmail'] ?? null;
$otp_input = $_POST['otp'] ?? null;
$new_password = $_POST['password'] ?? null;

if(!$email){
    die("Email session not set.");
}

$stmt = $conn->prepare("SELECT otp, expire_at FROM user_otp WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if(!$row){
    echo "<script>
            alert('No OTP found for this email!');
            window.location.href='../Pages/resetPass.php';
          </script>";
    exit;
}

if($otp_input){
    $hashedOtp = $row['otp'];
    $expireAt = (int)$row['expire_at'];

    if(time() > $expireAt){
        echo "<script>
                alert('OTP expired!');
                window.location.href='../Pages/resetPass.php';
              </script>";
        exit;
    }

    if(password_verify($otp_input, $hashedOtp)){
        $_SESSION['otp_verified'] = true;
        echo "<script>
                alert('OTP verified! You can now reset password.');
                window.location.href='../Pages/resetPass.php';
              </script>";
        exit;
    }else{
        echo "<script>
                alert('Invalid OTP!');
                window.location.href='../Pages/resetPass.php';
              </script>";
        exit;
    }
}

if($new_password){
    if(!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true){
        die("Unauthorized. Please verify OTP first.");
    }

    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE userdata SET user_password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    unset($_SESSION['resetOTP']);
    unset($_SESSION['otp_verified']);
    unset($_SESSION['resetEmail']);

    echo "<script>
            alert('Password reset successfully!');
            window.location.href='../Pages/reglog.php';
          </script>";
    exit;
}
?>
