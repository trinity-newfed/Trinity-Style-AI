<?php require "../component/products/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop All - TRINITY</title>
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
                <span onclick="window.location.href='search.php?content=collections'">Collection</span>
                <span onclick="window.location.href='contact.php'">Contact</span>
            </div>

            <div id="logo" onclick="window.location.href='../Pages/'">TRINITY</div>
        </div>
        
        <div id="utility-menu">
            <div class="relative">
                <svg class="icon cart" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="21px" onclick="window.location.href='cart.php'">
                    <path d="M200-80q-33 0-56.5-23.5T120-160v-480q0-33 23.5-56.5T200-720h80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720h80q33 0 56.5 23.5T840-640v480q0 33-23.5 56.5T760-80H200Zm0-80h560v-480H200v480Zm421.5-298.5Q680-517 680-600h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85h-80q0 83 58.5 141.5T480-400q83 0 141.5-58.5ZM360-720h240q0-50-35-85t-85-35q-50 0-85 35t-35 85ZM200-160v-480 480Z"/>
                </svg>
                <span class="absolute top-[-5px] right-[-5px] bg-red-400 text-white rounded-full w-[14px] h-[14px] text-[7px] flex items-center justify-center"><?=$noti?></span>
            </div>

            <svg class="icon search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376C296.3 401.1 253.9 416 208 416 93.1 416 0 322.9 0 208S93.1 0 208 0 416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
            </svg>
            <?php require "../component/menu.php" ?>
        </div>

        <div id="fast-menu">
            <div id="fast-menu-container">
                <div class="menu-item">
                    <div class="menu-title"><span>TRINITY</span></div>

                    <div class="submenu">
                        
                        <div class="submenu-item">T-shirt
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Basic T-shirt'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Oversize T-shirt'">Oversize</div>
                        </div>

                        <div class="submenu-item">Polo shirt
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Basic Polo'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Logo Polo'">Logo</div>
                        </div>

                        <div class="submenu-item">Hoodie
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Hoodie'">Signature</div>
                        </div>
                    </div>
                </div>

                <div class="menu-item">
                    <div class="menu-title"><span>TRINITY LADIES</span></div>

                    <div class="submenu">

                        <div class="submenu-item">Blouse
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Classic Blouse'">Classic</div>
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Wrap Blouse'">Warp</div>
                        </div>

                        <div class="submenu-item">Crop top
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Basic Crop Top'">Basic</div>
                            <div class="sub-sub" onclick="window.location.href='search.php?content=Tank Crop Top'">Tank</div>
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

                <?php require "../component/menu2.php" ?>
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
                    <?php require "../component/base.php" ?>
                </div>   

                <button id="searchBtn" onclick="window.location.href='products.php'"><p>View All Products</p></button>
            </div>
        </div>

    </section>

    <section id="head" class="relative bg-[#E5E5E5] overflow-hidden min-h-[500px] md:h-[100vh] flex items-center">
        <div class="absolute bg-[black] z-[100] w-[100%] h-[100%] animate-1"></div>
        <div class="absolute inset-0 bg-cover bg-center opacity-90">
            <img class="object-cover w-[100%] h-[100%] animate-2" src="../Pictures/Banners/Product-Banner.png" alt="">
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full text-center py-20 z-10">
            <h1 class="text-4xl md:text-7xl font-serif-custom tracking-widest uppercase mb-4 text-gray-900">
                READY-TO-WEAR
            </h1>
            <p class="text-sm md:text-base text-gray-700 max-w-md mx-auto mb-8 font-light tracking-wide">
                Contemporary silhouettes<br>crafted for the modern individual.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4 text-xs tracking-widest uppercase">
                <a href="search.php?content=collections" class="bg-black text-white px-8 py-3.5 hover:bg-transparent border-black transition">Explore Collection</a>
                <a href="search.php?content=new" class="border border-black text-black px-8 py-3.5 hover:bg-black hover:text-white transition">New Arrivals</a>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex justify-between items-baseline mb-8">
            <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">Featured Collection</h2>
            <a href="#" class="text-xs uppercase tracking-widest text-gray-500 hover:text-black underline underline-offset-4">View All</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8 collections animate-on-scroll">
            <?php require "../component/products/product.php" ?>
        </div>
    </section>

    <section class="grid grid-cols-1 md:grid-cols-2 bg-[#F9F9F9] items-center">
        <div class="h-96 md:h-[600px] w-full bg-cover bg-center" style="background-image: url('../Pictures/Banners/Products-Section-3-Img.png');"></div>
        <div class="p-12 md:p-24 text-center md:text-left">
            <h2 class="text-3xl font-serif-custom tracking-widest uppercase mb-4">Tailoring Redefined</h2>
            <p class="text-xs text-gray-600 tracking-wide max-w-sm mb-8 leading-relaxed">
                Precision cuts, elevated textures, and timeless forms designed beyond seasonal trends.
            </p>
            <a href="search.php?content=all" class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition">Discover More</a>
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
            <?php require "../component/products/classic.php" ?>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <div class="h-[450px] bg-cover bg-center" style="background-image: url('../Pictures/Banners/Products-Section-5-Img-1.png');"></div>
        <div class="h-[450px] bg-cover bg-center" style="background-image: url('../Pictures/Banners/Products-Section-5-Img-2.png');"></div>
        <div class="p-4">
            <span class="text-[10px] tracking-widest text-gray-400 uppercase block mb-2">Hot Collection</span>
            <hr class="w-12 border-black mb-6">
            <p class="text-xs text-gray-600 leading-relaxed tracking-wide mb-8">
                A curated drop featuring structured tailoring and contemporary essentials inspired by urban architecture.
            </p>
            <a href="search.php?content=collections" class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition">View Collection</a>
        </div>
    </section>

