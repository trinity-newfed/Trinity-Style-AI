<?php
require __DIR__ . '/../vendor/autoload.php';

set_time_limit(0);
ob_implicit_flush(true);

function logToStdout($message) {
    $out = fopen('php://stdout', 'w');
    fwrite($out, date('[Y-m-d H:i:s] ') . $message . PHP_EOL);
    fclose($out);
}

if (class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->safeLoad();
}

$redisHost = getenv('REDIS_MAIL_HOST') ?: ($_ENV['REDIS_MAIL_HOST'] ?? 'trinity_redis_mail');
$redisPort = getenv('REDIS_MAIL_PORT') ?: ($_ENV['REDIS_MAIL_PORT'] ?? 6379);

logToStdout("Worker Mail Service Running..");

$redis = null;
while ($redis === null) {
    try {
        $client = new Predis\Client([
            'scheme'             => 'tcp',
            'host'               => $redisHost,
            'port'               => (int)$redisPort,
            'read_write_timeout' => -1,
        ]);
        $client->connect();
        $redis = $client;
        logToStdout("Connect To Redis Success {$redisHost}:{$redisPort}");
    } catch (\Throwable $e) {
        logToStdout("Cannot Connect To Redis ({$e->getMessage()}). Trying To Reconnect...");
        sleep(3);
    }
}

while (true) {
    try {
        $job = $redis->blpop(['smtp_mail_queue'], 0);

        if ($job && isset($job[1])) {
            logToStdout("Job Pending: " . $job[1]);
            $data = json_decode($job[1], true);

            if (!isset($data['url']) || !isset($data['param'])) {
                logToStdout("Skipping Invalid Request.");
                continue;
            }

            $ch = curl_init();
            $targetUrl = $data['url'];
            
            curl_setopt($ch, CURLOPT_URL, $targetUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data['param']));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $isHttps = (strpos($targetUrl, 'https://') === 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $isHttps);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $isHttps ? 2 : 0);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                logToStdout("cURL Error: " . curl_error($ch) . " - Target: " . $targetUrl);
            } else {
                logToStdout("[HTTP $httpCode] Mail push for: " . ($data['param']['email'] ?? 'N/A'));
            }

            curl_close($ch);
        }
    } catch (\Throwable $e) { 
        logToStdout("Worker Loop Error: " . $e->getMessage() . " - Auto Restarting...");
        sleep(3); 
        
        try {
            $redis->ping();
        } catch (\Throwable $ex) {
            logToStdout("Redis Connection Lost. Trying to reconnect...");
            try {
                $redis->connect();
            } catch (\Throwable $conEx) {
                
            }
        }
    }

    gc_collect_cycles();
}