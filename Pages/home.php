<?php
include "../Database/createdatabase.php";
session_start();

if(!isset($_SESSION['role'])){
    $_SESSION['role'] = "guest";
}

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);


$product = $conn
  ->query("SELECT products.id AS id,
            products.product_name, products.product_group,
            products.product_price, products.product_category,
            products.product_type, products.product_describe,
            products.product_size, products.product_img,
            product_variant.product_price, product_variant.product_size,
            product_variant.product_img, product_variant.product_img1,
            product_variant.product_color

            FROM products
            JOIN product_variant
            ON products.id = product_id
            ")
  ->fetch_all(MYSQLI_ASSOC);

$product_variant = $conn->query("SELECT 
                                 product_variant.product_id, product_variant.product_price,
                                 product_variant.product_img AS variant_img,
                                 product_variant.product_color, product_variant.product_size,
                                 product_variant.product_stock, products.product_name,
                                 products.product_category

                                 FROM product_variant
                                 JOIN products
                                 ON product_variant.product_id = products.id")
                        ->fetch_all(MYSQLI_ASSOC);

$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

if(isset($_SESSION['error'])){
    echo "<script>alert('{$_SESSION['error']}');</script>";
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en" id="html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trinity Style - Home</title>
    <link rel="stylesheet" href="../Css/nav.css">
    <link rel="stylesheet" href="../Css/home.css">
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Birthstone&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&family=Playwrite+NO:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>
<section id="head">
    <div id="head-slider">
        <div id="head-banner-container">
        <img src="../Pictures/Banners/BannerImg-1.png">
        <div id="hero-text">
            <h1>NEW<br>COLLECTION</h1>
            <p>Timeless essentials for the modern wardrobe</p>
            <button onclick="window.location.href='products.php'">
                <span class="cta-hero-text">Shop Now</span>
            </button>
        </div>
        </div>

        <div id="head-banner-container-2">

            <img src="../Pictures/Banners/BannerImg-2.png">

            <div id="hero-text-2">
                <p>DISCOVER THE NEW</p>
                <h1>COLLECTION</h1>
                <div class="title"></div>
                <p>MODERN STREETWEAR <br>POWERED BY AI STYLING</p>
                <button id="scroll-btn" onclick="window.location.href='products.php'">Scroll to explore</button>
            </div>

        </div> 
        
        
    </div>

    <button id="next-btn">
        <svg class="icon btn" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
            <path d="M566.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L466.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l434.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/>
        </svg>
    </button>

    <button class="sectionBtn" onclick="scrollToNext(this)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="icon sectionPath">
            <path d="M169.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 306.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/>
        </svg>
    </button>

</section>

<section id="categories">
    <div id="categoriesImg-Container" class="animate-on-scroll">
        <img src="../Pictures/Banners/BannerCategory.png" alt="">
    </div>

    <div id="category-Container" class="animate-on-scroll">
        <div class="territory category animate-on-scroll">
            <span>CATEGORY</span>
            <h2>WARDROBE</h2>
            <p>Discover pieces that define your style</p>
        </div>

        <div id="category" class="animate-on-scroll">

            <div class="category-divs">
                <div>
                    <img class="cateImg" onclick="window.location.href='products.php?category=all&name=T-shirt#product-header'" src="../Pictures/Icon/T-shirt.png" id="icon-1" alt="">
                    <label for="icon-1" onclick="window.location.href='products.php?category=all&name=T-shirt#product-header'">T-SHIRT</label>
                    <button class="viewBtn" onclick="window.location.href='products.php?category=all&name=T-shirt#product-header'"><span class="btnSpan">Explore</span></button>
                </div>

                <?php foreach($product as $p): 
                    $T_shirt = $p['product_category'] === "men" && $p['product_name'] === "White Basic T-shirt";
                ?>
                <?php if($T_shirt): 
                    $T_shirt = $p;    
                ?>

                    <div class="divImg2">
                        <img src="../<?=$T_shirt['product_img']?>" alt="">
                    </div>

                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="category-divs">
                <div>
                    <img class="cateImg" onclick="window.location.href='products.php?category=all&name=Polo#product-header'" src="../Pictures/Icon/Polo.png" id="icon-2" alt="">
                    <label onclick="window.location.href='products.php?category=all&name=Polo#product-header'" for="icon-2">POLO</label>
                    <button class="viewBtn" onclick="window.location.href='products.php?category=all&name=Polo#product-header'"><span class="btnSpan">View All</span></button>
                </div>

                <?php foreach($product as $p): 
                    $T_shirt = $p['product_category'] === "men" && $p['product_name'] === "White Basic Polo";
                ?>
                <?php if($T_shirt): 
                    $T_shirt = $p;    
                ?>

                    <div class="divImg2">
                        <img src="../<?=$T_shirt['product_img']?>" alt="">
                    </div>

                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="category-divs none">
                <div>
                    <img class="cateImg" onclick="window.location.href='products.php?category=all&name=Hoodie#product-header'" src="../Pictures/Icon/Hoodie.png" id="icon-3" alt="">
                    <label onclick="window.location.href='products.php?category=all&name=Hoodie#product-header'" for="icon-3">HOODIE</label>
                    <button class="viewBtn" onclick="window.location.href='products.php?category=all&name=Hoodie#product-header'"><span class="btnSpan">Explore</span></button>
                </div>
            </div>

            <div class="category-divs">
                <div>
                    <img class="cateImg" onclick="window.location.href='products.php?category=all&name=Blouse#product-header'" src="../Pictures/Icon/Blouse.png" id="icon-4" alt="">
                    <label onclick="window.location.href='products.php?category=all&name=Blouse#product-header'" for="icon-4">BLOUSE</label>
                    <button class="viewBtn" onclick="window.location.href='products.php?category=all&name=Blouse#product-header'"><span class="btnSpan">View All</span></button>
                </div>

                <?php foreach($product as $p): 
                    $T_shirt = $p['product_category'] === "women" && $p['product_name'] === "Black Wrap Blouse";
                ?>
                <?php if($T_shirt): 
                    $T_shirt = $p;    
                ?>

                    <div class="divImg2">
                        <img src="../<?=$T_shirt['product_img']?>" alt="">
                    </div>

                <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="category-divs">
                <div>
                    <img class="cateImg" onclick="window.location.href='products.php?category=all&name=Crop Top#product-header'" src="../Pictures/Icon/CropTop.jpeg" id="icon-5" alt="">
                    <label onclick="window.location.href='products.php?category=all&name=Crop Top#product-header'" for="icon-5">CROPTOP</label>
                    <button class="viewBtn" onclick="window.location.href='products.php?category=all&name=Crop Top#product-header'"><span class="btnSpan">View All</span></button>
                </div>

                <?php foreach($product as $p): 
                    $T_shirt = $p['product_category'] === "women" && $p['product_name'] === "White Tank Crop Top";
                ?>
                <?php if($T_shirt): 
                    $T_shirt = $p;    
                ?>

                    <div class="divImg2 short">
                        <img src="../<?=$T_shirt['product_img']?>" alt="">
                    </div>

                <?php endif; ?>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
    
</section>

    
    <section id="body">
        <div id="body-content-container">
            <div id="newest-collection">
                <div id="container-body">
                    <video id="bannerVideo" muted>
                        <source src="../Pictures/Banners/videoBanner.mp4" type="video/mp4">
                    </video>

                    
                </div>

            </div>

            
        </div>

        <button class="sectionBtn" onclick="scrollToNext(this)">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="icon sectionPath">
                    <path d="M169.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 306.7 54.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/>
                </svg>
            </button>
    </section>

    <div class="marquee-box">
        <div class="marquee-track">

            <div class="marquee-item">
                <span>SALE UP TO 50%</span>
                <span>FREE DELIVERY FOR $700 ORDER</span>
                <span>GIFT WRAP THE ORDER</span>
                <span>LUXURIOUS GIFT COMBO</span>
            </div>

            <div class="marquee-item">
                <span>SALE UP TO 50%</span>
                <span>FREE DELIVERY FEE FOR $700 ORDER</span>
                <span>GIFT WRAP THE ORDER</span>
                <span>LUXURIOUS GIFT COMBO</span>
            </div>
        </div>
    </div>

    <section id="body-1" class="animate-on-scroll">
        <img src="../Pictures/Banners/BannerAI-1.png" alt="">

        <div id="cta-tryon">

                        <h3>See yourself in Trinity</h3>
                        <p>Upload your photo and experience <br> virtual fiting powered by AI</p>
                        <button onclick="window.location.href='products.php#product-section'">
                            <span class="cta-tryon-text">Try Now</span>
                        </button>

                    </div>
    </section>
    
    <section id="body-2">
        <div id="body-2-text-container" class="animate-on-scroll">
            <span>Collection</span>
            <h4>The Winter Collection</h4>
            <p>The Trinity collection isn't just about surviving the cold; it’s about mastering it. Built on the pillars of Form, Function, and Fortitude, this line of premium coats is designed for the modern individual who demands elegance without compromising on warmth.</p>
            <button class="body-2-cta" onclick="window.location.href='products.php#product-section'"><span>Discover</span></button>
        </div>

        <div id="body-2-img-container">
            <img class="body-2-img id1 animate-on-scroll" src="../Pictures/Banners/model1.png" alt="">
            <img class="body-2-img id2 animate-on-scroll" src="../Pictures/Banners/model2.png" alt="">
        </div>
    </section>

    <div class="territory">
        <span>TRINITY</span> 
        <h3>OUR LOOK</h3>
    </div>

    <section id="footer">
        <div id="feat-product">
            <div id="featBtn">
                <button id="featPre">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"  class="icon">
                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                    </svg>
                </button>

                <button id="featNext">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" class="icon">
                        <path d="M247.1 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L179.2 256 41.9 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/>
                    </svg>
                </button>
            </div>
                <!--Newest Collection-->
                <?php 
                    foreach($product as $item): 
                    if($item['product_category'] != "collections") continue;
                ?>
                <div class="feat collection">
                    <div class="feat-img-container fi-1" onclick="window.location.href='detail.php?id=<?=$item['id']?>'">
                        <img class="feat-img id1" src="../<?=$item['product_img']?>" alt="">
                        <img class="feat-img id2" src="../<?=$item['product_img1']?>" alt="">
                    </div>

                    <div id="featContent-Container" style="flex-direction: column; max-height: fit-content; width: 30%">
                        <?php 
                            foreach($product_variant as $variant): 
                            if($variant['product_id'] != $item['id']) continue;
                            if($variant['product_color'] == $item['product_color']) continue;
                        ?>
                        <img src="../<?=$variant['variant_img']?>">
                        <?php 
                            break;
                            endforeach; 
                        ?>
                        <span><?=$item['product_name']?></span>
                        <p>$ <?=$item['product_price']?></p>
                        <button class="feat-btn" onclick="window.location.href='products.php#product-'">Try with AI</button>
                    </div>
                </div>
                <?php 
                    break;
                    endforeach; 
                ?>

                <!--Basic T-shirt-->
                <?php 
                    foreach($product as $item): 
                    if($item['product_category'] != "men" || $item['product_name'] != "Basic T-shirt") continue;
                ?>
                <div class="feat id1">
                    <div class="feat-img-container fi-1">
                        <img class="feat-img id1" src="../<?=$item['product_img']?>" alt="">
                        <img class="feat-img id2" src="../<?=$item['product_img1']?>" alt="">
                    </div>

                    <div id="featContent-Container" style="flex-direction: column; max-height: fit-content; width: 30%">
                        <?php 
                            foreach($product_variant as $variant): 
                            if($variant['product_id'] != $item['id']) continue;
                            if($variant['product_color'] == $item['product_color']) continue;
                        ?>
                        <img src="../<?=$variant['variant_img']?>">
                        <?php 
                            break;
                            endforeach; 
                        ?>
                        <span><?=$item['product_name']?></span>
                        <p>$<?=$item['product_price']?></p>
                        <button class="feat-btn" onclick="window.location.href='products.php#product-<?=$item['id']?>'">View Product</button>
                    </div>

                </div>
                <?php 
                    break;
                    endforeach; 
                ?>

                <!--Trinity Lady-->
                <?php 
                    foreach($product as $item): 
                    if($item['product_category'] != "women" || $item['product_name'] != "Classic Blouse") continue;
                ?>
                <div class="feat id2">
                    <div class="feat-img-container fi-1">
                        <img class="feat-img id1" src="../<?=$item['product_img']?>" alt="">
                        <img class="feat-img id2" src="../<?=$item['product_img1']?>" alt="">
                    </div>

                    <div id="featContent-Container" style="flex-direction: column; max-height: fit-content; width: 30%">
                        <?php 
                            foreach($product_variant as $variant): 
                            if($variant['product_id'] != $item['id']) continue;
                            if($variant['product_color'] == $item['product_color']) continue;
                        ?>
                        <img src="../<?=$variant['variant_img']?>">
                        <?php 
                            break;
                            endforeach; 
                        ?>
                        <span><?=$item['product_name']?></span>
                        <p>$<?=$item['product_price']?></p>
                        <button class="feat-btn" onclick="window.location.href='products.php#product-<?=$item['id']?>'">View Product</button>
                    </div>

                </div>
                <?php 
                    break;
                    endforeach; 
                ?>


                <!--Logo Polo-->
                <?php 
                    foreach($product as $item): 
                    if($item['product_category'] != "men" || $item['product_name'] != "Logo Polo") continue;
                ?>
                <div class="feat id2">
                    <div class="feat-img-container fi-1">
                        <img class="feat-img id1" src="../<?=$item['product_img']?>" alt="">
                        <img class="feat-img id2" src="../<?=$item['product_img1']?>" alt="">
                    </div>

                    <div id="featContent-Container" style="flex-direction: column; max-height: fit-content; width: 30%">
                        <?php 
                            foreach($product_variant as $variant): 
                            if($variant['product_id'] != $item['id']) continue;
                            if($variant['product_color'] == $item['product_color']) continue;
                        ?>
                        <img src="../<?=$variant['variant_img']?>">
                        <?php 
                            break;
                            endforeach; 
                        ?>

                        <span><?=$item['product_name']?></span>
                        <p>$<?=$item['product_price']?></p>
                        <button class="feat-btn" onclick="window.location.href='products.php#product-<?=$item['id']?>'">View Product</button>
                    </div>

                </div>
                <?php 
                    break;
                    endforeach; 
                ?>
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
                <span onclick="window.location.href='#head'">Home</span>
                <span onclick="window.location.href='products.php?#product-section'">Shop</span>
                <span onclick="window.location.href='products.php?#product-section'">Collection</span>
                <span onclick="window.location.href='contact.php'">Contact</span>
            </div>

            <div id="logo" onclick="window.location.href='#head'">TRINITY</div>
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

<section id="footer-1">
    <div class="footer-1-title">
        <span>TRINITY</span>
        <h4>FROM TRINITY TO YOU</h4>
    </div>

    <div class="footer1-imgContainer">
        <div>
            <img src="../Pictures/Banners/F1-I1.png" alt="">
            <p>Woven from the finest fabrics</p>
        </div>

        <div>
            <img src="../Pictures/Banners/F1-I2.png" alt="">
            <p>Meticulously inspected to ensure perfection in every single piece before it reaches you</p>
        </div>

        <div>
            <img src="../Pictures/Banners/F1-I3.png" alt="">
            <p>Unbox a package crafted with passion, tailored for perfection, and delivered just for you</p>
        </div>
    </div>
</section>

<footer class="footer-2">
    <img src="../Pictures/Banners/BannerFooter.png" alt="" style="max-width: 100%; filter: brightness(70%);">
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
        <a href="#head">Home</a>
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
<script>
        const email = <?= isset($_SESSION['username']) ? json_encode($_SESSION['username']) : '""' ?>;
        let username1 = email.split("@")[0] || "";
        let displayName = username1.length > 6
        ? username1.substring(0, 6) + "..."
        : username1;
        const userWelcome = document.querySelectorAll(".menu-Username");
        const head = document.getElementById("head");
        const body = document.getElementById("body");
        const footer = document.getElementById("footer");       
        const slider = document.getElementById("head-slider");
        const nextBtn = document.getElementById("next-btn");
        const video = document.getElementById("bannerVideo");
        const category = document.getElementById("category");
        function scrollToNext(button){

        const currentSection = button.parentElement; 
        const nextSection = currentSection.nextElementSibling; 

        if(nextSection){
            nextSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting){
                        entry.target.classList.add("animate");
                        observer.unobserve(entry.target); 
                    }
                });
            }, {
                root: null,
                threshold: 0.15
            });

            const elementsToAnimate = document.querySelectorAll('.animate-on-scroll');
            elementsToAnimate.forEach(element => observer.observe(element));
        });

        if(userWelcome){
            userWelcome.forEach(user => user.textContent = "Hi, " + displayName);
        }

        let index = 0;
        let bannerInterval = setInterval(() => {
            index++;
            if(index > 1){
                index = 0;
            }
            nextBtn.classList.toggle("rotate");
            slider.style.transform = `translateX(-${index * 100}%)`;
        }, 20000);

        nextBtn.addEventListener("click", () =>{
            clearInterval(bannerInterval);
            index++;
            if(index > 1){
                index = 0;
            }

            bannerInterval = setInterval(() => {
                index++;
                if(index > 1){
                    index = 0;
                }
                nextBtn.classList.toggle("rotate");
                slider.style.transform = `translateX(-${index * 100}%)`;
            }, 20000);

            nextBtn.classList.toggle("rotate");
            slider.style.transform = `translateX(-${index * 100}%)`;
        });



        let num = 0;
        const headObserve = new IntersectionObserver(entries =>{
            entries.forEach(entry =>{
                if(entry.isIntersecting){
                    document.getElementById("menu").style.background = "transparent";
                    document.getElementById("menu").style.backdropFilter = "blur(0px)";
                    document.getElementById("menu").style.transition = ".3s all";
                    document.getElementById("menu").classList.add("head");
                    userWelcome ? userWelcome.forEach(user => user.style.color = "") : null;

                    const lines = document.querySelectorAll(".line");
                    lines.forEach(line => line.style.stroke = "white");

                    const icons = document.getElementById("menu").querySelectorAll(".icon path");
                    icons.forEach(icon => icon.style.fill = "white");

                    const spans = document.getElementById("menu").querySelectorAll("span");
                    spans.forEach(span => span.style.color = "white");

                }else{
                    document.getElementById("menu").classList.remove("head");

                    userWelcome ? userWelcome.forEach(user => user.style.color = "black") : null;

                    const lines = document.querySelectorAll(".line");
                    lines.forEach(line => line.style.stroke = "black");

                    const icons = document.getElementById("menu").querySelectorAll(".icon path");
                    icons.forEach(icon => icon.style.fill = "black");

                    const spans = document.getElementById("menu").querySelectorAll("span");
                    spans.forEach(span => span.style.color = "");

                    document.getElementById("menu").style.background = "";
                    document.getElementById("menu").style.backdropFilter = "blur(10px)";
                }
            });
        }, {
            threshold: 0.7
        });

        const bodyObserve = new IntersectionObserver(entries =>{
            entries.forEach(entry =>{
                if(entry.isIntersecting){
                setInterval(() => {
                    if(num < 3){
                        num++;
                        if(num == 2){
                            video.play();
                        }
                }
                }, 1000);
                }
            });
        },{
            threshold: 0.45
        });
        const footerObserve = new IntersectionObserver(entries =>{
            entries.forEach(entry =>{
                if(entry.isIntersecting){
                    
                }
            });
        },{
            threshold: 0.5
        });

        headObserve.observe(head);
        bodyObserve.observe(body);
        footerObserve.observe(footer);



        //Menu Toggle

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


        //Search bar

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

        

        const divs = category.querySelectorAll(".category-divs");

        let currentIndex = 0;
        const totalDivs = divs.length;
        const gap = 250;
        let isScrolling = false;

        category.addEventListener('wheel', function(e){
            if(window.innerWidth < 768){
                return; 
            }
            e.preventDefault(); 
            if (isScrolling) return; 

            if(e.deltaY > 0){
                if(currentIndex < totalDivs - 1) currentIndex++;
            }else{
                if(currentIndex > 0) currentIndex--;
            }


            const divHeight = divs[0].offsetHeight;
            const step = divHeight + gap;
            const totalTranslate = currentIndex * step;

            isScrolling = true;
            divs.forEach(d => {
                d.style.transform = `translateY(-${totalTranslate}px)`;
            });

            setTimeout(() => {
                isScrolling = false;
            }, 1500); 

            }, { passive: false });

            const featPre = document.getElementById("featPre");
            const featNext = document.getElementById("featNext");
            const feats = document.querySelectorAll(".feat");
            document.querySelector(".feat.collection").classList.add("active");
            let indexes = 0;
            featNext.addEventListener('click', ()=>{
                indexes = indexes + 1;
                if(indexes < 4){
                    feats.forEach(feat => feat.classList.remove("active"));
                    feats[indexes].classList.add("active");
                }else indexes = -1;
            });

            featPre.addEventListener('click', ()=>{
                indexes = indexes - 1;
                if(indexes >= 0){
                    feats.forEach(feat => feat.classList.remove("active"));
                    feats[indexes].classList.add("active");
                }else indexes = 4;
            });

    const user_id = <?php echo json_encode($userID); ?>;

    if(user_id){
  const interval = setInterval(async () =>{
    try{
      const res = await fetch(`http://localhost:5000/api/progress/${user_id}`);
      const data = await res.json();

      if(data.status === "done"){
        clearInterval(interval);
        const goUser = confirm("Redirect to user page for result?");
        if(goUser){
          window.location.href = data.redirect;
        }
      }

    }catch(err){
      console.error(err);
    }
  }, 3000);
    }


</script>
</body>
</html>