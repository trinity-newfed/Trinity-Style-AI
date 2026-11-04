<?php
//PRODUCT FETCH
$product = $conn
    ->query("SELECT 
                products.id AS id,
                products.product_name, 
                products.product_is_delete,
                products.product_state,

                product_variant.id AS variant_id,
                product_variant.product_img as product_img,
                product_variant.product_price, 
                product_variant.product_color,
                product_variant.product_size,
                product_variant.product_stock

             FROM products
             INNER JOIN product_variant ON products.id = product_variant.product_id
             WHERE products.product_is_delete = 0 
               AND product_variant.product_is_delete = 0")
    ->fetch_all(MYSQLI_ASSOC);

//USER FETCH
$userdata = $conn
    ->query("SELECT * FROM userdata")
    ->fetch_all(MYSQLI_ASSOC);

//VOUCHER FETCH
$voucher = $conn
    ->query("SELECT * FROM vouchers")
    ->fetch_all(MYSQLI_ASSOC);

//ORDER FETCH
$order = $conn->query("SELECT 
            orders.id,
            order_items.order_id,
            orders.order_state,
            orders.order_name,
            orders.order_original_price,
            orders.order_final_price,
            orders.order_delivery_fee,
            orders.created_at,
            order_items.product_name,    
            order_items.quantity AS order_quantity,
            product_variant.product_img AS img
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        LEFT JOIN product_variant ON (
            order_items.product_id = product_variant.product_id 
            AND LOWER(TRIM(order_items.color)) = LOWER(TRIM(product_variant.product_color)))"
        )->fetch_all(MYSQLI_ASSOC);
?>