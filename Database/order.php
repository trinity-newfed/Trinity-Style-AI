<?php
header('Content-Type: application/json; charset=utf-8');

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

if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$cart_ids = $_SESSION['checkout_cart_ids'] ?? [];
if (empty($cart_ids)) {
    echo "<script>alert('No items selected!'); window.location.href='../Pages/cart.php';</script>";
    exit();
}

$agree = $_POST['policy_id'] ?? null;
$userID = $_SESSION['user_id'] ?? null;
$email = $conn->execute_query("SELECT email FROM userdata WHERE id = ?",[$userID])->fetch_assoc()["email"];
$username = $_SESSION['username'] ?? 'GUEST';
$orderAddress = $_POST['address'] ?? '';

if (!$userID) {
    header("Location: ../Pages/reglog.php");
    exit();
}

function getCoords($add)
{
    $url = "https://photon.komoot.io/api/?q=" . urlencode($add) . "&limit=1";
    $response = @file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if (!empty($data['features'])) {
            return $data['features'][0]['geometry']['coordinates'];
        }
    }
    return null;
}

function getDistance($from, $toCoords)
{
    $url = "https://router.project-osrm.org/route/v1/driving/"
        . $from[0] . "," . $from[1] . ";"
        . $toCoords[0] . "," . $toCoords[1]
        . "?overview=false";

    $response = @file_get_contents($url);
    if ($response) {
        $data = json_decode($response, true);
        if (isset($data['routes'][0]['distance'])) {
            return $data['routes'][0]['distance'] / 1000;
        }
    }
    return null;
}

function getShippingFee($km)
{
    if ($km === null)
        return 25;
    if ($km < 20)
        return 2;
    if ($km < 100)
        return 5;
    if ($km < 1000)
        return 15;
    return 25;
}

//Policy
if ($agree) {
    $policy = $conn->prepare("SELECT 1 FROM user_policy_agreement WHERE user_id = ? AND policy_id = ?");
    $policy->bind_param("is", $userID, $agree);
    $policy->execute();
    if ($policy->get_result()->num_rows == 0) {
        $sql = $conn->prepare("INSERT INTO user_policy_agreement (user_id, policy_id) VALUES (?, ?)");
        $sql->bind_param("is", $userID, $agree);
        $sql->execute();
        $sql->close();
    }
    $policy->close();
}

//Distance
$from = [106.5775, 10.8908];
$shipFee = 25;

$addressStmt = $conn->prepare("SELECT user_address FROM userdata WHERE id = ?");
$addressStmt->bind_param("i", $userID);
$addressStmt->execute();
$userAddressResult = $addressStmt->get_result();

if ($row = $userAddressResult->fetch_assoc()) {
    $add = $row['user_address'];
    $toCoords = getCoords($add);
    if ($toCoords) {
        $km = getDistance($from, $toCoords);
        $shipFee = getShippingFee($km);
    }
}
$addressStmt->close();

$placeholders = implode(',', array_fill(0, count($cart_ids), '?'));
$sql = "SELECT 
            cart.id AS cart_id,
            cart.product_id,
            products.product_name,
            products.product_price,
            products.product_img,
            cart.product_color,
            cart.cart_size,
            cart.quantity
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.id IN ($placeholders) AND cart.user_id = ?";

$stmt = $conn->prepare($sql);
$types = str_repeat('i', count($cart_ids)) . 'i';
$params = array_merge($cart_ids, [$userID]);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total = 0;
foreach ($data as $item) {
    $total += $item['product_price'] * $item['quantity'];
}

$voucher = $_SESSION['voucher_id'] ?? 0;
$discount_amount = 0;
$ship_discount = 0;

if ($voucher > 0) {
    $vsql = $conn->prepare("SELECT voucher_discount, voucher_max, voucher_type FROM vouchers WHERE id = ?");
    $vsql->bind_param("i", $voucher);
    $vsql->execute();
    if ($dis = $vsql->get_result()->fetch_assoc()) {
        $val = $dis['voucher_discount'] ?? 0;
        $voucher_max = $dis['voucher_max'] ?? PHP_INT_MAX;

        if ($dis['voucher_type'] == "shipping") {
            $ship_discount = $val;
        } else {
            $discount_amount = min($total * ($val / 100), $voucher_max);
        }
    }
    $vsql->close();
}

$FREE_SHIP_THRESHOLD = 700;
if ($total >= $FREE_SHIP_THRESHOLD) {
    $shipFee = 0;
    $ship_discount = 0;
}
$final_ship_fee = max(0, $shipFee - $ship_discount);
$final_total = max(0, $total - $discount_amount + $final_ship_fee);

$conn->begin_transaction();

