<?php
session_set_cookie_params(['path' => '/']);
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

ignore_user_abort(true);
set_time_limit(120);

$email = $_POST['email'] ?? '';
$timestamp = $_POST['timestamp'] ?? '';
$token = $_POST['token'] ?? '';
$key = "trinitySMTP2026";
$content = $_POST['content'];

$tokenCheck = hash_hmac('sha256', $email . $timestamp, $key);

if (hash_equals($tokenCheck, $token)) {
    echo "Xác thực token thành công!";
} else {
    die("Mã xác thực không chính xác.");
}

$userResult = $conn->execute_query("SELECT id FROM userdata WHERE email = ?", [$email])->fetch_assoc();
$userID = $userResult["id"] ?? null;

$order = $conn->execute_query("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT 1", [$userID])->fetch_assoc();

function sendOrderConfirmationEmail($targetEmail, $order, $conn)
{
    if (!$order || !isset($order['id'])) {
        return false;
    }

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('triple3tbusiness@gmail.com', 'TRINITY ARCHIVE');
        $mail->addAddress($targetEmail);

        $mail->isHTML(true);
        $mail->Subject = "[TRINITY ARCHIVE] - Order Confirmed #{$order['order_name']}";

        $items = $conn->execute_query("SELECT 
                                    order_items.product_id, 
                                    order_items.price,
                                    order_items.color, 
                                    order_items.size,
                                    order_items.quantity, 
                                    order_items.product_name,
                                    product_variant.product_img
                                    FROM order_items 
                                    LEFT JOIN product_variant ON (
                                        order_items.product_id = product_variant.product_id 
                                        AND LOWER(TRIM(order_items.color)) = LOWER(TRIM(product_variant.product_color))
                                    )
                                    WHERE order_items.order_id = ?", [$order["id"]]);


        $orderProducts = [];
        while ($item = $items->fetch_assoc()) {
            $orderProducts[] = $item;
        }

        if (empty($orderProducts)) {
            return false;
        }

        $itemsHtml = '';
        $uniqueImages = [];
        $imgCounter = 0;

        foreach ($orderProducts as $product) {
            $formattedPrice = number_format($product['price'], 0, ',', '.') . '$';
            $attributesArray = array_filter([$product['color'], $product['size']]);
            $attributes = implode(' / ', $attributesArray);

            $imageTagHtml = '';

            if (!empty($product['product_img'])) {
                $cleanImgName = ltrim($product['product_img'], '/\\');

                $imagePath = __DIR__ . '/../' . $cleanImgName;

                if (file_exists($imagePath)) {
                    if (!isset($uniqueImages[$imagePath])) {
                        $imgCounter++;
                        $cidName = "prod_img_" . $imgCounter;

                        $mail->addEmbeddedImage($imagePath, $cidName, basename($imagePath));
                        $uniqueImages[$imagePath] = $cidName;
                    }

                    $currentCid = $uniqueImages[$imagePath];
                    $imageTagHtml = "
                    <td width='70' style='vertical-align: middle; padding-right: 12px;'>
                        <img src='cid:{$currentCid}' alt='Product' width='60' height='60' style='background: #f5f5f4; display: block; object-fit: cover; border-radius: 4px; border: 1px solid #eaebec;' />
                    </td>";
                } else {
                    echo "PHP đang tìm ảnh tại: " . $imagePath . "<br>";
                }
            }

            if (empty($imageTagHtml)) {
                $imageTagHtml = "
                <td width='70' style='vertical-align: middle; padding-right: 12px;'>
                    <div style='width: 60px; height: 60px; background: #f5f5f4; border: 1px dashed #d6d3d1; border-radius: 4px; text-align: center; line-height: 60px; font-size: 10px; color: #a8a29e;'>No Img</div>
                </td>";
            }

            $itemsHtml .= "
            <table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%' style='margin-bottom: 16px; border-bottom: 1px solid #f5f5f4; padding-bottom: 16px;'>
                <tr>
                    {$imageTagHtml}
                    <td style='vertical-align: middle; font-family: Arial, sans-serif;'>
                        <div style='font-size: 13px; font-weight: bold; color: #1c1917; line-height: 1.4;'>
                            {$product['product_name']} &times; {$product['quantity']}
                        </div>
                        <div style='font-size: 11px; color: #78716c; margin-top: 2px;'>
                            {$attributes}
                        </div>
                    </td>
                    <td align='right' style='vertical-align: middle; white-space: nowrap; font-size: 13px; color: #1c1917; font-family: Arial, sans-serif; padding-left: 12px;'>
                        {$formattedPrice}
                    </td>
                </tr>
            </table>";
        }

        $shipping = number_format($order['order_delivery_fee'] ?? 0, 0, ',', '.') . '$';
        $total = number_format($order['order_final_price'] ?? 0, 0, ',', '.') . '$';
        $currentYear = date("Y");

        $mail->Body = "
        <div style='margin:0; padding:0; background-color:#f9f9f9;'>
            <div style='max-width:480px; margin:40px auto; background:#ffffff; border:1px solid #eaebec; padding:40px 32px; font-family:Arial, sans-serif; color:#1c1917;'>
                <div style='text-align:center; margin-bottom:32px;'>
                    <div style='font-size:18px; font-weight:300; letter-spacing:4px; text-transform:uppercase;'>TRINITY ARCHIVE</div>
                </div>
                <div style='margin-bottom: 24px;'>
                    <span style='font-size: 11px; color: #78716c; text-transform: uppercase; letter-spacing: 1px;'>ORDER #{$order['order_name']}</span>
                    <h2 style='font-size: 20px; font-weight: normal; margin: 8px 0 12px 0; color: #000000;'>Thank you for letting TRINITY be a part of your journey.</h2>
                    <p style='font-size: 13px; line-height: 1.6; color: #44403c; margin: 0;'>
                        Hi, TRINITY has successfully received your order details. Our team will review and process your shipment shortly.
                    </p>
                </div>
                <div style='border-top: 1px solid #eaebec; padding-top: 24px; margin-top: 24px;'>
                    <h3 style='font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;'>Items Ordered</h3>
                    {$itemsHtml}
                </div>
                <div style='border-top: 1px solid #f5f5f4; margin-top: 16px; padding-top: 16px; font-size: 13px; color: #44403c;'>
                    <table role='presentation' cellspacing='0' cellpadding='0' border='0' width='100%'>
                        <tr>
                            <td style='font-size: 13px; color: #44403c; padding-bottom: 12px;'>Delivery Fee:</td>
                            <td align='right' style='font-size: 13px; color: #1c1917; padding-bottom: 12px;'>{$shipping}</td>
                        </tr>
                        <tr>
                            <td style='font-size: 15px; font-weight: bold; color: #000000; border-top: 1px dashed #eaebec; padding-top: 16px;'>Total Amount:</td>
                            <td align='right' style='font-size: 15px; font-weight: bold; color: #000000; border-top: 1px dashed #eaebec; padding-top: 16px;'>{$total}</td>
                        </tr>
                    </table>
                </div>
                <div style='text-align:center; font-size:10px; color:#a8a29e; margin-top:40px; border-top:1px solid #f5f5f4; padding-top: 20px; letter-spacing:1px;'>
                    &copy; {$currentYear} TRINITY ARCHIVE GLOBAL
                </div>
            </div>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

sendOrderConfirmationEmail($email, $order, $conn);
?>