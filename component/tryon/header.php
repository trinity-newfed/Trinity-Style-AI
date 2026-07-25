<?php
include('../Database/host.php');

if ($conn->connect_error) {
    echo json_encode([
        "status" => "failed",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: reglog.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

$productID = $_POST['productID'] ?? null;
$productColor = $_POST['productColor'] ?? null;

if (empty($productID)) {
    header("Location: products.php");
    exit;
}

$productQuery = $conn->execute_query("SELECT 
                    p.id AS product_id,
                    p.product_name, 
                    p.product_group,
                    p.product_price, 
                    p.product_category,
                    p.product_type, 
                    p.product_describe,
                    p.color_display,
                    pv.product_stock,
                    pv.product_img,
                    pv.product_color
                 FROM products p
                 JOIN product_variant pv ON p.id = pv.product_id
                 WHERE p.id = ? AND pv.product_color = ?",[$productID, $productColor])->fetch_assoc();

$variantQuery = $conn->execute_query("SELECT 
                    pv.product_id, 
                    pv.product_price,
                    pv.product_img AS variant_img,
                    pv.product_color AS color, 
                    pv.product_size,
                    pv.product_stock, 
                    p.product_name,
                    p.product_category
                 FROM product_variant pv
                 JOIN products p ON pv.product_id = p.id
            WHERE pv.product_id = ?",[$productID])->fetch_all(MYSQLI_ASSOC);

$otherQuery = $conn->execute_query("SELECT * FROM products WHERE id  != ? AND product_type = ? LIMIT 3",[$productID, $productQuery['product_type']])->fetch_all(MYSQLI_ASSOC);
$alternativeQuery = $conn->execute_query("SELECT * FROM products WHERE id  != ? LIMIT 3",[$productID])->fetch_all(MYSQLI_ASSOC);

$agree = 0;
$sqlPolicy = $conn->prepare("SELECT 1 FROM user_policy_agreement WHERE user_id = ?");
$sqlPolicy->bind_param("i", $userID);
$sqlPolicy->execute();
$agreement = $sqlPolicy->get_result();
if ($agreement->num_rows > 0) {
    $agree = 1;
}
$sqlPolicy->close();

$conn->close();
?>