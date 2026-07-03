<?php require "../component/cart/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--TAILWIND CSS & CSS-->
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../Css/nav.css">
  <link rel="stylesheet" href="../Css/cart.css">

  <!--GG FONT & ICON-->
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
  <title>Shopping Bag - TRINITY</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Birthstone&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&family=Playwrite+NO:wght@100..400&display=swap"
    rel="stylesheet">
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
        <?php require "../component/cart/product.php" ?>
      </div>

      <!--Item information-->
      <div id="info-list">
        <div id="info-total-order">
          <div class="info-total-order-span-container voucher">
            <span>Voucher</span>
            <?php require "../component/cart/voucher.php" ?>
          </div>

          <div class="info-total-order-span-container delivery">
            <span>Delivery fee</span>
            <span id="deli-fee" style="text-align: center;"></span>
          </div>

          <div class="info-total-order-span-container total">
            <span>Grand Total</span>
            <?php require "../component/cart/total.php" ?>
          </div>

          <button type="submit" id="order-btn">Purchase</button>
        </div>
      </div>

    </div>
  </section>

  <?php require "../component/sectionMenu.php" ?>


  <?php require "../component/sectionFooter.php" ?>

  <input type="hidden" value="<?= $address ?>" id="to" disabled>

  <div
    class="toast opacity-0 invisible translate-y-[-100%] md:translate-y-[100%] transition-all duration-300 fixed max-w-[250px] h-[40px] bg-[#000000] text-[#ffffff] z-[1001] top-[0] bottom-auto md:bottom-[0] md:top-[auto] p-2 sm:p-4 flex justify-center items-center gap-2">
    <span>Item removed from bag</span>
    <button class="underline">Undo</button>
  </div>

  <script src="../asset/headerEmail.js"></script>
  <script src="../asset/cartJS/cart.js"></script>
  <script src="../asset/search.js"></script>
</body>

</html>