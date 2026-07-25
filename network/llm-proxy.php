<?php
include "../Database/host.php";
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';

use Predis\Client as PredisClient;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$last_request = $_SESSION['last_request_time'] ?? 0;
if (time() - $last_request < 2) {
    http_response_code(429);
    echo json_encode(["error" => "Too many requests. Please wait."]);
    exit;
}
$_SESSION['last_request_time'] = time();
$redisHost = $_ENV['REDIS_AI_HOST'] ?? 'trinity_redis_ai';
$redisPort = (int) ($_ENV['REDIS_AI_PORT'] ?? 6379);

try {
    error_log("DEBUG: Bắt đầu kết nối tới Redis host: $redisHost:$redisPort");
    
    $redis = new PredisClient([
        'scheme' => 'tcp',
        'host'   => $redisHost,
        'port'   => $redisPort,
    ]);
    
    $redis->ping();
    error_log("DEBUG: Ping Redis thành công!");

    $task_id = bin2hex(random_bytes(16)); 
    $redis_key = "chat-ai-pending:" . $task_id;
    $payload = ["user_id" => $_SESSION['user_id']];
    
    error_log("DEBUG: Đang chuẩn bị SETEX với key: $redis_key");
    
    $result = $redis->setex($redis_key, 300, json_encode($payload));
    error_log("DEBUG: Lệnh SETEX trả về: " . ($result ? "OK" : "FALSE"));

    echo json_encode([
        "status" => "success",
        "task_id" => $task_id,
        "debug_connection" => [
            "host_detected" => $redisHost,
            "port_detected" => $redisPort,
            "task_id" => $task_id
        ],
        "fastapi_url" => "http://localhost:5000"
    ]);

    exit;

} catch (Exception $e) {
    error_log("DEBUG: LỖI REDIS: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["error" => "Service unavailable: " . $e->getMessage()]);
}
?>