<?php require "../component/home/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRINITY - Cultivating Authentic Apparel</title>
    <!--TAILWIND CSS & CSS-->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../Css/nav.css">
    <link rel="stylesheet" href="../Css/home.css">
    <!--GG FONT & ICON-->
     <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f3ef] text-[#1a1a1a] antialiased w-full">

    <section id="head" class="relative w-full bg-[#ebeae4] px-6 py-8 md:py-16 min-h-[90vh] flex flex-col justify-between overflow-hidden">
        <div class="absolute w-[100%] h-[100%] bg-black z-[1001] left-0 top-0 transition-all duration-300 animate-on-scroll black-screen"></div>

        <div class="w-full max-w-7xl mx-auto flex flex-col justify-between h-full flex-1">
            <div class="my-auto py-12 flex flex-col items-center text-center w-full">
                <h1 class="text-2xl md:text-4xl font-light tracking-[0.15em] opacity-0 transiton-all duration-300 translate-y-[-40px] leading-snug max-w-2xl uppercase animate-on-scroll head-h1">
                    Cultivating Authentic Apparel Through Meaningful Design.
                </h1>
                <p class="text-xs md:text-sm text-gray-500 max-w-md mt-4 opacity-0 transiton-all duration-300 translate-y-[-40px] leading-relaxed font-light animate-on-scroll head-p">
                    We partner with visionary companies to build a modern legacy of essential shirts.
                </p>

                <div class="relative w-90 h-90 my-10 flex items-center justify-center animate-on-scroll head-img-container">
                    <img class="w-[100%] h-[100%] object-cover translate-y-[150%]" src="../Pictures/Banners/BannerImg-1.png" alt="">
                </div>

                <button class="border border-black text-xs tracking-[0.2em] px-8 py-3.5 uppercase bg-transparent hover:bg-black hover:text-white transition-colors duration-300">
                    Our Collections
                </button>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-100">
        <div class="">
            <?php require "../component/home/feat.php" ?>
        </div>
    </section>

    <section class="w-full bg-[#f4f3ef] px-6 py-12 md:py-20 border-t border-gray-300/40">
        <div class="w-full max-w-7xl mx-auto">
            <h2 class="text-xl tracking-[0.2em] uppercase mb-6 font-light">Our Principles</h2>
            
            <div class="relative bg-stone-300 w-full h-72 md:h-96 mb-4 relative flex items-end p-6 overflow-hidden translate-y-[40px] opacity-0 principle-img-container animate-on-scroll">
                <img class="absolute w-full h-full object-cover top-0 left-0" src="../Pictures/Banners/Section-2-Img.png" alt="">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                <div class="w-full space-y-2 z-10">
                    <div class="h-8 bg-stone-400/80 w-1/2 shadow-md"></div>
                    <div class="h-8 bg-stone-500/80 w-2/3 shadow-md"></div>
                    <div class="h-8 bg-stone-600/80 w-1/3 shadow-md"></div>
                </div>
            </div>

            <p class="text-xs tracking-widest uppercase text-gray-400 mb-12">Our Fabrics: A Journey in Shirting Textures</p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-on-scroll principle">
                <div class="principle-child bg-stone-200 aspect-square relative p-2 opacity-0">
                    <img class="absolute w-full h-full object-cover top-0 left-0" src="../Pictures/Banners/Section-2-Img-child-1.png" alt="">
                    <span class="text-xs text-black-400 relative z-[100]">1</span>
                </div>

                <div class="principle-child bg-stone-200 aspect-square relative p-2 opacity-0 translate-x-[-105%]">
                    <img class="absolute w-full h-full object-cover top-0 left-0" src="../Pictures/Banners/Section-2-Img-child-2.png" alt="">
                    <span class="text-xs text-black-400 relative z-[100]">2</span>
                </div>

                <div class="principle-child bg-stone-200 aspect-square relative p-2 opacity-0 translate-x-[-105%]">
                    <img class="absolute w-full h-full object-cover top-0 left-0" src="../Pictures/Banners/Section-2-Img-child-3.png" alt="">
                    <span class="text-xs text-black-400 relative z-[100]">3</span>
                </div>

                <div class="principle-child bg-stone-200 aspect-square relative p-2 opacity-0 translate-x-[-105%]">
                    <img class="absolute w-full h-full object-cover top-0 left-0" src="../Pictures/Banners/Section-2-Img-child-4.png" alt="">
                    <span class="text-xs text-black-400 relative z-[100]">4</span>
                </div>
            </div>
            
            <div class="mt-8 flex justify-center opacity-25">
                <div class="w-32 h-5 bg-gradient-to-r from-transparent via-stone-600 to-transparent blur-[2px]"></div>
            </div>
        </div>
    </section>

    <section class="w-full bg-[#ecebe5] px-6 py-12 md:py-20 border-t border-gray-300/40 principle-text-section animate-on-scroll">
        <div class="w-full max-w-4xl mx-auto">
            <h2 class="text-xs tracking-[0.2em] uppercase mb-10 text-gray-400 font-medium">Our Principles</h2>
            
            <div class="space-y-8 md:space-y-12">

                <div class="flex items-start gap-6 md:gap-10 principle-text-section-child">
                    <span class="text-2xl font-light tracking-wider text-stone-400">01</span>
                    <div>
                        <h4 class="text-sm md:text-base font-semibold tracking-wide uppercase">Simplicity</h4>
                        <p class="text-xs md:text-sm text-stone-500 mt-1">We are maanixe to wearability.</p>
                    </div>
                </div>

                <div class="flex items-start gap-6 md:gap-10 principle-text-section-child">
                    <span class="text-2xl font-light tracking-wider text-stone-400">02</span>
                    <div>
                        <h4 class="text-sm md:text-base font-semibold tracking-wide uppercase">Function</h4>
                        <p class="text-xs md:text-sm text-stone-500 mt-1">Covansens the apparel to cut.</p>
                    </div>
                </div>

                <div class="flex items-start gap-6 md:gap-10 principle-text-section-child">
                    <span class="text-2xl font-light tracking-wider text-stone-400">03</span>
                    <div>
                        <h4 class="text-sm md:text-base font-semibold tracking-wide uppercase">Elegance</h4>
                        <p class="text-xs md:text-sm text-stone-500 mt-1">Generous leading, artisand meanings.</p>
                    </div>
                </div>

                <div class="flex items-start gap-6 md:gap-10 principle-text-section-child">
                    <span class="text-2xl font-light tracking-wider text-stone-400">04</span>
                    <div>
                        <h4 class="text-sm md:text-base font-semibold tracking-wide uppercase">Meaningful</h4>
                        <p class="text-xs md:text-sm text-stone-500 mt-1">We crede nioapillas and thierarchy.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="max-w-[1200px] mx-auto text-center my-[100px]">
        
        <div class="mb-10 md:mb-14">
            <div class="text-[14px] font-medium tracking-[6px] uppercase text-[#8c8c8c] mb-3">
                Trinity
            </div>
            <h2 class="text-2xl md:text-3xl font-normal tracking-[4px] uppercase text-[#1a1a1a] m-0">
                From Workshop to Your Hands
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10">
            
            <div class="flex flex-col items-center">
                <div class="w-full aspect-[1.18/1] overflow-hidden mb-6 bg-gray-100">
                    <img class="hover:scale-[1.2] transition-all duration-[4s] cursor-pointer" src="../Pictures/Banners/F1-I1.png" alt="Carefully selecting materials" class="w-full h-full object-cover block">
                </div>
                <p class="text-[12px] font-normal leading-relaxed tracking-[1.5px] uppercase text-[#4a4a4a] px-2 m-0">
                    Carefully selecting the finest pieces of premium leather
                </p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-full aspect-[1.18/1] overflow-hidden mb-6 bg-gray-100">
                    <img class="hover:scale-[1.2] transition-all duration-[4s] cursor-pointer" src="../Pictures/Banners/F1-I2.png" alt="Quality inspection" class="w-full h-full object-cover block">
                </div>
                <p class="text-[12px] font-normal leading-relaxed tracking-[1.5px] uppercase text-[#4a4a4a] px-2 m-0">
                    Thoroughly inspecting every single product before it reaches the customer
                </p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-full aspect-[1.18/1] overflow-hidden mb-6 bg-gray-100">
                    <img class="hover:scale-[1.2] transition-all duration-[4s] cursor-pointer" src="../Pictures/Banners/F1-I3.png"  alt="Delivering pride" class="w-full h-full object-cover block">
                </div>
                <p class="text-[12px] font-normal leading-relaxed tracking-[1.5px] uppercase text-[#4a4a4a] px-2 m-0">
                    The final product is the ultimate pride of Trinity when delivered to you
                </p>
            </div>

        </div>
    </section>

    <section class="w-full bg-[#f0eee7] px-6 py-12 md:py-20 border-t border-gray-300/40">
        <div class="w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20 items-start">
            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-gray-400 mb-6">GET NEWEST INFORMARTION AND DEALS</p>
                <div class="grid grid-cols-2 gap-6 text-sm font-medium tracking-widest text-stone-600">
                    <span>Just by fill in your contact</span>
                </div>
            </div>

            <div>
                <p class="text-xs tracking-[0.2em] uppercase text-gray-400 mb-6">Contact</p>
                <form class="contact-form space-y-4" onsubmit="event.preventDefault();">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" placeholder="NAME" class="name w-full bg-white border border-stone-300/60 p-3 text-xs tracking-widest focus:outline-none focus:border-black transition-colors">
                        <input type="email" placeholder="EMAIL" class="email w-full bg-white border border-stone-300/60 p-3 text-xs tracking-widest focus:outline-none focus:border-black transition-colors">
                    </div>
                    <textarea placeholder="MESSAGE" rows="3" class="more w-full bg-white border border-stone-300/60 p-3 text-xs tracking-widest focus:outline-none focus:border-black transition-colors"></textarea>
                    <button class="contact-submitBtn w-full bg-black text-white text-xs tracking-[0.25em] py-3.5 uppercase font-medium hover:bg-stone-800 transition-colors">
                        Contact Us
                    </button>
                </form>
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
                <span onclick="window.location.href='#head'">Home</span>
                <span onclick="window.location.href='products.php'">Shop</span>
                <span onclick="window.location.href='products.php'">Collection</span>
                <span onclick="window.location.href='contact.php'">Contact</span>
            </div>

            <div id="logo" onclick="window.location.href='#head'">TRINITY</div>
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

    <footer class="w-full bg-[#1a1a1a] text-stone-400 px-6 py-12 md:py-16 text-xs tracking-wider">
        <div class="w-full max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            <div>
                <span class="text-white font-medium text-sm tracking-[0.2em] block mb-2">TRINITY</span>
                <span class="text-stone-500 block text-[10px] uppercase">Design • Branding Agency</span>
            </div>
            <div>
                <span class="text-stone-500 block mb-2 uppercase text-[10px]">Address</span>
                <p class="leading-relaxed text-stone-300">Dong Thanh,<br>Hoc Mon</p>
            </div>
            <div>
                <span class="text-stone-500 block mb-2 uppercase text-[10px]">Get in touch</span>
                <p class="leading-relaxed text-stone-300">triple3Tbusiness@gmail.com</p>
            </div>
            <div>
                <span class="text-stone-500 block mb-2 uppercase text-[10px]">Social</span>
                <div class="space-y-1 text-stone-300">
                    <a href="#" class="block hover:text-white transition-colors">Instagram</a>
                    <a href="#" class="block hover:text-white transition-colors">LinkedIn</a>
                    <a href="#" class="block hover:text-white transition-colors">Behance</a>
                </div>
            </div>
        </div>
    </footer>

<script src="../asset/contact.js"></script>
<script src="../asset/headerEmail.js"></script>
<script src="../asset/homeJS/home.js"></script>
</body>
</html> 