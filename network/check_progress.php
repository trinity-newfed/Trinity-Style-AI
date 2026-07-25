<?php
include "host.php";
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';
use Predis\Client as PredisClient;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$taskId = $_GET['task_id'] ?? null;

if (!$taskId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số task_id.']);
    exit;
}

try {
    $redisHost = $_ENV['REDIS_AI_HOST'] ?? 'trinity_redis_ai';
    $redisPort = (int) ($_ENV['REDIS_AI_PORT'] ?? 6379);
    $redisPassword = $_ENV['REDIS_PASSWORD'] ?? null;

    $redisConfig = ['scheme' => 'tcp', 'host' => $redisHost, 'port' => $redisPort];
    if ($redisPassword) {
        $redisConfig['password'] = $redisPassword;
    }

    $redis = new PredisClient($redisConfig);

    $rawStatus = $redis->get("task_status:{$taskId}");

    if (!$rawStatus) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy tiến trình hoặc đã quá hạn.']);
        exit;
    }

    $statusData = json_decode($rawStatus, true);

    echo json_encode([
        'success' => true,
        'data' => [
            'status' => $statusData['status'] ?? 'unknown',
            'progress' => $statusData['progress'] ?? 0,
            'result_url' => $statusData['result_url'] ?? null,
            'message' => $statusData['message'] ?? ''
        ]
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối Redis: ' . $e->getMessage()]);
    exit;
}