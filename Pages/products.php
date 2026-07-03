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
  <link
    href="https://fonts.googleapis.com/css2?family=Birthstone&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&family=Playwrite+NO:wght@100..400&display=swap"
    rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .font-serif-custom {
      font-family: 'Playfair Display', serif;
    }
  </style>
</head>

<body class="bg-white text-black antialiased isolate" id="body">

  <?php require "../component/sectionMenu.php" ?>

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
        <a href="search.php?content=collections"
          class="bg-black text-white px-8 py-3.5 hover:bg-transparent border-black transition">Explore Collection</a>
        <a href="search.php?content=new"
          class="border border-black text-black px-8 py-3.5 hover:bg-black hover:text-white transition">New Arrivals</a>
      </div>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex justify-between items-baseline mb-8">
      <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">Featured Collection</h2>
      <a href="search.php?content=collections"
        class="text-xs uppercase tracking-widest text-gray-500 hover:text-black underline underline-offset-4">View
        All</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8 collections animate-on-scroll">
      <?php require "../component/products/product.php" ?>
    </div>
  </section>

  <section class="grid grid-cols-1 md:grid-cols-2 bg-[#F9F9F9] items-center">
    <div class="h-96 md:h-[600px] w-full bg-cover bg-center"
      style="background-image: url('../Pictures/Banners/Products-Section-3-Img.png');"></div>
    <div class="p-12 md:p-24 text-center md:text-left">
      <h2 class="text-3xl font-serif-custom tracking-widest uppercase mb-4">Tailoring Redefined</h2>
      <p class="text-xs text-gray-600 tracking-wide max-w-sm mb-8 leading-relaxed">
        Precision cuts, elevated textures, and timeless forms designed beyond seasonal trends.
      </p>
      <a href="search.php?content=all"
        class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition">Discover
        More</a>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-100">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">Best Sellers</h2>
      <div class="flex space-x-2">
        <button class="previous p-1 border border-gray-200 rounded-full hover:border-black"><svg class="w-4 h-4"
            fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
          </svg></button>
        <button class="next p-1 border border-gray-200 rounded-full hover:border-black"><svg class="w-4 h-4" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path>
          </svg></button>
      </div>
    </div>

    <div
      class="flex overflow-x-auto overflow-y-hidden gap-x-5 max-w-[100%] scrollbar-hide hide products animate-on-scroll">
      <?php require "../component/products/classic.php" ?>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
    <div class="h-[450px] bg-cover bg-center"
      style="background-image: url('../Pictures/Banners/Products-Section-5-Img-1.png');"></div>
    <div class="h-[450px] bg-cover bg-center"
      style="background-image: url('../Pictures/Banners/Products-Section-5-Img-2.png');"></div>
    <div class="p-4">
      <span class="text-[10px] tracking-widest text-gray-400 uppercase block mb-2">Hot Collection</span>
      <hr class="w-12 border-black mb-6">
      <p class="text-xs text-gray-600 leading-relaxed tracking-wide mb-8">
        A curated drop featuring structured tailoring and contemporary essentials inspired by urban architecture.
      </p>
      <a href="search.php?content=collections"
        class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition">View
        Collection</a>
    </div>
  </section>

  <?php require "../component/sectionFooter.php" ?>


  <div id="product-modal">

    <div class="modal-container">
      <span class="close-modal z-[101]">&times;</span>
      <div class="modal-left">
        <img id="modal-img" src="" alt="Product Image">
      </div>

      <div class="modal-right">
        <h2 id="modal-name" class="font-normal opacity-[0.8] text-[24px] lg:text-[32px]"></h2>
        <p id="modal-price"
          class="w-[100%] font-light text-[14px] lg:text-[24px] pb-2 sm:pb-5 border-b border-[rgba(0,0,0,0.1)]"></p>

        <div>
          <div class="size-select">
            <p class="pt-3 lg:pt-4 pb-1">Size</p>
            <div class="sizes flex gap-2">
              <label for="S-size"
                class="active text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">S</label>
              <label for="M-size"
                class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">M</label>
              <label for="L-size"
                class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">L</label>
              <label for="XL-size"
                class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer">XL</label>
            </div>
          </div>

          <div class="color-select">
            <p class="pt-3 lg:pt-4 pb-1">Color</p>
            <div class="colors grid grid-cols-3 gap-y-1 lg:grid-cols-4 gap-x-1 sm:gap-x-2"></div>
          </div>
        </div>

        <div class="flex gap-2 sm:gap-3 pt-2 sm:pt-3 mt-10 w-[100%]">
          <button class="w-[50%] modal-add text-[0.7rem] sm:text-[0.9rem] p-1 sm:p-2">ADD TO CART</button>
          <button class="w-[50%] modal-try text-[0.7rem] sm:text-[0.9rem] p-1 sm:p-2" type="button">TRY WITH
            AI✨</button>
        </div>

        <div class="modal-detail cursor-pointer hover:underline pt-2 sm:pt-4">Details</div>
      </div>
    </div>

  </div>

  <div
    class="toast opacity-0 invisible translate-y-[100%] transition-all duration-300 fixed max-w-[250px] h-[40px] bg-[#000000] text-[#ffffff] bottom-[0] p-2 sm:p-4 flex justify-center items-center gap-2">
    <span>Item added to bag</span>
    <button class="underline" onclick="window.location.href='cart.php'">View</button>
  </div>

  <script src="../asset/contact.js"></script>
  <script src="../asset/headerEmail.js"></script>
  <script src="../asset/productsJS/products.js"></script>
  <script src="../asset/search.js"></script>
</body>

</html>