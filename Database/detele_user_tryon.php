<?php
include('host.php');

session_start();

if(isset($_POST['id']) && isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("DELETE FROM tryon WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $_POST['id'], $_SESSION['user_id']);
    $stmt->execute();

if($stmt->execute()){
    header("Location: ../Pages/user.php");
    exit();
}else{
    echo "Delete Failed";
}

$stmt->close();
$conn->close();
}
header("Location: ../Pages/user.php");
exit();
?>