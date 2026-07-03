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
      class="relative sm:sticky top-0 sm:top-[60px] w-[100%] sm:min-w-[30%] sm:max-w-[400px] flex flex-col justify-start items-center p-3">
      <span class="border-b broder-bg-[rgba(0,0,0,0.3)] w-[100%] flex justify-center items-center py-2">FILTER</span>
      <div class="relative w-[100%] border-b broder-bg-[rgba(0,0,0,0.3)] w-[100%] flex justify-start items-center py-2">
        <div class="flex justify-between items-center w-[100%] cursor-pointer">
          <span>Sort by</span>

          <svg class="svg rotate-[180deg] select transition-all duration-300 w-[13px] h-[13px]"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
            <path
              d="M169.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L192 205.3 54.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z" />
          </svg>
        </div>

        <div class="absolute"></div>
      </div>
    </div>
    <?php require "../component/search/item.php" ?>
  </section>

  <?php require "../component/sectionFooter.php" ?>

  <script src="../asset/headerEmail.js"></script>
  <script src="../asset/search.js"></script>
</body>

</html>