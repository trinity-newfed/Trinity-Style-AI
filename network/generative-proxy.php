<?php
include "../Database/host.php";
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as PredisClient;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();

$clientToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
$sessionToken = $_SESSION['csrf_token'] ?? null;

if (!$clientToken || !$sessionToken || !hash_equals($sessionToken, $clientToken)) {
    http_response_code(403);
    echo json_encode([
        'success' => false, 
        'message' => 'Security validation failed: Request unauthorized.'
    ]);
    exit;
}

$redisHost = $_ENV['REDIS_AI_HOST'] ?? 'trinity_redis_ai';
$redisPort = (int) ($_ENV['REDIS_AI_PORT'] ?? 6379);

try {
    $redis = new PredisClient([
        'scheme' => 'tcp',
        'host'   => $redisHost,
        'port'   => $redisPort,
    ]);
    $redis->ping();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối Redis: ' . $e->getMessage()]);
    exit;
}

$userId = $_SESSION['user_id'] ?? null;
$authToken = $_SERVER['HTTP_AUTHORIZATION'] ?? $_POST['token'] ?? null;

if ($userId) {
    $row = $conn->execute_query("SELECT next_limit_reset FROM userdata WHERE id = ?", [$userId])->fetch_assoc();
    $userLimit = $row['next_limit_reset'] ?? null;

    $message = "You reached Virtual Try On for today, Your limit reset at: {$userLimit}";
    if ($userLimit !== null && strtotime($userLimit) > time()) {
        echo json_encode([
            'success' => false, 
            'message' => $message
        ]);
        exit;
    }
    else {
        $limit = $conn->execute_query("SELECT user_limit_tryon FROM userdata WHERE id = ?",[$userId])->fetch_assoc();
        $totalLimit = intval($limit['user_limit_tryon']);

        if ($limit) {
            $conn->execute_query("UPDATE userdata SET next_limit_reset = null, user_limit_tryon_daily = ? WHERE id = ?",[$totalLimit, $userId]);
        }
    }
}

if (!$userId && !$authToken) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Session expired.']);
    exit;
}

$clientIdentifier = $userId ?? $_SERVER['REMOTE_ADDR'];

$userActiveKey = "user_active_task:{$clientIdentifier}";
$activeTaskId = $redis->get($userActiveKey);

if ($activeTaskId) {
    $rawStatus = $redis->get("task_status:{$activeTaskId}");
    if ($rawStatus) {
        $statusData = json_decode($rawStatus, true);
        $status = $statusData['status'] ?? 'pending';

        if (in_array($status, ['pending', 'processing'])) {
            http_response_code(409);
            echo json_encode([
                'success' => false,
                'message' => 'Your task is already processing, Please try again after your previous task done!',
                'active_task_id' => $activeTaskId,
                'status' => $status
            ]);
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'This method is not support']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please upload your image']);
    exit;
}

$file = $_FILES['image'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
$maxFileSize = 10 * 1024 * 1024;

if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'File not support (JPG, PNG, WEBP only).']);
    exit;
}

if ($file['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Image size exceed 10MB.']);
    exit;
}

$rateKey = "rate_limit:ai_tryon:" . $clientIdentifier;
$maxRequestsPerMinute = 5;
$currentRequests = $redis->get($rateKey);

if ($currentRequests && $currentRequests >= $maxRequestsPerMinute) {
    http_response_code(429);
    echo json_encode([
        'success' => false, 
        'message' => 'Max tryon limit reached(' . $maxRequestsPerMinute . ' limit/30 seconds). Please try again later!'
    ]);
    exit;
}

$redis->multi();
$redis->incr($rateKey);
$redis->expire($rateKey, 30);
$redis->exec();

$taskId = "task_" . bin2hex(random_bytes(16));
$tempPath = $file['tmp_name'];
$imageData = base64_encode(file_get_contents($tempPath));

$color = $_POST['color'] ?? 'white';
$productId = $_POST['product_id'] ?? 1;
$productImg = $conn->execute_query("SELECT product_variant.product_img 
                FROM products 
                JOIN product_variant ON products.id = product_variant.product_id 
                WHERE products.id = ? AND product_variant.product_color = ?", [$productId, $color])->fetch_assoc()['product_img'];

$taskPayload = [
    'task_id' => $taskId,
    'user_id' => $userId,
    'image_base64' => $imageData,
    'color' => $color,
    'product_id' => $productId,
    'product_img' => $productImg,
    'created_at' => time()
];

$redis->setex("task_status:{$taskId}", 600, json_encode([
    'status' => 'pending',
    'progress' => 0,
    'owner' => $clientIdentifier,
    'message' => 'Waiting for AI server...'
]));

$redis->setex($userActiveKey, 300, $taskId);

$redis->hSet('ai_pending_tasks', $taskId, json_encode($taskPayload));

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'AI process initiated successfully.',
    'task_id' => $taskId
]);
exit;
?>