try {
    $usernameShort = strtoupper(substr($username, 0, 3));
    $orderCode = $usernameShort . date('YmdHis');
    $orderstate = "success";

    $stmt = $conn->prepare("
        INSERT INTO orders(user_id, order_name, order_original_price, order_delivery_fee, discount, ship_discount, order_final_price, order_address, order_state)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isdddddss", $userID, $orderCode, $total, $final_ship_fee, $discount_amount, $ship_discount, $final_total, $orderAddress, $orderstate);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    $baseStatus = "success";
    $orderTrack = $conn->execute_query("INSERT INTO order_tracking(user_id, order_name, status_detail) VALUES(?, ?, ?)", [$userID, $orderCode, $baseStatus]);

    $itemStmt = $conn->prepare("
        INSERT INTO order_items (order_id, product_id, product_name, price, img, color, size, quantity)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $checkStockStmt = $conn->prepare("
        SELECT product_stock FROM product_variant 
        WHERE product_id = ? AND product_color = ?
    ");

    $updateStockStmt = $conn->prepare("
        UPDATE product_variant SET product_stock = product_stock - ? 
        WHERE product_id = ? AND product_color = ? AND product_stock >= ?
    ");

    $soldStmt = $conn->prepare("
        INSERT INTO product_sold(`sold`, `product_id`, `product_color`) VALUES(?, ?, ?)
        ON DUPLICATE KEY UPDATE sold = sold + VALUES(sold)
    ");

    foreach ($data as $item) {
        $itemStmt->bind_param(
            "iisdsssi",
            $order_id,
            $item['product_id'],
            $item['product_name'],
            $item['product_price'],
            $item['product_img'],
            $item['product_color'],
            $item['cart_size'],
            $item['quantity']
        );
        $itemStmt->execute();

        $checkStockStmt->bind_param("is", $item['product_id'], $item['product_color']);
        $checkStockStmt->execute();
        $stockRow = $checkStockStmt->get_result()->fetch_assoc();
        $currentStock = $stockRow['product_stock'] ?? 0;

        if ($currentStock >= $item['quantity']) {
            $updateStockStmt->bind_param(
                "iisi",
                $item['quantity'],
                $item['product_id'],
                $item['product_color'],
                $item['quantity']
            );
            $updateStockStmt->execute();

            $soldStmt->bind_param("iis", $item['quantity'], $item['product_id'], $item['product_color']);
            $soldStmt->execute();
        } else {
            throw new Exception("Product '" . ucfirst($item['product_name']) . "' (" . ucfirst($item['product_color']) . " - " . $item['cart_size'] . ") is out of stock!");
        }
    }

    $itemStmt->close();
    $checkStockStmt->close();
    $updateStockStmt->close();
    $soldStmt->close();

    if ($voucher > 0) {
        $usql = $conn->execute_query("INSERT INTO used_voucher(user_id, voucher_id) VALUES(?, ?)", [$userID, $voucher]);

        $vDel = $conn->prepare("DELETE FROM user_voucher WHERE voucher_id = ? AND user_id = ?", [$voucher, $userID]);
    }

    $del = $conn->prepare("DELETE FROM cart WHERE id IN ($placeholders) AND user_id = ?");
    $delParams = array_merge($cart_ids, [$userID]);
    $delTypes = str_repeat('i', count($cart_ids)) . 'i';
    $del->bind_param($delTypes, ...$delParams);
    $del->execute();
    $del->close();

    $tierStmt = $conn->prepare("
        SELECT COUNT(*) AS total_orders, SUM(order_final_price) AS total_spent 
        FROM orders WHERE user_id = ? AND order_state = 'success'
    ");
    $tierStmt->bind_param("i", $userID);
    $tierStmt->execute();
    $orderData = $tierStmt->get_result()->fetch_assoc();
    $totalOrdersCount = $orderData['total_orders'] ?? 0;
    $totalSpent = $orderData['total_spent'] ?? 0;
    $tierStmt->close();

    $newTier = '1';
    if (($totalOrdersCount >= 40 && $totalSpent >= 2500) || $totalSpent >= 5000) {
        $newTier = '4';
    } elseif (($totalOrdersCount >= 25 && $totalSpent >= 1000) || $totalSpent >= 2000) {
        $newTier = '3';
    } elseif (($totalOrdersCount >= 10 && $totalSpent >= 200) || $totalSpent >= 500) {
        $newTier = '2';
    }

    if ($newTier !== '1') {
        $tierUpdate = $conn->prepare("UPDATE userdata SET user_tier = ? WHERE id = ?");
        $tierUpdate->bind_param("si", $newTier, $userID);
        $tierUpdate->execute();
        $tierUpdate->close();
    }

    $conn->commit();

    unset($_SESSION['checkout_cart_ids']);
    unset($_SESSION['voucher_id']);

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];

    $url = $protocol . $host . "/Trinity-Style-AI/Database/orderMail.php";

    $key = "trinitySMTP2026";
    $timestamp = time();
    $tempToken = hash_hmac('sha256', $email . $timestamp, $key);

    $param = [
        'email' => $email,
        'timestamp' => $timestamp,
        'token' => $tempToken,
        'content' => 'Thank you for letting TRINITY be a part of your journey.'
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

    header("Location: ../Pages/user.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    echo "<script>alert('Checkout failed: " . addslashes($e->getMessage()) . "'); window.location.href='../Pages/cart.php';</script>";
    exit();
}
?>