<?php 
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0); 
error_reporting(E_ALL);

include('host.php');

try{
    $conn = new mysqli($host, $user, $password, $dbname);

    if($conn->connect_error){
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $hotline = isset($_POST['hotline']) ? trim($_POST['hotline']) : '';
    $more = isset($_POST['more']) ? trim($_POST['more']) : '';

    if(empty($email)){
        throw new Exception("Email required fields.");
    }

    $sql = $conn->execute_query("SELECT * FROM contact WHERE email = ?", [$email]);
    
    if($sql->num_rows > 0){
        throw new Exception("This email has already submitted a contact request.");
    }else{
        $conn->execute_query(
            "INSERT INTO contact (email, name, address, hotline, more) VALUES (?, ?, ?, ?, ?)",
            [$email, $name, $address, $hotline, $more]
        );
    }

    if($conn->affected_rows === 0){
        throw new Exception("Failed to insert contact data, please try again later.");
    }else{
        echo json_encode([
            "status" => "success",
            "message" => "Thanks for contacting us."
        ]);
    }

}catch(Exception $e){
    echo json_encode([
        "status" => "failed",
        "message" => $e->getMessage()
    ]);
}

$conn->close();
exit();
?>