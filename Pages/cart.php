<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

//PRODUCT SEARCH
$product = $conn
  ->query("SELECT products.id AS id,
            products.product_name, products.product_group,
            products.product_price, products.product_category,
            products.product_type, products.product_describe,
            products.product_size, 
            
            product_variant.product_price, product_variant.product_id AS variant_id,
            product_variant.product_size, product_variant.product_img, 
            product_variant.product_color

            FROM products
            JOIN product_variant
            ON products.id = product_variant.product_id
            ")
  ->fetch_all(MYSQLI_ASSOC);

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);

//VOUNCHER FETCH
if(isset($_SESSION['user_id'])){

    $userID = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT
    vouchers.id AS id,
    vouchers.voucher_condition,
    vouchers.voucher_discount,
    vouchers.voucher_type,  
    vouchers.voucher_max,
    user_voucher.voucher_id
FROM vouchers
JOIN user_voucher
    ON vouchers.id = user_voucher.voucher_id
WHERE user_id = ?
");

    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $voucher = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}else{
    $voucher = [];
}



//CART FETCH
if(isset($_SESSION['user_id'])){

    $userID = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT 
    cart.id AS cart_id,
    cart.product_id,
    products.product_name,
    products.product_price,
    products.product_category,
    products.product_state,
    products.product_is_delete,
    cart.cart_size,
    cart.quantity,
    
    product_variant.product_color AS variant_color,
    product_variant.product_img AS variant_img,
    product_variant.product_stock AS variant_stock
    
    FROM cart
    JOIN products 
    ON cart.product_id = products.id
    JOIN product_variant 
    ON cart.product_id = product_variant.product_id 
    AND cart.product_color = product_variant.product_color
    WHERE cart.user_id = ?
");

$stmt->bind_param("i", $userID);
$stmt->execute();
$data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);



} else {
    $data = [];
}
$address = 0;
if(isset($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
    $distance = $conn->prepare("SELECT user_address FROM userdata
                            WHERE id = ?");
    $distance->bind_param("i", $userId);
    $distance->execute();
    $userAddress = $distance->get_result();

    if($userAddress->num_rows > 0){
        $row = $userAddress->fetch_assoc();
        $address = $row['user_address'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Css/nav.css">
    <link rel="stylesheet" href="../Css/cart.css">
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <title>Trinity Style - Cart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Birthstone&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&family=Playwrite+NO:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>
    <section id="body">

        <!--Cart shopping header-->
        <div id="cart-header">

            <span>Shopping Cart</span>

        </div>

        <!--Items container-->
        <div id="cart-item-container">
            <!--Item list-->
            <div id="item-list">
                <?php foreach($data as $item): 
                    $active = ($item['product_is_delete'] == 0 && $item['product_state'] == "active") ? 1 : 0;
                ?>
                    <div class="cartItem relative" data-stock=<?=$item['variant_stock']?> data-id="<?=$item['cart_id']?>" data-active="<?=$active?>">

                        <div class="item-left">
                            <div class="item-img-container relative">
                                <span class="notice absolute w-[100%] text-red-700 hidden text-center z-[100] top-[50%] left-[50%] translate-y-[-50%] translate-x-[-50%]">OUT OF STOCK</span>
                                <img src="../<?=$item['variant_img']?>" alt="">
                            </div>

                            <div class="item-info-container">
                                <span class="item-name"><?=$item['product_name']?></span>
                                <span class="items-price-container" data-price="<?=$item['product_price']?>"></span>
                                
                                <!--Item selection-->
                                <div class="item-option">
                                    <span class="hidden">COLOR</span>
                                    
                                    <div class="flex gap-[5px] w-fit md:w-[100%]">
                                        <div class="colorContainer">
                                            <div class="color" style="background: <?=$item['variant_color']?>"></div>

                                            <select name="cart_color" class="cartColor" data-id="<?=$item['cart_id']?>">
                                                <option value="<?=$item['variant_color']?>" data-img="../<?=$item['variant_img']?>"><?=ucfirst($item['variant_color'])?></option>

                                                <?php foreach($product as $variant): ?>
                                                    <?php 
                                                        if ($variant['variant_id'] != $item['product_id']) continue; 
                                                        if ($variant['product_color'] == $item['variant_color']) continue;
                                                    ?>
                                                        <option value="<?=$variant['product_color']?>" 
                                                            data-img="../<?=$variant['product_img']?>">
                                                            <?=ucfirst($variant['product_color'])?>
                                                        </option>
                                                <?php endforeach; ?>
                                            
                                            </select>
                                        </div>

                                        <div class="sizeContainer">
                                            <select name="cart_size" class="cartSize" data-id="<?=$item['cart_id']?>">
                                                <?php $sizes = ["S", "M", "L", "XL"];
                                                  $currentSize = $item['cart_size'];
                                                  foreach($sizes as $size):
                                                  $selected = ($size === $currentSize) ? 'selected' : '';

                                                ?>

                                                <option value="<?=$size?>" <?=$selected?>><?=$size?></option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!--Item quantity-->
                                <div id="items-quantity-container">
                                    <span>QUANTITY</span>
                                    <div>
                                        <button style="cursor: pointer;" type="button" id="minus-input" class="operation-button" data-id="<?=$item['cart_id']?>" data-action="minus">
                                            <svg class="icon operate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32z"/></svg>
                                        </button>
                                    
                                        <span class="item-quantity" style="font-weight: 550;"><?=$item['quantity']?></span>

                                        <button style="cursor: pointer;" type="button" id="plus-input" class="operation-button" data-id="<?=$item['cart_id']?>" data-action="plus">
                                            <svg class="icon operate" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 160-160 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32l0-160 160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0 0-160z"/></svg>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="item-right">
                            <div class="deleteItem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                    <path d="M55.1 73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L147.2 256 9.9 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192.5 301.3 329.9 438.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.8 256 375.1 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192.5 210.7 55.1 73.4z"/>
                                </svg>
                            </div>

                            <div class="items-total-container"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!--Item information-->
            <div id="info-list">
                <div id="info-total-order">
                    <div class="info-total-order-span-container voucher">
                        <span>Voucher</span>
                        <?php if(empty($voucher)): ?>
                        <span>No Voucher</span>
                        <?php else: ?>
                        <input type="hidden" id="id" name="id" value="">
                        <div id="voucher-select" style="cursor: pointer;">
                            <div value="0" id="main-voucher" data-condition="0" data-max="0">No Selection</div>
                            <div class="voucher-list" value="0" data-condition="0" data-max="0">
                                <div class="voucher">No Selection</div>
                                <?php foreach($voucher as $v): ?>
                                <?php if($v['voucher_type'] == "order"): ?>
                                    <div class="voucher order" value="<?=$v['id']?>"
                                                        data-condition="<?=$v['voucher_condition']?>"
                                                        data-max="<?=$v['voucher_max']?>"
                                                        data-id="<?=$v['id']?>"
                                                        data-discount="<?=$v['voucher_discount']?>"
                                                        data-ship="0">Sale <?=$v['voucher_discount']?>% | Max <?=$v['voucher_max']?>$
                                    </div>
                                <?php elseif($v['voucher_type'] == "shipping"): ?>
                                    <?php if($v['voucher_discount'] == 25): ?>
                                        <div class="voucher free" value="<?=$v['id']?>"
                                                        data-condition="<?=$v['voucher_condition']?>"
                                                        data-max="<?=$v['voucher_max']?>"
                                                        data-id="<?=$v['id']?>"
                                                        data-discount="<?=$v['voucher_discount']?>"
                                                        data-ship="1"><svg class="shipping icon" viewBox="0 0 640 512" aria-hidden="true">
                                                                        <path d="M64 96c0-35.3 28.7-64 64-64h288c35.3 0 64 28.7 64 64v32h50.7c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3V384c0 35.3-28.7 64-64 64h-3.3c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64H300.7c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64H128c-35.3 0-64-28.7-64-64v-48H24c-13.3 0-24-10.7-24-24s10.7-24 24-24h112c13.3 0 24-10.7 24-24s-10.7-24-24-24H24c-13.3 0-24-10.7-24-24s10.7-24 24-24h176c13.3 0 24-10.7 24-24s-10.7-24-24-24H24c-13.3 0-24-10.7-24-24S10.7 96 24 96h40zm512 192v-50.7l-45.3-45.3H480v96h96zM256 424a40 40 0 1 0-80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/>
                                                                      </svg>
                                                                      Free Ship
                                        </div>
                                    <?php else: ?>
                                        <div class="voucher ship" value="<?=$v['id']?>"
                                                        data-condition="<?=$v['voucher_condition']?>"
                                                        data-max="<?=$v['voucher_max']?>"
                                                        data-id="<?=$v['id']?>"
                                                        data-discount="<?=$v['voucher_discount']?>"
                                                        data-ship="1">$<?=$v['voucher_discount']?> Ship OFF
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="info-total-order-span-container delivery">
                        <span>Delivery fee</span>
                        <span id="deli-fee" style="text-align: center;"></span>
                    </div>

                    <div class="info-total-order-span-container total">
                        <span>Grand Total</span>
                        <?php if(empty($data)): ?>
                        <span id="final-total">$0</span>
                        <?php else: ?>
                        <span id="final-total">$0</span>
                        <?php endif; ?>
                    </div> 
                          
                    <button type="submit" id="order-btn">Purchase</button>
                </div>
            </div>

        </div>
    </section>

    <section id="menu">
        <input type="checkbox" id="menu-toggle" hidden>
        <label class="hamburger" for="menu-toggle">
            <svg viewBox="0 0 32 32">
                <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                <path class="line" d="M7 16 27 16"></path>
            </svg>
        </label>

        <div id="text-menu">
            
            <div id="text">
                <span onclick="window.location.href='../Pages/'">Home</span>
                <span onclick="window.location.href='products.php?#product-section'">Shop</span>
                <span onclick="window.location.href='products.php?#product-section'">Collection</span>
                <span onclick="window.location.href='contact.php'">Contact</span>
            </div>

            <div id="logo" onclick="window.location.href='../Pages/'">TRINITY</div>
        </div>
        
        <div id="utility-menu">
            <svg class="icon cart" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="21px" onclick="window.location.href='cart.php'">
                <path d="M200-80q-33 0-56.5-23.5T120-160v-480q0-33 23.5-56.5T200-720h80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720h80q33 0 56.5 23.5T840-640v480q0 33-23.5 56.5T760-80H200Zm0-80h560v-480H200v480Zm421.5-298.5Q680-517 680-600h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85h-80q0 83 58.5 141.5T480-400q83 0 141.5-58.5ZM360-720h240q0-50-35-85t-85-35q-50 0-85 35t-35 85ZM200-160v-480 480Z"/>
            </svg>

            <svg class="icon search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376C296.3 401.1 253.9 416 208 416 93.1 416 0 322.9 0 208S93.1 0 208 0 416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
            </svg>
            <?php if(isset($_SESSION['username'])): ?>
                <p onclick="window.location.href='user.php'" class="menu-Username account" style="cursor: pointer;"></p>
            <?php else: ?>
                    <input type="submit" value="Login" id="login-input" onclick="window.location.href='reglog.php'" hidden>
                    <label for="login-input">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon user" viewBox="0 0 448 512">
                            <path d="M144 128a80 80 0 1 1 160 0 80 80 0 1 1 -160 0zm208 0a128 128 0 1 0 -256 0 128 128 0 1 0 256 0zM48 480c0-70.7 57.3-128 128-128l96 0c70.7 0 128 57.3 128 128l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8c0-97.2-78.8-176-176-176l-96 0C78.8 304 0 382.8 0 480l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8z"/>
                        </svg>
                    </label>
            <?php endif; ?>
        </div>

        <div id="fast-menu">
            <div id="fast-menu-container">
                <div class="menu-item">
                    <div class="menu-title"><span>TRINITY</span></div>

                    <div class="submenu">
                        <div class="submenu-item">T-shirt
                            <div class="sub-sub" onclick="window.location.href='products.php?category=men&name=Basic T-shirt#product-header'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='products.php?category=men&name=Oversize T-shirt#product-header'">Oversize</div>
                        </div>

                        <div class="submenu-item">Polo shirt
                            <div class="sub-sub" onclick="window.location.href='products.php?category=men&name=Basic Polo#product-header'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='products.php?category=men&name=Logo Polo#product-header'">Logo</div>
                        </div>

                        <div class="submenu-item">Hoodie
                            <div class="sub-sub" onclick="window.location.href='products.php?category=men&name=Hoodie#product-header'">Signature</div>
                        </div>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-title"><span>TRINITY LADIES</span></div>

                    <div class="submenu">
                        <div class="submenu-item">T-shirt
                            <div class="sub-sub" onclick="window.location.href='products.php?category=women&name=Basic T-shirt#product-header'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='products.php?category=women&name=Oversize T-shirt#product-header'">Oversize</div>
                        </div>

                        <div class="submenu-item">Blouse
                            <div class="sub-sub" onclick="window.location.href='products.php?category=women&name=Classic Blouse#product-header'">Classic</div>
                            <div class="sub-sub" onclick="window.location.href='products.php?category=women&name=Wrap Blouse#product-header'">Warp</div>
                        </div>

                        <div class="submenu-item">Crop top
                            <div class="sub-sub" onclick="window.location.href='products.php?category=women&name=Basic CropTop#product-header'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='products.php?category=women&name=Tank CropTop#product-header'">Tank</div>
                        </div>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-title" onclick="window.location.href='voucher.php'"><span>GIFT VOUNCHER</span></div>
                </div>

                <div class="menu-item">
                    <div class="menu-title" onclick="window.location.href='userTier.php'"><span>TRINITY TIER</span></div>
                </div>

                <div class="menu-item">
                    <div class="menu-title" onclick="window.location.href='about.php'"><span>ABOUT</span></div>
                </div>

                <?php if(isset($_SESSION['username'])): ?>
                    <p onclick="window.location.href='user.php'" class="menu-Username fast-menu-account" style="cursor: pointer;"></p>
                <?php else: ?>
                    <input type="submit" value="Login" id="login-input" onclick="window.location.href='reglog.php'" hidden>
                    <label for="login-input" id="label-login-input">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon user fast-menu" viewBox="0 0 448 512">
                            <path d="M144 128a80 80 0 1 1 160 0 80 80 0 1 1 -160 0zm208 0a128 128 0 1 0 -256 0 128 128 0 1 0 256 0zM48 480c0-70.7 57.3-128 128-128l96 0c70.7 0 128 57.3 128 128l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8c0-97.2-78.8-176-176-176l-96 0C78.8 304 0 382.8 0 480l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8z"/>
                        </svg> 

                        <p>Login</p>
                    </label>
                <?php endif; ?>
            </div>
        </div>

        <div id="menu-search">
            <div id="search-Container">
                <span>
                    <svg class="icon active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376C296.3 401.1 253.9 416 208 416 93.1 416 0 322.9 0 208S93.1 0 208 0 416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                    </svg>
                </span>

                <input type="text" id="searchBar" placeholder="Search..."/>
    
            </div>

    
            <div id="search-Items">
                <p id="searchResult"></p>
                    <div id="items-Container">
                    <?php foreach($baseProduct as $p):?>
                        <div class="item" data-name="<?=$p['product_name']?>">
                            <div class="item-Img">
                                <img src="../<?=$p['product_img']?>" alt="" onclick="window.location.href='detail.php?id=<?=$p['id']?>'">
                            </div>

                            <div>
                                <h4 onclick="window.location.href='detail.php?id=<?=$p['id']?>'"><?=$p['product_name']?></h4>
                                <span>$<?=$p['product_price']?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>  
            </div>   

            <button id="searchBtn" onclick="window.location.href='products.php'"><p>View All Products</p></button>
        </div>

    </section>


<footer class="footer-2">
  <div class="footer-container">
    <div class="footer-left">
      <p class="footer-label">CONTACT US</p>
      <h2 class="footer-title">
        Let’s Discuss Your <br> Style. With Us
      </h2>

      <button class="footer-btn" onclick="window.location.href='contact.php'">
        Schedule a call now →
      </button>

      <p class="footer-email-label">OR EMAIL US AT</p>

      <div class="footer-email">
        triple3tbusiness@gmail.com
        <span>📋</span>
      </div>
    </div>

    <div class="footer-right">
      <div class="footer-col">
        <p class="footer-col-title">QUICK LINKS</p>
        <a href="../Pages/">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="voucher.php">Vouchers</a>
        <a href="userTier.php">User Tier</a>
        <a href="about.php">About Us</a>
      </div>
      <div class="footer-col">
        <p class="footer-col-title">INFORMATION</p>
        <a href="../legal/term-of-service.php">Terms of Service</a>
        <a href="../legal/privacy-policy.php">Privacy Policy</a>
        <a href="../legal/delivery-policy.php">Delivery Policy</a>
        <a href="../legal/ai-usage-policy.php">AI Usage Policy</a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>Copyright (c) 2026 trinity-newfed</p>
    <div class="footer-social">
      <span>f</span>
      <span>t</span>
      <span>ig</span>
      <span>in</span>
    </div>
  </div>
</footer>
<input type="hidden" value="<?=$address?>" id="to" disabled>
    <script>

        const finalTotal = document.getElementById("final-total");


        const priceDisplay = document.querySelectorAll(".items-price-container");
        if(priceDisplay){
            priceDisplay.forEach(pDisplay =>{
                pDisplay.textContent = "$" + parseFloat(pDisplay.dataset.price);
            });
        }

        //QUANTITY BTN
        const operationBtn = document.querySelectorAll(".operation-button");
        operationBtn.forEach(btn =>{
        btn.addEventListener('click', function(){
            
            const id = this.dataset.id;
            const action = this.dataset.action;
            const item = this.closest(".cartItem");
            const quantities = item.querySelector(".item-quantity");

            let quantity = parseInt(quantities.textContent);

            if(action == "plus" && item.dataset.stock > (quantity + 1)){
                quantity++;
                fetch('../Database/cart_update.php', {
                method: 'POST',
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_id=${id}&action=${action}`
                })
                .then(() => {btn.disabled = false;
                        calculateFinalTotal();
                });  
            }else if(action == "minus" && quantity > 1){
                quantity--;
                fetch('../Database/cart_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_id=${id}&action=${action}`
                })
                .then(() => {btn.disabled = false;
                        calculateFinalTotal();
                });  
            }else if(action == "minus" && quantity == 1){
                location.reload();
                fetch('../Database/cart_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_id=${id}&action=${action}`
                })
                .then(() => {btn.disabled = false;
                        calculateFinalTotal();
                });  
            }

            quantities.textContent = quantity;                        
        });
    });

        //COLOR & SIZE SELECT
        const colorSelect = document.querySelectorAll('.cartColor');
        const sizeSelect = document.querySelectorAll('.cartSize');
        

        function cartUpdate(item){
            const id = item.querySelector('.cartColor').dataset.id;
            const cartColor = item.querySelector('.cartColor').value.toLowerCase();
            const cartSize = item.querySelector('.cartSize').value;
            const colorIndicator = item.querySelector('.color');

            if(colorIndicator){
                colorIndicator.style.backgroundColor = cartColor; 
            }

            fetch('../Database/cart_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_id=${id}&cart_color=${cartColor}&cart_size=${cartSize}&action=update`
            })
            .catch(error => {
                console.error('Error updating cart:', error)
            })

        }

        colorSelect.forEach(color =>{
            color.addEventListener('change', function(){
                const img = this.closest(".cartItem").querySelector(".item-img-container img").src = this.options[this.selectedIndex].dataset.img;
                const item = this.closest(".cartItem");
                setTimeout(() => {
                    location.reload();
                }, 100);
                cartUpdate(item);
            });
        });

        sizeSelect.forEach(size =>{
            size.addEventListener('change', function(){
                const item = this.closest(".cartItem");
                cartUpdate(item);
            });
        });

        //DELETE ITEM 
        document.querySelectorAll(".deleteItem").forEach(del =>{del.addEventListener('click', function(e){
            const id = this.closest(".cartItem").dataset.id;
            let confirmDEL = confirm("Do you want to delete this piece?");
            if(confirmDEL){
                fetch('../Database/delete_item_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `cartId=${id}`
                })
                .then(() => location.reload())
                .catch(error =>{
                    console.log("Failed to delete");
                })
            }else{
                e.preventDefault();
            }
        });
        })

        //CHECKOUT BUTTON
        document.querySelector('#order-btn').addEventListener('click', function(){
            const ids = [];
            const sizes = [];
            const colors = [];

            document.querySelectorAll('.cartItem').forEach(item => {
                const id = item.dataset.id;
        
                const active = parseInt(item.dataset.active, 10) || 0;
                const stock = parseInt(item.dataset.stock, 10) || 0;
        
                const size = item.dataset.size || 'L'; 
                const color = item.dataset.color || 'Black';

                if(active === 1 && stock > 0){
                    ids.push(id);
                    sizes.push(size);
                    colors.push(color);
                }
            });

            if(ids.length === 0){
                alert("Giỏ hàng không có sản phẩm nào đủ điều kiện hoặc còn hàng để thanh toán!");
                return;
            }

            const voucherId = document.querySelector('#voucher-select')?.value || null;

            fetch('../Database/checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    cart_ids: ids,
                    cart_size: sizes,
                    cart_color: colors,
                    id: voucherId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success'){
                    window.location.href = data.redirect; 
                }else{
                    alert(data.message);
                }
            })
            .catch(error => console.error("Lỗi kết nối:", error));
        });

        
        //USERNAME
        const email = <?= isset($_SESSION['username']) ? json_encode($_SESSION['username']) : '""' ?>;
        let username1 = email.split("@")[0] || "";
        let displayName = username1.length > 6
        ? username1.substring(0, 6) + "..."
        : username1;
        const userWelcome = document.querySelectorAll(".menu-Username");
        
        if(userWelcome){
            userWelcome.forEach(user => user.textContent = "Hi, " + displayName);
        }

        const dropDown = document.getElementById("main-voucher");
        if(dropDown){
            dropDown.addEventListener('click', ()=>{
                document.querySelector(".voucher-list").style.display = "block";
            });
            document.addEventListener('click', function(e){
                if(e.target !== dropDown) document.querySelector(".voucher-list").style.display = "none";
            });
        }


        //MAP API
        let currentKm = 0;
        let coords = {
            from: [106.5775, 10.8908],
            to: null
        };

        window.onload = async function() {
            const toName = "<?=$address?>";
            const coordsTo = await getCoordsFromName(toName);
            coords.to = coordsTo;
            document.getElementById("to").value = toName;
            if(coordsTo){
                coords.to = coordsTo;
                const km = await calc();
                const fee = calculateShippingFee(km);
            }
        };

        async function calc(){
            const url = `https://router.project-osrm.org/route/v1/driving/${coords.from[0]},${coords.from[1]};${coords.to[0]},${coords.to[1]}?overview=false`;
            const res = await fetch(url);
            const data = await res.json();
            const km = (data.routes[0].distance / 1000);
            return km;
        }

        async function getCoordsFromName(name){
            const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(name)}&limit=1`);
            const data = await res.json();
            if(data.features.length > 0){
                return data.features[0].geometry.coordinates;
            }
            return null;
        }


        

    //DELIVERY CALCULATE
    function calculateShippingFee(km){
        if(isNaN(km) || km <= 0) return 0;
        currentKm = km; 
        if(km < 20) return 2;
        else if(km < 100) return 5;
        else if(km < 1000) return 15;
        else return 25;
    }

    window.onload = async function(){
        const toName = "<?=$address?>";
        const coordsTo = await getCoordsFromName(toName);
        if(coordsTo){
            coords.to = coordsTo;
            const km = await calc();
            const fee = calculateShippingFee(km);
            document.getElementById("deli-fee").textContent = fee.toLocaleString() + "$";
        }
        await calculateFinalTotal();
    };
    


//CALCULATE TOTAL
function calculateFinalTotal(){
    const selectedVoucher = document.querySelector("#voucher-select option:checked");
    const finalTotalDisplay = document.getElementById("final-total");
    const deliFeeDisplay = document.getElementById("deli-fee");
    const mainVoucher = document.getElementById("main-voucher");

    let total = 0;
    const items = document.querySelectorAll(".cartItem");
    

    //FOREACH ITEM
    items.forEach(item =>{
        const itemsTotalSpan = item.querySelector(".items-total-container");
        const price = parseFloat(item.querySelector(".items-price-container").textContent.replace("$", ""));
        const quantity = parseInt(item.querySelector(".item-quantity").textContent);
        
        const itemTotal = price * quantity;
        if (itemsTotalSpan) itemsTotalSpan.textContent = "Item total: $" + itemTotal;
            total += itemTotal;

        if(item.dataset.stock <= 0){
            item.style.opacity = "0.7";
            item.querySelector(".notice").classList.remove("hidden");
            item.querySelector(".notice").textContent = "OUT OF STOCK";
        }
        if(item.dataset.active == 0){
            item.style.opacity = "0.7";
            item.querySelector(".notice").classList.remove("hidden");
            item.querySelector(".notice").textContent = "TEMPORARILY UNAVAILABLE";
        }
    });

    //FREESHIP THRESHOLD
    const FREE_SHIP_THRESHOLD = 700;
    const isAutoFreeShip = total >= FREE_SHIP_THRESHOLD;
    let totalDiscount = 0;
    let shipDiscount = 0;
    let currentShippingFee = (typeof calculateShippingFee === 'function') ? calculateShippingFee(currentKm) : 0;

    //VOUCHER SELECT
    const vouchers = document.querySelectorAll(".voucher-list .voucher");
    vouchers.forEach(voucher => {
        const condition = parseFloat(voucher.dataset.condition) || 0;
        const isShipVoucher = parseInt(voucher.dataset.ship) === 1;


        if(total < condition || (isShipVoucher && isAutoFreeShip)){
            voucher.classList.add("disabled");
            let disabledVouchers = document.querySelectorAll(".voucher.disabled");
            disabledVouchers.forEach(disabledVoucher =>{
                const text = disabledVoucher.textContent;
                if(mainVoucher.textContent == text) mainVoucher.textContent = "No Selection";
            })
        }else{
            voucher.classList.remove("disabled");
        }
    });

    const activeVoucher = document.querySelector(".voucher.active");

    //CHECK VOUCHER
    if(activeVoucher && !activeVoucher.disabled && activeVoucher.value !== "0"){
        const val = parseFloat(activeVoucher.dataset.discount) || 0;
        const isShipVoucher = parseInt(activeVoucher.dataset.ship) === 1;
        if(isShipVoucher){
            shipDiscount = val; 
            totalDiscount = 0; 
        }else{
            const maxLimit = parseFloat(activeVoucher.dataset.max) || Infinity;
            totalDiscount = Math.min(total * (val / 100), maxLimit);
            shipDiscount = 0;
        }
    }

    let finalShippingFee = Math.max(0, currentShippingFee - shipDiscount);
    if(total >= FREE_SHIP_THRESHOLD){
        finalShippingFee = 0;
    }
    const finalTotal = Math.max(0, total - totalDiscount + finalShippingFee);
    
    //DISPLAY FEE
    if(finalTotalDisplay){
        finalTotalDisplay.textContent = "$" + finalTotal;
    }
    if(deliFeeDisplay){
        deliFeeDisplay.textContent = finalShippingFee === 0 ? "$0" : "$" + finalShippingFee.toLocaleString();
    }
}
const vouchers = document.querySelectorAll(".voucher-list .voucher");

vouchers.forEach(voucher => {
    voucher.addEventListener('click', ()=>{
        if(voucher.classList.contains("disabled")) return;
        vouchers.forEach(v => v.classList.remove("active"));
        voucher.classList.add("active");
        const activeVoucher = document.querySelector(".voucher.active");
        const input = document.getElementById("id");
        if(input){
            input.value = activeVoucher ? activeVoucher.dataset.id : "";
        }
        document.querySelector(".voucher-list").style.display = "none";
        const mainVoucher = document.getElementById("main-voucher");
        if(mainVoucher){
            mainVoucher.textContent = voucher.textContent;
        }
        calculateFinalTotal();
    });
});        
            
        //Menu toggle

        const fastMenuContainer = document.getElementById("fast-menu-container");
        const menuToggle = document.getElementById("menu-toggle");
        const hamburger = document.querySelector(".hamburger");

        document.addEventListener('click', function(e){
            if(menuToggle.checked && !hamburger.contains(e.target) && menuToggle !== e.target && !fastMenuContainer.contains(e.target)){
                menuToggle.checked = false;
            }
        });

        const menuTitles = document.querySelectorAll(".menu-title");
            menuTitles.forEach(title =>{
                title.addEventListener("click", ()=>{
                    const parent = title.parentElement;
                    parent.classList.toggle("active");
            });
        });
        const submenuItems = document.querySelectorAll(".submenu-item");
            submenuItems.forEach(item =>{
                item.addEventListener("click",(e)=>{
                    e.stopPropagation();
                    item.classList.toggle("active");
            });
        });

        //Search bar

        const search = document.querySelector(".icon.search");
        const menuSearch = document.getElementById("menu-search");
        const searchContainer = document.getElementById("search-Container");

        search.addEventListener('click', ()=>{
            document.getElementById("menu").classList.toggle("active");

            userWelcome ? userWelcome.forEach(user => user.classList.toggle("active")) : null;

            const lines = document.querySelectorAll(".line");
            lines.forEach(line => line.classList.toggle("active"));

            const icons = document.getElementById("menu").querySelectorAll(".icon path");
            icons.forEach(icon => icon.classList.toggle("active"));

            const spans = document.getElementById("menu").querySelectorAll("span");
            spans.forEach(span => span.classList.toggle("active"));

            document.getElementById("menu-search").classList.toggle("active");
            document.getElementById("search-Container").classList.toggle("active");
        });

        document.addEventListener('click', function(e){
            if(!searchContainer.contains(e.target) && e.target !== search){
                document.getElementById("menu").classList.remove("active");

                userWelcome ? userWelcome.forEach(user => user.classList.remove("active")) : null;

                const lines = document.querySelectorAll(".line");
                lines.forEach(line => line.classList.remove("active"));

                const icons = document.getElementById("menu").querySelectorAll(".icon path");
                icons.forEach(icon => icon.classList.remove("active"));

                const spans = document.getElementById("menu").querySelectorAll("span");
                spans.forEach(span => span.classList.remove("active"));
                document.getElementById("menu-search").classList.remove("active");
                document.getElementById("search-Container").classList.remove("active");
            }
        });


        const searchBar = document.getElementById("searchBar");
        const searchItems = document.getElementById("search-Items");
        const searchResult = document.getElementById("searchResult");
        const searchBtn = document.getElementById("searchBtn");

        searchBar.addEventListener('keyup', () => {
            const items = document.querySelectorAll(".item");
            const searchKey = searchBar.value.toLowerCase().trim();

            if(searchKey.length > 0){
                searchItems.classList.add("active");

            }else{

                searchItems.classList.remove("active");
                searchResult.textContent = "";
                return;
            }

            let hasResult = false;

            items.forEach(item => {
                const name = item.dataset.name.toLowerCase();
                if(name.includes(searchKey) || searchKey === "all"){
                    item.style.display = "";
                    hasResult = true;    

                }else{
                    item.style.display = "none";
                }
            });

            if(hasResult){
                searchBtn.style.display = "";
                if(searchKey.length >= 3) searchResult.textContent = "Result for: " + searchKey;

            }else{
                searchBtn.style.display = "none";
                if(searchKey.length >= 3) searchResult.textContent = "No result for: " + searchKey; 
                else searchResult.textContent = "";
            }
        });


//USERNAME AND API FETCH
const user_id = <?php echo json_encode($userID); ?>;

if(user_id){
  const interval = setInterval(async () =>{
    try {
      const res = await fetch(`http://localhost:5000/api/progress/${user_id}`);
      const data = await res.json();

      if(data.status === "done"){
        clearInterval(interval);
        const goUser = confirm("Redirect to user page for result?");
        if(goUser){
          window.location.href = data.redirect;
        }
      }
    }catch (err){
      console.error(err);
    }
  }, 3000);
}
    </script>
</body>
</html>