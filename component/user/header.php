<?php 
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
session_start();
if(!isset($_SESSION['user_id'])){
  header("Location: reglog.php");
  exit();
}
$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];

$sql = "SELECT * FROM userdata
        WHERE id = ?";
$stmt = $conn->prepare($sql);
if(!$stmt){
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$sql = "SELECT 
        orders.id,
        order_items.order_id,
        orders.order_state,
        orders.order_name,
        orders.order_original_price,
        orders.order_final_price,
        orders.created_at,
        order_items.product_name,
        order_items.img,    
        order_items.quantity
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        WHERE orders.user_id = ?
        ORDER BY orders.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);
$groupedOrders = [];
$count = 0;
foreach($data as $d){
    $orderID = $d['id'];
    
    if (!isset($groupedOrders[$orderID])) {
        $groupedOrders[$orderID] = [
            'order_info' => $d,
            'total_items' => 0
        ];
    }
    

    $groupedOrders[$orderID]['total_items'] += $d['quantity'];
}
?>