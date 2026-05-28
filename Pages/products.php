<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);

session_start();
$username = $_SESSION['username'] ?? null;
$userID = $_SESSION['user_id'] ?? null;

$baseProduct = $conn->query("SELECT * FROM products")
                    ->fetch_all(MYSQLI_ASSOC);

$product = $conn
  ->query("SELECT products.id AS id,
            products.id AS id,
            products.product_name, 
            products.product_group,
            products.product_price, 
            products.product_category,
            products.product_type, 
            products.product_describe,
            product_variant.product_stock,
            product_variant.product_img,
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

$sql = $conn->prepare("SELECT * FROM user_policy_agreement
                       WHERE user_id = ?");
$sql->bind_param("i", $userID);
$sql->execute();
$agreement = $sql->get_result();
if($agreement->num_rows > 0){
  $agree = 1;
}else{
  $agree = 0;
}
$sql->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRINITY - Ready To Wear</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Css/nav.css">
    <link rel="stylesheet" href="../Css/products.css">
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Birthstone&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&family=Playwrite+NO:wght@100..400&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .font-serif-custom {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-white text-black antialiased" id="body">
    
    <section id="menu" class="head">
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

    <section id="head" class="relative bg-[#E5E5E5] overflow-hidden min-h-[500px] md:h-[100vh] flex items-center">
        <div class="absolute bg-[black] z-[100] w-[100%] h-[100%] animate-1"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-90">
            <img class="object-cover w-[100%] h-[100%] animate-2" src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=1200" alt="">
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center py-20 z-10">
            <h1 class="text-4xl md:text-7xl font-serif-custom tracking-widest uppercase mb-4 text-gray-900">
                READY-TO-WEAR
            </h1>
            <p class="text-sm md:text-base text-gray-700 max-w-md mx-auto mb-8 font-light tracking-wide">
                Contemporary silhouettes<br>crafted for the modern individual.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 text-xs tracking-widest uppercase">
                <a href="#" class="bg-black text-white px-8 py-3.5 hover:bg-transparent border-black transition">Explore Collection</a>
                <a href="#" class="border border-black text-black px-8 py-3.5 hover:bg-black hover:text-white transition">New Arrivals</a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex justify-between items-baseline mb-8">
            <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">Featured Collection</h2>
            <a href="#" class="text-xs uppercase tracking-widest text-gray-500 hover:text-black underline underline-offset-4">View All</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8 collections animate-on-scroll">
            <?php foreach($baseProduct as $base): ?>
                <?php if($base['product_category'] !== "collections") continue; ?>
                
            
            <div class="group cursor-pointer collections-child">
                <div class="relative bg-[#F3F3F3] aspect-[3/4] mb-4 overflow-hidden">
                    <span class="absolute top-2 left-2 bg-white text-[9px] uppercase tracking-widest px-2 py-0.5 z-10">Limited</span>
                    <img src="../<?=$base['product_img']?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Product">
                </div>
                <h3 class="text-xs uppercase tracking-wider text-gray-900">TRINITY <?=$base['product_name']?></h3>
                <p class="text-xs text-gray-600 mt-1">$ <?=$base['product_price']?></p>
            </div>

            <?php endforeach; ?>

            <?php foreach($product as $key => $item): ?>
                <?php if($item['product_category'] !== "collections") continue; ?>
    
                    <div class="group cursor-pointer collections-child"
                         data-id="<?=$item['id']?>" 
                         data-img="../<?=$item['product_img']?>"
                         data-name="<?=$item['product_color']?> <?=$item['product_name']?>"
                         data-price="<?=$item['product_price']?>"
                         data-category="<?=$item['product_category']?>"
                         data-color="<?=$item['product_color']?>"
                         data-stock="<?=$item['product_stock']?>">

                        <div class="bg-[#F3F3F3] aspect-[3/4] mb-4 overflow-hidden">
                            <img src="../<?=$item['product_img']?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Product">
                        </div>

                        <h3 class="text-xs uppercase tracking-wider text-gray-900"><?=$item['product_color']?> <?=$item['product_name']?></h3>
                        <p class="text-xs text-gray-600 mt-1">$<?=$item['product_price']?></p>

                        <?php foreach($product_variant as $variant): 
                        ?>
                            <?php if($variant['product_id'] == $item['id']): 
                                $activeClass = ($variant['product_color'] === $item['product_color']) ? 'active' : '';
                            ?>
                                <div class="variants <?=$activeClass?>" 
                                     data-id="<?=$variant['product_id']?>"
                                     data-variant="<?=$variant['product_color']?>"
                                     data-img="../<?=$variant['variant_img']?>" 
                                     data-name="<?=$variant['product_color']?> <?=$variant['product_name']?>"
                                     data-price="<?=$variant['product_price']?>"
                                     data-category="<?=$variant['product_category']?>"
                                     data-color="<?=$variant['product_color']?>"
                                     data-stock="<?=$variant['product_stock']?>">
                                </div>
                            <?php endif; ?>
                        <?php 
                          endforeach;
                        ?>
                    </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 bg-[#F9F9F9] items-center">
        <div class="h-96 md:h-[600px] w-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=800');"></div>
        <div class="p-12 md:p-24 text-center md:text-left">
            <h2 class="text-3xl font-serif-custom tracking-widest uppercase mb-4">Tailoring Redefined</h2>
            <p class="text-xs text-gray-600 tracking-wide max-w-sm mb-8 leading-relaxed">
                Precision cuts, elevated textures, and timeless forms designed beyond seasonal trends.
            </p>
            <a href="#" class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition">Discover More</a>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-100">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">Best Sellers</h2>
            <div class="flex space-x-2">
                <button class="previous p-1 border border-gray-200 rounded-full hover:border-black"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path></svg></button>
                <button class="next p-1 border border-gray-200 rounded-full hover:border-black"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
        </div>

        <div class="flex overflow-x-auto overflow-y-hidden gap-x-5 max-w-[100%] scrollbar-hide hide products animate-on-scroll">
            <?php 
                $index = -1;
                foreach($baseProduct as $item): 
                $index = $index + 1;
                $formattedNum = str_pad($index, 2, '0', STR_PAD_LEFT);
            ?>
                <?php if($item['product_category'] === "collections") continue; ?>
                <div class="products-child group cursor-pointer w-[calc((100%-80px)/5)] shrink-0 min-w-[160px] product transition-all duration-500"
                    <?php 
                        foreach($product_variant as $variant):
                        if($variant['product_id'] == $item['id']):
                    ?>
                     data-id="<?=$item['id']?>" 
                     data-img="../<?=$item['product_img']?>"
                     data-name="<?=$item['product_name']?>"
                     data-price="<?=$item['product_price']?>"
                     data-color="<?=$variant['product_color']?>"
                     data-category="<?=$item['product_category']?>"
                     data-stock="<?=$item['product_stock']?>"
                    <?php 
                        endif; 
                        endforeach;
                    ?>
                     >
                    
                    <div class="text-[10px] text-gray-400 mb-1"><?=$formattedNum?></div>
                        <div class="relative bg-[#F3F3F3] aspect-[3/4] mb-3">
                            <img src="../<?=$item['product_img']?>" class="w-full h-full object-cover" alt="">
                        </div>

                        <h3 class="text-[11px] uppercase tracking-wider"><?=$item['product_name']?></h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">$ <?=$item['product_price']?></p>

                        <?php foreach($product_variant as $variant): 
                        ?>
                            <?php if($variant['product_id'] == $item['id']): 
                                $activeClass = ($variant['product_color'] === "white") ? 'active' : '';
                            ?>
                                <div class="variants <?=$activeClass?>" 
                                     data-id="<?=$variant['product_id']?>"
                                     data-variant="<?=$variant['product_color']?>"
                                     data-img="../<?=$variant['variant_img']?>" 
                                     data-name="<?=$variant['product_color']?> <?=$variant['product_name']?>"
                                     data-price="<?=$variant['product_price']?>"
                                     data-category="<?=$variant['product_category']?>"
                                     data-color="<?=$variant['product_color']?>"
                                     data-stock="<?=$variant['product_stock']?>">
                                </div>
                            <?php endif; ?>
                        <?php 
                          endforeach;
                        ?>
                    </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <div class="h-[450px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1485230895905-ec40ba36b9bc?q=80&w=600');"></div>
        <div class="h-[450px] bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=600');"></div>
        <div class="p-4">
            <span class="text-[10px] tracking-widest text-gray-400 uppercase block mb-2">Hot Collection</span>
            <hr class="w-12 border-black mb-6">
            <p class="text-xs text-gray-600 leading-relaxed tracking-wide mb-8">
                A curated drop featuring structured tailoring and contemporary essentials inspired by urban architecture.
            </p>
            <a href="#" class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition">View Collection</a>
        </div>
    </section>

    <footer class="bg-[#F6F6F6] pt-16 pb-8 border-t border-gray-200 text-xs tracking-wide">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
            <div>
                <h4 class="font-serif-custom text-base tracking-widest uppercase mb-4">Trinity</h4>
                <p class="text-gray-500 leading-relaxed mb-4">Minimal contemporary fashion label focused on tailoring and refined essentials.</p>
                <div class="flex space-x-4 text-gray-600">
                    <a href="#" class="hover:text-black">IG</a>
                    <a href="#" class="hover:text-black">FB</a>
                    <a href="#" class="hover:text-black">PT</a>
                </div>
            </div>
            <div>
                <h4 class="uppercase font-semibold text-gray-900 tracking-wider mb-4">Navigation</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:text-black">New In</a></li>
                    <li><a href="#" class="hover:text-black">Ready To Wear</a></li>
                    <li><a href="#" class="hover:text-black">Men</a></li>
                    <li><a href="#" class="hover:text-black">Editorial</a></li>
                </ul>
            </div>
            <div>
                <h4 class="uppercase font-semibold text-gray-900 tracking-wider mb-4">Support</h4>
                <ul class="space-y-2 text-gray-500">
                    <li><a href="#" class="hover:text-black">Contact</a></li>
                    <li><a href="#" class="hover:text-black">Shipping</a></li>
                    <li><a href="#" class="hover:text-black">Returns</a></li>
                    <li><a href="#" class="hover:text-black">FAQs</a></li>
                </ul>
            </div>
            <div>
                <h4 class="uppercase font-semibold text-gray-900 tracking-wider mb-4">Newsletter</h4>
                <p class="text-gray-500 mb-4 leading-relaxed">Subscribe to receive updates, access to exclusive deals, and more.</p>
                <form class="flex border-b border-black py-1">
                    <input type="email" placeholder="Enter your email" class="bg-transparent flex-1 focus:outline-none text-xs placeholder-gray-400">
                    <button type="submit" class="uppercase tracking-widest font-semibold hover:opacity-70">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-gray-200 flex flex-col md:flex-row justify-between text-[11px] text-gray-400">
            <p>© 2026 TRINITY. All rights reserved.</p>
            <div class="flex space-x-4 mt-2 md:mt-0">
                <a href="#" class="hover:text-black">Privacy Policy</a>
                <a href="#" class="hover:text-black">Terms & Conditions</a>
            </div>
        </div>
    </footer>


    <div id="product-modal">

        <div class="modal-container">
            <div class="modal-left">
                <img id="modal-img" src="" alt="Product Image">
            </div>

            <div class="modal-right">
                <span class="close-modal">&times;</span>
                <p class="modal-brand">TRINITY</p>
                <h2 id="modal-name"></h2>
                <p id="modal-price"></p>

                <div class="size-select">
                    <p>Size</p>
                    <div class="sizes">
                        <label for="S-size">S</label>
                        <label for="M-size">M</label>        
                        <label for="L-size">L</label>        
                        <label for="XL-size">XL</label>
                    </div>
                </div>

                <div class="color-select">
                    <p>Color</p>
                    <div class="colors grid grid-cols-3 md:grid-cols-4 sm:gx-3"></div>
                </div>

                <div id="form-container">
                    <form id="addCartForm">     
                        <input type="hidden" name="product_id" id="modal-product-id">
                        <input type="hidden" name="product_category" id="modal-product-category">
                        <input type="hidden" name="product_color" id="modal-product-color">

                        <input type="radio" name="cart_size" value="S" id="S-size" hidden checked>
                        <input type="radio" name="cart_size" value="M" id="M-size" hidden>
                        <input type="radio" name="cart_size" value="L" id="L-size-" hidden>
                        <input type="radio" name="cart_size" value="XL" id="XL-size" hidden> 

                        <button class="modal-add add-cart-btn-big">ADD TO CART</button>
                    </form>

                    <div id="Try-on-form">
                        <button class="modal-try" type="button">Try with AI✨</button>
                        <div id="tooltip-explain">
                            <h3>Virtual AI Try On</h3>
                            <span>This is an feature for customers to try on our product</span>
                        </div>
                    </div>
                </div>

                <div id="modal-detail" onclick="window.location.href='detail.php?id=<?=$p['id']?>'">Details</div>
            </div>
        </div>

    </div>

<script>
        //Username
        const email = <?= isset($_SESSION['username']) ? json_encode($_SESSION['username']) : '""' ?>;
        let username1 = email.split("@")[0] || "";
        let displayName = username1.length > 6
        ? username1.substring(0, 6) + "..."
        : username1;
        const userWelcome = document.querySelectorAll(".menu-Username");

        if(userWelcome){
            userWelcome.forEach(user => user.textContent = "Hi, " + displayName);
        }
        
        //Head observe
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
        headObserve.observe(head);

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

        //Animate class add on Viewport
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

        //Card modal popup
        const products = document.querySelectorAll(".group.cursor-pointer");

        const conModal = document.querySelector(".modal-container");
        const modal = document.getElementById("product-modal");
        const modalImg = document.getElementById("modal-img");
        const modalName = document.getElementById("modal-name");
        const modalPrice = document.getElementById("modal-price");
        const modalColor = document.querySelector(".colors");

        const modalProductId = document.getElementById("modal-product-id");
        const modalProductCategory = document.getElementById("modal-product-category");
        const modalProductColor = document.getElementById("modal-product-color");
        const modalAddCart = document.querySelector(".modal-add");
        const sizeAdd = document.querySelectorAll(".sizes label");

        products.forEach(product => {
            product.addEventListener('click', function(){

              //Size reset
              sizeAdd.forEach(size =>{
                size.style.color = "black";
                size.style.background = "white";
              });

              //Stock
              if(this.dataset.stock <= 0){
                modalAddCart.disabled = true;
                modalAddCart.style.background = "gray";
                modalAddCart.textContent = "OUT OF STOCK";
              }else{
                modalAddCart.disabled = false;
                modalAddCart.style.background = "";
                modalAddCart.textContent = "ADD TO CART";
              }

              //Modal info
              let modalId = "";
              const modalVariant = this.querySelectorAll(".variants");
              modalImg.src = this.dataset.img;
              modalId.value = this.dataset.id;
              modalName.textContent = this.dataset.name.toUpperCase();
              modalPrice.textContent = "$" + this.dataset.price;
              modalProductId.value = this.dataset.id;
              modalProductCategory.value = this.dataset.category;
              modalProductColor.value = this.dataset.color;
              modal.style.setProperty("display", "flex", "important");


              //Render label color
              let htmlModal = "";
              modalVariant.forEach((variant) =>{


                const isActive = variant.classList.contains("active");

                const activeClasses = isActive ? "color active bg-black text-white border-black" : "color bg-white text-black hover:bg-black hover:text-white";

                const inlineStyle = isActive ? "color: white; background: black;" : "color: black; background: white;";

                htmlModal += `
                    <label class="${activeClasses} color text-xs md:text-sm uppercase border-solid border-black/20 border p-1 md:p-2 mr-2 mb-2 text-center cursor-pointer transition-all duration-300 hover:bg-black hover:text-white"
                           data-id="${variant.dataset.id}"
                           data-variant="${variant.dataset.variant}"
                           data-img="${variant.dataset.img}"
                           data-name="${variant.dataset.name}"
                           data-price="${variant.dataset.price}"
                           data-stock="${variant.dataset.stock}">${variant.dataset.variant}</label>
                `;
              });

              modalColor.innerHTML = htmlModal;


              //Size select
              let modalSize = "S";
              sizeAdd.forEach(label =>
                    label.addEventListener('click', ()=>{
                        modalSize = label.textContent;
                        sizeAdd.forEach(label =>{
                            label.style.color = "black";
                            label.style.background = "white";
                            
                        });

                        label.style.color = "white";
                        label.style.background = "black";
                    })
                );

              //Color select
              const colorAdd = document.querySelectorAll(".colors label");

                colorAdd.forEach(color => {
                    color.addEventListener('click', ()=>{
                        colorAdd.forEach(color =>{
                            color.style.color = "black";
                            color.style.background = "white";
                        });

                    color.style.color = "white";
                    color.style.background = "black";
                    });
                });



                //Color button change
                const outerColorBtn = document.querySelectorAll(".colors label");

                outerColorBtn.forEach(Btn =>{
                    Btn.addEventListener('click', function(e){
                        e.stopPropagation();

                        //Stock
                        if(this.dataset.stock <= 0){
                            modalAddCart.disabled = true;
                            modalAddCart.style.background = "gray";
                            modalAddCart.textContent = "OUT OF STOCK";
                        }else{
                            modalAddCart.disabled = false;
                            modalAddCart.style.background = "";
                            modalAddCart.textContent = "ADD TO CART";
                        } 

                        const vImg = this.dataset.img;
                        const baseName = this.dataset.name;
                        const vColor = this.dataset.variant;

                        modalImg.src = vImg;
                        modalName.textContent = baseName.toUpperCase();
                        modalPrice.textContent = "$" + product.dataset.price;
                        modalProductId.value = product.dataset.id;
                        modalProductCategory.value = product.dataset.category;
                        modalProductColor.value = vColor;

                        modal.style.setProperty("display", "flex", "important");

                    });
                });

                //Add cart
                modalAddCart.addEventListener('click', function(e){
                    e.preventDefault();
                    console.log(modalProductColor.value);
                    fetch('../Database/add_item_to_cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `product_category=${modalProductCategory.value}&product_color=${modalProductColor.value}&cart_size=${modalSize}&product_id=${parseInt(modalProductId.value)}`
                    })
                    .catch(error => {
                        console.error('Error updating cart:', error);
                    });
                });

            });
        });        

        //Card modal close
        const closeBtn = document.querySelector(".close-modal");
        closeBtn.addEventListener('click', ()=>{
            modal.style.display = "none";
        });

        modal.addEventListener('click', function(e){
            if(!conModal.contains(e.target)) modal.style.display = "none";
        });


        //Button Next - Previous
        document.querySelector(".next").addEventListener('click', function(){
            const products = document.querySelectorAll(".product");
            products.forEach(product =>{
                product.style.animation = "none";
                product.style.opacity = "1";
                const width = product.offsetWidth;
                let gap = 100;
                product.style.transform = `translateX(-${(width*5) + gap}px)`;
            });
        });

        document.querySelector(".previous").addEventListener('click', function(){
            const products = document.querySelectorAll(".product");
            products.forEach(product =>{
                product.style.animation = "none";
                product.style.opacity = "1";
                const width = product.offsetWidth;
                let gap = 100;
                product.style.transform = `translateX(${0}px)`;
            });
        });

        


        //Fast menu 
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
</script>
</body>
</html>