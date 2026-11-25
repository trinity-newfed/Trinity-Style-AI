<?php
include('host.php');

if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? '';
if (!in_array($role, ['adminTan', 'adminTrung'], true)) {
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method not allowed.");
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit("Invalid order ID.");
}

$orderState = trim((string) ($_POST['order_state'] ?? ''));
$address = trim((string) ($_POST['order_address'] ?? ''));

if ($orderState === '' && $address === '') {
    exit("No changes made.");
}

$orderResult = $conn->execute_query(
    "SELECT user_id, order_state, order_address FROM orders WHERE id = ?",
    [$id]
);
$order = $orderResult ? $orderResult->fetch_assoc() : null;

if (!$order) {
    http_response_code(404);
    exit("Order not found.");
}

$userID = $order['user_id'];
$currentState = (string) ($order['order_state'] ?? '');
$currentAddress = (string) ($order['order_address'] ?? '');

$isStateChanged = ($orderState !== '' && $orderState !== $currentState);
$isAddressChanged = ($address !== '' && $address !== $currentAddress);

if (!$isStateChanged && !$isAddressChanged) {
    echo "<script>
            confirm('No changes made!!');
            window.location.href='orderpage.php?id=$id';
          </script>";
}

$updateFields = [];
$updateParams = [];

if ($isStateChanged) {
    $updateFields[] = "order_state = ?";
    $updateParams[] = $orderState;
}

if ($isAddressChanged) {
    $updateFields[] = "order_address = ?";
    $updateParams[] = $address;
}

$conn->begin_transaction();

try {
    if ($isStateChanged) {
        $conn->execute_query(
            "INSERT INTO order_tracking(order_id, user_id, order_state) VALUES(?, ?, ?)",
            [$id, $userID, $orderState]
        );
    }

    if (!empty($updateFields)) {
        $updateParams[] = $id;
        $sql = "UPDATE orders SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $conn->execute_query($sql, $updateParams);
    }

    $conn->commit();
    echo "<script>
            alert('Update successfully!');
            window.location.href='orderpage.php?id=$id';
          </script>";
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    exit("Failed to update order.");
}