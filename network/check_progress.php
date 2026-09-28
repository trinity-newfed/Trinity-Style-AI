<?php
error_reporting(0);
ini_set('display_errors', 0);

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
    echo json_encode(['success' => false, 'message' => 'Task id required!']);
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
        http_response_code(200); 
        echo json_encode([
            'success' => true,
            'data' => [
                'status' => 'pending',
                'progress' => 0,
                'message' => 'Waiting...'
            ]
        ]);
        exit;
    }

    $statusData = json_decode($rawStatus, true);

    echo json_encode([
        'success' => true,
        'data' => [
            'status' => $statusData['status'] ?? 'pending',
            'progress' => $statusData['progress'] ?? 0,
            'result_url' => $statusData['result_url'] ?? null,
            'message' => $statusData['message'] ?? ''
        ]
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Redis Error: ' . $e->getMessage()]);
    exit;
}