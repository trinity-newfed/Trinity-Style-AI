<?php require "../component/products/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TRINITY — SHOP ALL</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../Css/nav.css">
  <link rel="stylesheet" href="../Css/products.css">
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Inter:wght@200;300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
    rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }

    .font-serif-custom {
      font-family: 'Cormorant Garamond', 'Playfair Display', serif;
    }

    @media (min-width: 768px) {
      .modal-container {
        flex-direction: row;
        overflow: hidden;
      }
    }

    .close-modal {
      position: absolute;
      top: 1rem;
      right: 1.25rem;
      font-size: 1.75rem;
      line-height: 1;
      cursor: pointer;
      color: #171717;
      transition: transform 0.2s;
    }

    .close-modal:hover {
      transform: scale(1.15);
    }

    .modal-left {
      width: 100%;
      background-color: #f5f5f5;
    }

    @media (min-width: 768px) {
      .modal-left {
        width: 50%;
      }
    }

    .modal-left img {
      width: 100%;
      height: 100%;
      max-height: 520px;
      object-fit: cover;
    }

    .modal-right {
      width: 100%;
      padding: 2.5rem 2rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    @media (min-width: 768px) {
      .modal-right {
        width: 50%;
      }
    }

    .size-select .sizes label.active {
      background-color: #000000;
      color: #ffffff;
      border-color: #000000;
    }

    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
  </style>
</head>

<body class="bg-[#FCFCFC] text-neutral-900 antialiased isolate selection:bg-neutral-900 selection:text-white" id="body">

  <?php require "../component/sectionMenu.php" ?>

  <section id="head" class="relative bg-neutral-900 overflow-hidden h-[85vh] md:h-[100vh] flex items-center justify-center">

    <div class="absolute bg-black z-[10] w-full h-full animate-1 pointer-events-none opacity-40"></div>
    <div class="absolute inset-0 bg-cover bg-center">
      <img class="object-cover w-full h-full animate-2 brightness-[80%] scale-105 transition-transform duration-500" 
           src="../Pictures/Banners/Product-Banner.png" alt="Trinity Ready To Wear">
      <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-6 text-center z-20 text-white pt-12">
      <p class="text-xs tracking-[0.3em] uppercase mb-3 text-neutral-300 font-light">Autum / Winter Collection</p>
      <h1 class="text-5xl md:text-8xl font-serif-custom tracking-[0.15em] uppercase mb-6 font-normal leading-tight">
        READY-TO-WEAR
      </h1>
      <p class="text-xs md:text-sm text-neutral-300 max-w-md mx-auto mb-10 font-light tracking-widest leading-relaxed">
        Contemporary silhouettes & architectural precision<br class="hidden sm:inline">crafted for the modern minimalist.
      </p>
      <div class="flex flex-col sm:flex-row justify-center items-center gap-4 text-xs tracking-[0.2em] uppercase">
        <a href="search.php?content=collections"
          class="w-full sm:w-auto bg-white text-black px-9 py-4 hover:bg-neutral-900 hover:text-white border border-white transition-all duration-300 font-medium">
          Explore Collection
        </a>
        <a href="search.php?content=new"
          class="w-full sm:w-auto backdrop-blur-md bg-white/10 text-white border border-white/40 px-9 py-4 hover:bg-white hover:text-black transition-all duration-300">
          New Arrivals
        </a>
      </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 hidden md:flex flex-col items-center gap-2 opacity-60 hover:opacity-100 transition-opacity">
      <span class="text-[10px] uppercase tracking-[0.25em] text-white">Scroll</span>
      <div class="w-[1px] h-8 bg-gradient-to-b from-white to-transparent"></div>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex justify-between items-end mb-12 border-b border-neutral-200 pb-4">
      <div>
        <span class="text-[10px] uppercase tracking-[0.25em] text-neutral-400 font-medium block mb-1">Curated Selection</span>
        <h2 class="text-2xl md:text-3xl font-serif-custom uppercase tracking-wider font-light">Featured Collection</h2>
      </div>
      <a href="search.php?content=collections"
        class="text-xs uppercase tracking-[0.2em] text-neutral-500 hover:text-black transition-colors border-b border-transparent hover:border-black pb-1">
        View All
      </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-12 collections animate-on-scroll">
      <?php require "../component/products/product.php" ?>
    </div>
  </section>

  <section class="grid grid-cols-1 md:grid-cols-2 bg-[#F4F4F3] items-center my-10 overflow-hidden">
    <div class="h-96 md:h-[650px] w-full bg-cover bg-center transition-transform duration-700 hover:scale-105"
      style="background-image: url('../Pictures/Banners/Products-Section-3-Img.png');"></div>
    <div class="p-10 md:p-24 text-center md:text-left flex flex-col items-center md:items-start justify-center">
      <span class="text-[10px] uppercase tracking-[0.3em] text-neutral-400 font-medium mb-3">Craftsmanship</span>
      <h2 class="text-3xl md:text-5xl font-serif-custom tracking-wider uppercase mb-6 font-normal text-neutral-900 leading-tight">
        Tailoring<br class="hidden md:inline"> Redefined
      </h2>
      <p class="text-xs md:text-sm text-neutral-600 tracking-wider max-w-md mb-10 leading-relaxed font-light">
        Precision cuts, elevated textures, and timeless forms designed beyond seasonal trends. Designed in Paris, meticulously tailored.
      </p>
      <a href="search.php?content=all"
        class="inline-block border border-neutral-900 text-neutral-900 text-xs uppercase tracking-[0.2em] px-9 py-4 hover:bg-neutral-900 hover:text-white transition-all duration-300">
        Discover More
      </a>
    </div>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="flex justify-between items-end mb-10 border-b border-neutral-200 pb-4">
      <div>
        <span class="text-[10px] uppercase tracking-[0.25em] text-neutral-400 font-medium block mb-1">Fresh Drops</span>
        <h2 class="text-2xl md:text-3xl font-serif-custom uppercase tracking-wider font-light">New Items</h2>
      </div>
      <div class="flex items-center space-x-3">
        <button class="previous p-2.5 border border-neutral-300 rounded-full hover:border-black hover:bg-black hover:text-white transition-all text-neutral-700">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>
        <button class="next p-2.5 border border-neutral-300 rounded-full hover:border-black hover:bg-black hover:text-white transition-all text-neutral-700">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
      </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-x-5 gap-y-8 max-w-full scrollbar-hide hide products animate-on-scroll">
      <?php require "../component/products/classic.php" ?>
    </div>
  </section>

  <section class="bg-neutral-900 text-white py-20 my-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
      <div class="h-[480px] bg-cover bg-center rounded-sm overflow-hidden group">
        <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
             style="background-image: url('../Pictures/Banners/Products-Section-5-Img-1.png');"></div>
      </div>
      <div class="h-[480px] bg-cover bg-center rounded-sm overflow-hidden group hidden md:block">
        <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-700"
             style="background-image: url('../Pictures/Banners/Products-Section-5-Img-2.png');"></div>
      </div>
      <div class="p-4 md:p-8 flex flex-col justify-center">
        <span class="text-[10px] tracking-[0.3em] text-neutral-400 uppercase block mb-3 font-medium">Limited Edition</span>
        <h3 class="text-3xl font-serif-custom tracking-wider uppercase mb-4 font-light">The Urban Capsule</h3>
        <hr class="w-12 border-neutral-700 mb-6">
        <p class="text-xs text-neutral-400 leading-relaxed tracking-wider mb-8 font-light">
          A curated drop featuring structured tailoring and contemporary essentials inspired by modern urban architecture and brutalist aesthetics.
        </p>
        <a href="search.php?content=collections"
          class="inline-block border border-white text-white text-xs uppercase tracking-[0.2em] px-8 py-3.5 hover:bg-white hover:text-black transition-all duration-300 text-center">
          View Collection
        </a>
      </div>
    </div>
  </section>

  <?php require "../component/sectionFooter.php" ?>


<div id="product-modal" class="fixed inset-0 z-[1000] flex items-center justify-center p-0 sm:p-4 md:p-6 bg-black/60 backdrop-blur-sm transition-opacity duration-300">

  <div class="modal-container relative w-full max-w-4xl bg-white text-neutral-900 shadow-2xl overflow-hidden flex flex-col md:flex-row max-h-[100vh] sm:max-h-[90vh] md:max-h-[85vh]">
    
    <span class="close-modal absolute top-3 right-3 sm:top-4 sm:right-4 z-[101] w-9 h-9 flex items-center justify-center text-neutral-400 hover:text-black hover:bg-neutral-100 transition-all rounded-full cursor-pointer text-2xl font-light">
      &times;
    </span>

    <div class="modal-left w-full md:w-1/2 bg-neutral-100/70 flex items-center justify-center p-6 sm:p-8 relative min-h-[260px] sm:min-h-[340px] md:min-h-[480px]">
      <img id="modal-img" src="" alt="Product Image" class="w-full h-full max-h-[280px] sm:max-h-[360px] md:max-h-[440px] object-contain transition-transform duration-700 ease-out hover:scale-105">
    </div>

    <div class="modal-right w-full md:w-1/2 p-6 sm:p-8 md:p-10 flex flex-col justify-between overflow-y-auto bg-white">
      
      <div>
        <span class="text-[10px] uppercase tracking-[0.25em] text-neutral-400 block mb-1.5 font-medium">TRINITY Essential</span>
        <h2 id="modal-name" class="font-serif-custom font-normal text-2xl sm:text-3xl tracking-wide text-neutral-900 mb-2 leading-tight"></h2>
        <p id="modal-price" class="w-full font-light text-lg sm:text-xl pb-4 border-b border-neutral-200 text-neutral-800"></p>
      </div>

      <div class="my-6 space-y-6">
        
        <div class="size-select">
          <div class="flex justify-between items-center mb-2.5">
            <p class="text-[11px] uppercase tracking-[0.2em] text-neutral-500 font-medium">Select Size</p>
            <button class="text-[10px] uppercase tracking-widest text-neutral-400 underline underline-offset-4 hover:text-black transition-colors" onclick="window.location.href='size-guide.html'">Size Guide</button>
          </div>
          <div class="sizes flex gap-2">
            <label for="S-size" class="active text-xs w-10 h-10 flex items-center justify-center border border-neutral-300 hover:border-black transition-all cursor-pointer font-medium tracking-wider">S</label>
            <label for="M-size" class="text-xs w-10 h-10 flex items-center justify-center border border-neutral-300 hover:border-black transition-all cursor-pointer font-medium tracking-wider">M</label>
            <label for="L-size" class="text-xs w-10 h-10 flex items-center justify-center border border-neutral-300 hover:border-black transition-all cursor-pointer font-medium tracking-wider">L</label>
            <label for="XL-size" class="text-xs w-10 h-10 flex items-center justify-center border border-neutral-300 hover:border-black transition-all cursor-pointer font-medium tracking-wider">XL</label>
          </div>
        </div>

        <div class="color-select">
          <p class="text-[11px] uppercase tracking-[0.2em] text-neutral-500 font-medium mb-2.5">Color Option</p>
          <div class="colors grid grid-cols-4 gap-2"></div>
        </div>

      </div>

      <div class="space-y-3 pt-4 border-t border-neutral-100 w-full">
        <div class="flex flex-col sm:flex-row gap-3">
          <button class="w-full sm:w-1/2 modal-add text-xs uppercase tracking-[0.2em] bg-black text-white py-3.5 hover:bg-neutral-800 transition-all font-medium active:scale-[0.99]">
            ADD TO CART
          </button>
          <button id="tryonBtn" class="w-full sm:w-1/2 modal-try text-xs uppercase tracking-[0.2em] border border-black bg-white text-black py-3.5 hover:bg-black transition-all font-medium flex items-center justify-center gap-2 active:scale-[0.99] shadow-sm">
            <span>TRY WITH AI</span>
            <span class="text-amber-300 text-sm">✨</span>
          </button>
        </div>
        
        <div class="modal-detail text-center text-[11px] uppercase tracking-[0.2em] text-neutral-400 cursor-pointer hover:text-black hover:underline underline-offset-4 pt-2 transition-colors">
          View Full Details &rarr;
        </div>
      </div>

    </div>

  </div>

</div>

  <div class="toast opacity-0 invisible translate-y-4 transition-all duration-300 fixed bottom-6 right-6 z-50 bg-black text-white px-5 py-3.5 rounded-none shadow-2xl flex items-center gap-4 text-xs tracking-wider border border-neutral-800">
    <div class="flex items-center gap-2">
      <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
      </svg>
      <span>Item added to bag</span>
    </div>
    <button class="underline uppercase tracking-widest hover:text-neutral-300 font-medium" onclick="window.location.href='cart.php'">View Bag</button>
  </div>

  <script src="../asset/contact.js"></script>
  <script src="../asset/headerEmail.js"></script>
  <script src="../asset/productsJS/products.js"></script>
  <script src="../asset/search.js"></script>
</body>

</html>