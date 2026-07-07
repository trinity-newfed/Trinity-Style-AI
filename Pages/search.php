<?php require "../component/search/header.php" ?>
<?php require "../Database/search.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search: <?php require "../component/search/title.php" ?></title>

  <!--TAILWIND CSS & CSS LINK-->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../Css/nav.css">
  <link rel="stylesheet" href="../Css/search.css">

  <!--GG FONT & ICON-->
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
</head>

<body>
  <?php require "../component/sectionMenu.php" ?>
  <section class="m-auto flex justify-center items-center my-[80px] pb-[50px] border-b broder-bg-[rgba(0,0,0,0.3)]">
    <div class="flex flex-col justify-center items-center">
      <h2 class="text-[30px]">Search</h2>
      <p><?php require "../component/search/title.php" ?></p>
    </div>
  </section>


  <section class="flex flex-col sm:flex-row justify-between sm:justify-center items-start">
    <div
      class="relative sm:sticky top-0 sm:top-[60px] w-full sm:min-w-[30%] sm:max-w-[400px] flex flex-col justify-start items-center p-3 bg-white">

      <span class="border-b border-black/30 w-full flex justify-center items-center py-2 font-bold">FILTER</span>

      <div class="relative w-full border-b border-black/30 flex flex-col justify-start items-center py-2">

        <div id="sort-toggle" class="flex justify-between items-center w-full cursor-pointer select-none py-1">
          <span id="current-sort">Sort by</span>

          <svg id="sort-arrow" class="transition-all duration-300 w-[13px] h-[13px] fill-current"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <path
              d="M169.4 342.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 274.7 54.6 137.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z" />
          </svg>
        </div>

        <div id="sort-options"
          class="hidden absolute left-0 top-[100%] w-full bg-white border border-black/20 rounded shadow-lg z-50 mt-1 overflow-hidden transition-all duration-200 opacity-0 scale-95 transform origin-top">
          <div data-value="name-az" class="sort-item px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Name: A-Z</div>
          <div data-value="name-za" class="sort-item px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Name: Z-A</div>
          <div data-value="price-asc" class="sort-item px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Price: Low to
            High</div>
          <div data-value="price-desc" class="sort-item px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm">Price: High
            to Low</div>
        </div>

      </div>
    </div>
    <?php require "../component/search/item.php" ?>
  </section>

  <?php require "../component/sectionFooter.php" ?>

  <script src="../asset/headerEmail.js"></script>
  <script src="../asset/search.js"></script>
  <script src="../asset/searchJS/search.js"></script>
</body>

</html>