<footer class="bg-white text-gray-600 font-sans border-t border-gray-100">
  <div class="max-w-7xl mx-auto px-4 py-12 sm:px-6 lg:px-8 border-b border-gray-100">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      
      <div class="flex flex-col items-center group">
        <div class="text-gray-800 group-hover:text-amber-500 transition-colors duration-300 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5h-9l-3.036-5.06A1.242 1.242 0 0 0 7.915 1.75H2.25A2.25 2.25 0 0 0 0 4v13.5A2.25 2.25 0 0 0 2.25 19.75h1.5a2.25 2.25 0 0 0 4.5 0h7.5a2.25 2.25 0 0 0 4.5 0h1.5a2.25 2.25 0 0 0 2.25-2.25V9.75A2.25 2.25 0 0 0 21 7.5Zm-13.5 12.25a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm12 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5M12 3.75v16.5M3.75 6.75h16.5M3.75 17.25h16.5" />
          </svg>
        </div>
        <h4 class="text-gray-900 font-medium tracking-widest text-xs uppercase">Nationwide Free Shipping</h4>
        <p class="text-xs text-gray-400 mt-1.5">For orders from 499K</p>
      </div>

      <div class="flex flex-col items-center group">
        <div class="text-gray-800 group-hover:text-amber-500 transition-colors duration-300 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0-2.625V7.5m0 0h5.25c1.243 0 2.25-1.007 2.25-2.25h-1.5a1.125 1.125 0 0 0-1.125-1.125h-4.875c-.621 0-1.125.504-1.125 1.125H3.75a1.125 1.125 0 0 0-1.125 1.125H7.5c0 1.243 1.007 2.25 2.25 2.25H12" />
          </svg>
        </div>
        <h4 class="text-gray-900 font-medium tracking-widest text-xs uppercase">Premium Gift Wrapping</h4>
        <p class="text-xs text-gray-400 mt-1.5">Luxurious & meaningful</p>
      </div>

      <div class="flex flex-col items-center group">
        <div class="text-gray-800 group-hover:text-amber-500 transition-colors duration-300 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5H3M21 12H3m18 4.5H3M19.5 4.5h-15a1.5 1.5 0 0 0-1.5 1.5v12a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5v-12a1.5 1.5 0 0 0-1.5-1.5Z" />
          </svg>
        </div>
        <h4 class="text-gray-900 font-medium tracking-widest text-xs uppercase">100% Authentic Products</h4>
        <p class="text-xs text-gray-400 mt-1.5">Exclusively crafted by TRINITY</p>
      </div>

      <div class="flex flex-col items-center group">
        <div class="text-gray-800 group-hover:text-amber-500 transition-colors duration-300 mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.151-.416.719-.416.87 0l2.428 6.666a.426.426 0 0 0 .375.281l7.103.65c.451.041.631.597.292.898l-5.372 4.792a.426.426 0 0 0-.129.398l1.583 6.95c.101.442-.38.791-.767.558l-6.19-3.738a.426.426 0 0 0-.44 0l-6.19 3.738c-.387.233-.868-.116-.767-.558l1.583-6.95a.426.426 0 0 0-.129-.398L.141 12.834c-.339-.301-.159-.857.292-.898l7.103-.65a.426.426 0 0 0 .375-.281l2.428-6.666Z" />
          </svg>
        </div>
        <h4 class="text-gray-900 font-medium tracking-widest text-xs uppercase">Transparent Returns & Warranty</h4>
        <p class="text-xs text-gray-400 mt-1.5">Clear policies, zero hassle</p>
      </div>

    </div>
  </div>

  <div class="max-w-7xl mx-auto px-4 py-16 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
      
      <div>
        <h3 class="text-gray-900 font-medium tracking-widest text-xs uppercase mb-4">Exclusive Offers from TRINITY</h3>
        <p class="text-sm text-gray-400 mb-6 leading-relaxed">Get 10% off on your first order when you subscribe to our newsletter.</p>
        <form class="contact-form space-y-3 max-w-sm">
          <input type="email" placeholder="Email Address" required 
                 class="email w-full px-4 py-3 bg-white border border-gray-200 text-sm focus:outline-none focus:border-gray-900 placeholder-gray-300 transition-colors" />
          <button type="submit" 
                  class="contact-submitBtn w-full bg-gray-600 hover:bg-gray-900 text-white font-medium text-xs tracking-widest uppercase py-3 transition-colors duration-300">
            Contact Us
          </button>
        </form>
        
        <div class="flex space-x-6 mt-8 text-gray-400">
          <a href="#" class="hover:text-gray-900 transition-colors duration-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
          </a>
          <a href="#" class="hover:text-gray-900 transition-colors duration-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.082 2h-.621c-2.42 0-2.743.012-3.71.054-.939.042-1.449.2-1.766.325a3.63 3.63 0 00-1.344.875 3.63 3.63 0 00-.875 1.344c-.125.317-.283.827-.325 1.766-.041.947-.054 1.29-.054 3.71v.621c0 2.42.012 2.743.054 3.71.042.939.2 1.449.325 1.766.23.596.548 1.106.974 1.53.424.424.934.742 1.53.974.317.125.827.283 1.766.325.967.041 1.29.054 3.71.054h.621c2.42 0 2.743-.012 3.71-.054.939-.042 1.449-.2 1.766-.325.596-.23 1.106-.548 1.53-.974.424-.424.742-.934.974-1.53.125-.317.283-.827.325-1.766.041-.967.054-1.29.054-3.71v-.621c0-2.42-.012-2.743-.054-3.71-.042-.939-.2-1.449-.325-1.766a3.63 3.63 0 00-.875-1.344 3.63 3.63 0 00-.125-.317c-.317-.125-.827-.283-1.766-.325C15.115 4.012 14.773 4 12.35 4h-.082zM12 7.682a4.318 4.318 0 100 8.636 4.318 4.318 0 000-8.636zM12 14a2 2 0 110-4 2 2 0 010 4zm5.884-7.804a.836.836 0 100-1.672.836.836 0 000 1.672z"/></svg>
          </a>
          <a href="#" class="hover:text-gray-900 transition-colors duration-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.043 2.62-.053 3.91-.017.38.01.76.047 1.13.11.84.144 1.57.564 2.11 1.2a4.84 4.84 0 0 1 1.07 2.19c.14.65.2 1.32.22 1.99.04 1.53.04 3.07 0 4.6-.02.67-.08 1.34-.22 1.99a4.84 4.84 0 0 1-1.07 2.19 4.98 4.98 0 0 1-2.11 1.2c-.37.063-.75.1-1.13.11-1.29.036-2.6.026-3.91-.017m-1.05.003c-1.31.043-2.62.053-3.91.017a4.65 4.65 0 0 1-1.13-.11 4.84 4.84 0 0 1-2.11-1.2 4.84 4.84 0 0 1-1.07-2.19c-.14-.65-.2-1.32-.22-1.99-.04-1.53-.04-3.07 0-4.6.02-.67.08-1.34.22-1.99A4.84 4.84 0 0 1 4.22 1.52c.54-.636 1.27-1.056 2.11-1.2.37-.063.75-.1 1.13-.11 1.29-.036 2.6-.026 3.91.017"/></svg>
          </a>
        </div>
      </div>

      <div>
        <h3 class="text-gray-900 font-medium tracking-widest text-xs uppercase mb-4">Contact Us</h3>
        <ul class="space-y-4 text-sm text-gray-500">
          <li>
            <span class="block text-xs font-semibold text-gray-900 uppercase tracking-wider mb-0.5">Sales Hotline</span>
            <span class="block text-xs text-gray-400">Hours: 8:00 AM - 9:00 PM Daily</span>
          </li>
          <li>
            <span class="block text-xs font-semibold text-gray-900 uppercase tracking-wider mb-0.5">Feedback & Claims</span>
            <a href="email:triple3tbusiness@gmail.com" class="hover:text-gray-900 underline underline-offset-4 decoration-gray-200 transition-colors">triple3tbusiness@gmail.com</a>
            <span class="block text-xs text-gray-400">Hours: 8:00 AM - 5:00 PM (Mon - Sat)</span>
          </li>
          <li>
            <span class="block text-xs font-semibold text-gray-900 uppercase tracking-wider mb-0.5">Email Support</span>
            <a href="mailto:contact@TRINITY.vn" class="hover:text-gray-900 underline underline-offset-4 decoration-gray-200 transition-colors">trinitysupport@gmail.com</a>
          </li>
        </ul>
      </div>

      <div class="flex justify-between">
        <div>
          <h3 class="text-gray-900 font-medium tracking-widest text-xs uppercase mb-4">Information</h3>
          <ul class="space-y-2.5 text-sm">
            <li><a href="about.php" class="hover:text-gray-900 transition-colors">About Us</a></li>
            <li><a href="../legal/privacy-policy.php" class="hover:text-gray-900 transition-colors">Privacy Policy</a></li>
            <li><a href="../legal/delivery-policy.php" class="hover:text-gray-900 transition-colors">Delivery Policy</a></li>
            <li><a href="../ai-usage-policy.php" class="hover:text-gray-900 transition-colors">AI Usage Policy</a></li>
            <li><a href="../warranty-policy.php" class="hover:text-gray-900 transition-colors">Warranty Policy</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-gray-900 font-medium tracking-widest text-xs uppercase mb-4">Quick Link</h3>
          <ul class="space-y-2.5 text-sm">
            <li><a href="../Pages/" class="hover:text-gray-900 transition-colors">Home</a></li>
            <li><a href="products.php" class="hover:text-gray-900 transition-colors">Products</a></li>
            <li><a href="voucher.php" class="hover:text-gray-900 transition-colors">Exclusive Offers</a></li>
            <li><a href="userTier.php" class="hover:text-gray-900 transition-colors">Membership Status</a></li>
          </ul>
        </div>

      </div>

    </div>
  </div>

  <div class="bg-gray-50 border-t border-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-2">
      <p class="text-[11px] font-medium tracking-widest text-gray-400 uppercase">
        &copy; 2026 - TRINITY
      </p>
    </div>
  </div>
