<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

$conn = new mysqli("localhost", "root", "", "TF_Database");
if ($conn->connect_error) {
    die(json_encode(['status' => false, 'message' => 'Connect error']));
}

$userID = $_SESSION['user_id'] ?? null;
if (!$userID) {
    echo json_encode(['status' => false, 'message' => 'Unauthorized']);
    exit;
}

$uploadDir = __DIR__ . "/../upload/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$res_current = $conn->execute_query("SELECT user_sex, user_hotline, user_address, img FROM userdata WHERE id = ?", [$userID]);
$current_data = $res_current->fetch_assoc();

$sex = !empty($_POST['user_sex']) ? $_POST['user_sex'] : $current_data['user_sex'];
$hotline = !empty($_POST['user_hotline']) ? $_POST['user_hotline'] : $current_data['user_hotline'];
$address = !empty($_POST['user_address']) ? $_POST['user_address'] : $current_data['user_address'];
$newPath = $current_data['img'];

if (!empty($_FILES['img']['name'])) {
    $extension = strtolower(pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION));
    $allowed = ["jpg", "jpeg", "png", "gif", "webp"];

    if (in_array($extension, $allowed)) {
        $newName = uniqid('img_', true) . '.' . $extension;
        $destPath = $uploadDir . $newName;

        if (move_uploaded_file($_FILES["img"]["tmp_name"], $destPath)) {
            if ($current_data['img'] && file_exists(__DIR__ . "/../" . $current_data['img'])) {
                unlink(__DIR__ . "/../" . $current_data['img']);
            }
            $newPath = "upload/" . $newName;
        } else {
            echo json_encode(['status' => false, 'message' => 'Upload failed']);
            exit;
        }
    } else {
        echo json_encode(['status' => false, 'message' => 'Invalid file type']);
        exit;
    }
}

$sql = $conn->prepare("UPDATE userdata SET user_sex=?, user_hotline=?, user_address=?, img=? WHERE id=?");
$sql->bind_param("ssssi", $sex, $hotline, $address, $newPath, $userID);

if ($sql->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Information Update Successfully.']);
} else {
    echo json_encode(['status' => false, 'message' => 'Database error']);
}

$sql->close();
$conn->close();
?>