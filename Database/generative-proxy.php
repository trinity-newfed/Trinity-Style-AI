<?php
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as PredisClient;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

session_start();

$redisHost = $_ENV['REDIS_AI_SERVICE_HOST'] ?? $_ENV['REDIS_HOST'] ?? '127.0.0.1';
$redisPort = (int) ($_ENV['REDIS_PORT'] ?? 6379);

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

if (!$userId && !$authToken) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Phiên làm việc không hợp lệ hoặc đã hết hạn.']);
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
                'message' => 'Bạn đang có một yêu cầu thử đồ đang xử lý. Vui lòng chờ hoàn thành trước khi thử tiếp!',
                'active_task_id' => $activeTaskId,
                'status' => $status
            ]);
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Phương thức không được hỗ trợ.']);
    exit;
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn hình ảnh hợp lệ để tải lên.']);
    exit;
}

$file = $_FILES['image'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
$maxFileSize = 10 * 1024 * 1024;

if (!in_array($file['type'], $allowedTypes)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Định dạng file không hỗ trợ (Chỉ chấp nhận JPG, PNG, WEBP).']);
    exit;
}

if ($file['size'] > $maxFileSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dung lượng ảnh vượt quá 10MB.']);
    exit;
}

$rateKey = "rate_limit:ai_tryon:" . $clientIdentifier;
$maxRequestsPerMinute = 5;
$currentRequests = $redis->get($rateKey);

if ($currentRequests && $currentRequests >= $maxRequestsPerMinute) {
    http_response_code(429);
    echo json_encode([
        'success' => false, 
        'message' => 'Bạn đã đạt giới hạn thử đồ (' . $maxRequestsPerMinute . ' lần/phút). Vui lòng đợi giây lát!'
    ]);
    exit;
}

$redis->multi();
$redis->incr($rateKey);
$redis->expire($rateKey, 60);
$redis->exec();

$taskId = "task_" . bin2hex(random_bytes(16));
$tempPath = $file['tmp_name'];
$imageData = base64_encode(file_get_contents($tempPath));

$color = $_POST['color'] ?? 'white';
$productId = $_POST['product_id'] ?? 1;

$taskPayload = [
    'task_id' => $taskId,
    'user_id' => $userId,
    'image_base64' => $imageData,
    'color' => $color,
    'product_id' => $productId,
    'created_at' => time()
];

$redis->setex("task_status:{$taskId}", 600, json_encode([
    'status' => 'pending',
    'progress' => 0,
    'message' => 'Đang chờ máy chủ AI tiếp nhận...'
]));

$redis->setex($userActiveKey, 300, $taskId);

$redis->lPush('ai_tryon_queue', json_encode($taskPayload));

http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => 'Khởi tạo tiến trình AI thành công.',
    'task_id' => $taskId
]);
exit;