</footer>


    <div id="product-modal">

        <div class="modal-container">
            <span class="close-modal z-[101]">&times;</span>
            <div class="modal-left">
                <img id="modal-img" src="" alt="Product Image">
            </div>

            <div class="modal-right">
                <h2 id="modal-name" class="font-normal opacity-[0.8] text-[24px] lg:text-[32px]"></h2>
                <p id="modal-price" class="w-[100%] font-light text-[14px] lg:text-[24px] pb-2 sm:pb-5 border-b border-[rgba(0,0,0,0.1)]"></p>

                <div>
                    <div class="size-select">
                        <p class="pt-3 lg:pt-4 pb-1">Size</p>
                        <div class="sizes flex gap-2">
                            <label for="S-size" class="active text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">S</label>
                            <label for="M-size" class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">M</label>        
                            <label for="L-size" class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">L</label>        
                            <label for="XL-size" class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">XL</label>
                        </div>
                    </div>

                    <div class="color-select">
                        <p class="pt-3 lg:pt-4 pb-1">Color</p>
                        <div class="colors grid grid-cols-3 lg:grid-cols-4 gap-x-1 sm:gap-x-2"></div>
                    </div>
                </div>

                <div class="flex gap-2 sm:gap-3 pt-2 sm:pt-3 mt-10 w-[100%]">
                    <button class="w-[50%] modal-add text-[0.7rem] sm:text-[0.9rem] p-1 sm:p-2">ADD TO CART</button>
                    <button class="w-[50%] modal-try text-[0.7rem] sm:text-[0.9rem] p-1 sm:p-2" type="button">TRY WITH AI✨</button>
                </div>

                <div class="modal-detail cursor-pointer hover:underline pt-2 sm:pt-4">Details</div>
            </div>
        </div>

    </div>

    <div class="toast opacity-0 invisible translate-y-[100%] transition-all duration-300 fixed max-w-[250px] h-[40px] bg-[#000000] text-[#ffffff] bottom-[0] p-2 sm:p-4 flex justify-center items-center gap-2">
        <span>Item added to bag</span>
        <button class="underline" onclick="window.location.href='cart.php'">View</button>
    </div>

<script src="../asset/contact.js"></script>
<script src="../asset/headerEmail.js"></script>
<script src="../asset/productsJS/products.js"></script>
<script src="../asset/search.js"></script>
</body>
</html>