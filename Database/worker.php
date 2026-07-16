<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

set_time_limit(0);

$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
    'read_write_timeout' => 0
]);

echo "Worker dang tuc truc cho don hang gui mail...\n";

while (true) {
    try {
        $job = $redis->blpop('smtp_mail_queue', 0);

        if ($job) {
            $data = json_decode($job[1], true);


            if (!isset($data['url']) || !isset($data['param'])) {
                echo "Du lieu Job khong hop le, bo qua.\n";
                continue;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $data['url']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data['param']));
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            $response = curl_exec($ch);

            if ($response === false) {
                echo "cURL Error: " . curl_error($ch) . "\n";
            } else {
                echo "Da xu ly xong 1 mail ngam cho: " . $data['url'] . "\n";
            }

            curl_close($ch);
        }
    } catch (Exception $e) {
        echo "Loi he thong: " . $e->getMessage() . "\n";
        sleep(2);
    }
}
?>