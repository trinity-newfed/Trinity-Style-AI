<?php require "../component/detail/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../Css/nav.css">
  <link rel="stylesheet" href="../Css/detail.css">
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
  <title><?= strtoupper($product['product_name']) ?> - TRINITY</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Birthstone&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&family=Playwrite+NO:wght@100..400&display=swap"
    rel="stylesheet">
</head>

<body>
  <div class="product-container pt-[10px] sm:pt-[120px]">
    <div class="product-left" data-img1="../<?= $product['product_img1'] ?>" data-img2="../<?= $product['product_img2'] ?>">
      <?php require "../component/detail/img.php" ?>
    </div>
  </div>

  <div class="product-right">
    <span id="mainId" style="display: none;" data-id="<?= $product['id'] ?>"></span>
    <span id="mainCategory" style="display: none;" data-category="<?= $product['product_category'] ?>"></span>
    <span id="mainColor" style="display: none;" data-color="<?= $product['product_color'] ?>"></span>
    <h1 class="font-normal opacity-[0.8] text-[30px] sm:text-[34px]"><?= $product['product_name'] ?></h1>

    <div class="price text-[green] text-[25px] pt-2 pb-5 border-b border-[rgba(0,0,0,0.1)]">
      $<?= $product['product_price']?></div>

    <div class="flex items-center border border-[rgba(0,0,0,0.3)] mt-5 w-max h-12">
      <button id="decrease-qty"
        class="w-12 h-full flex items-center justify-center text-sm font-light hover:bg-[#F3F3F3] transition-colors duration-300 select-none">—</button>
      <input id="quantity-input" type="number" value="1" min="1" max="99"
        class="w-12 h-full text-center text-xs font-medium focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none bg-transparent">
      <button id="increase-qty"
        class="w-12 h-full flex items-center justify-center text-sm font-light hover:bg-[#F3F3F3] transition-colors duration-300 select-none">＋</button>
    </div>

    <p class="pt-5 pb-2">Size</p>
    <div class="size flex gap-2">
      <label
        class="active text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer"
        for="S-size">S</label>
      <label
        class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer"
        for="M-size">M</label>
      <label
        class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer"
        for="L-size">L</label>
      <label
        class="text-[12px] sm:text-[14px] w-[30px] h-[30px] sm:w-[40px] sm:h-[40px] p-2 flex items-center justify-center border border-[rgba(0,0,0,0.3)] hover:border-[black] cursor-pointer"
        for="XL-size">XL</label>
    </div>

    <div class="color-select pt-5 pb-2">
      <p>Color</p>
      <div class="colors grid grid-cols-3 lg:grid-cols-4 gap-x-1 sm:gap-x-2">
        <?php require "../component/detail/label.php" ?>
      </div>
    </div>

    <div class="flex flex-col gap-5 pt-5 pb-2">
      <button class="add-cart">ADD TO CART</button>
      <button class="modal-try" type="submit">TRY WITH AI✨</button>
      <p class="short-desc font-serif"><?= $product['product_describe'] ?></p>
    </div>

  </div>
  </div>

  </div>
  <section id="body">

    <div class="max-w-7xl w-[100%] mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-100">
      <h1 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider mb-3">Color Variations</h1>
      <div
        class="simillar-product-container flex overflow-x-auto overflow-y-hidden gap-x-5 max-w-[100%] scrollbar-hide hide products animate-on-scroll animate">
        <?php require "../component/detail/variant.php" ?>
      </div>
    </div>


  </section>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-100">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-lg md:text-xl font-serif-custom uppercase tracking-wider">You may also like</h1>

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
      class="simillar-product-container flex overflow-x-auto overflow-y-hidden gap-x-5 max-w-[100%] scrollbar-hide hide products animate-on-scroll animate">
      <?php require "../component/detail/classic.php" ?>
    </div>
  </div>

  <section>
    <div>
      <img src="" alt="">
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
      <a href="#"
        class="inline-block border border-black text-xs uppercase tracking-widest px-8 py-3 hover:bg-black hover:text-white transition"
        onclick="window.location.href='search.php?content=collections'">View Collection</a>
    </div>
  </section>

  <?php require "../component/sectionMenu.php" ?>

  <?php require "../component/sectionFooter.php" ?>

  <div
    class="toast opacity-0 invisible translate-y-[100%] transition-all duration-300 fixed max-w-[250px] h-[40px] bg-[#000000] text-[#ffffff] bottom-[0] p-2 sm:p-4 flex justify-center items-center gap-2">
    <span>Item added to bag</span>
    <button class="underline" onclick="window.location.href='cart.php'">View</button>
  </div>
</body>

<script src="../asset/contact.js"></script>
<script src="../asset/headerEmail.js"></script>
<script src="../asset/detailJS/detail.js"></script>
<script src="../asset/search.js"></script>

</html>