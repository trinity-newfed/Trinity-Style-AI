<?php
include('host.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] != "adminTan" && $_SESSION['role'] != "adminTrung")) {
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/");
    exit();
}

$conn->begin_transaction();

try {
    $file = fopen("products.csv", "r");
    $variant = fopen("product_variant.csv", "r");

    if (!$file || !$variant) {
        throw new Exception("Cannot open csv.");
    }

    $sql_products = "INSERT INTO products 
        (product_name, product_group, product_price, product_category, product_type, product_describe, color_display, product_img, product_img1, product_img2) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        product_group    = VALUES(product_group),
        product_price    = VALUES(product_price),
        product_type     = VALUES(product_type),
        product_describe = VALUES(product_describe),
        color_display     = VALUES(color_display),
        product_img      = VALUES(product_img),
        product_img1     = VALUES(product_img1),
        product_img2     = VALUES(product_img2)";

    $stmt = $conn->prepare($sql_products);

    while (($data = fgetcsv($file, 0, ",")) !== FALSE) {
        $stmt->bind_param(
            "sdssssssss", 
            $data[0], 
            $data[1],  
            $data[2],
            $data[3],
            $data[4],
            $data[5],
            $data[6],
            $data[7],
            $data[8],
            $data[9]
        );
        $stmt->execute();
    }
    $stmt->close();

    $sql_variants = "INSERT INTO product_variant 
        (product_id, product_price, product_color, product_size, product_img, product_img1, product_img2)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
        product_price = VALUES(product_price),
        product_size  = VALUES(product_size),
        product_img   = VALUES(product_img),
        product_img1  = VALUES(product_img1),
        product_img2  = VALUES(product_img2)";

    $stmt2 = $conn->prepare($sql_variants);

    while (($data = fgetcsv($variant, 1000, ",")) !== FALSE) {

        $p_id = (int)$data[0];
        $p_price = (double)$data[1];

        $stmt2->bind_param(
            "idsssss", 
            $p_id,
            $p_price,
            $data[2],
            $data[3],
            $data[4],
            $data[5],
            $data[6]
        );
        $stmt2->execute();
    }
    $stmt2->close();

    fclose($file);
    fclose($variant);

    $conn->commit();

} catch (Exception $e) {
    $conn->rollback();
    die("Import thất bại: " . $e->getMessage());
}

$conn->close();

header("Location: admin.php");
exit;
?>