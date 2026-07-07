<?php require "../component/voucher/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TRINITY — EXCLUSIVE OFFERS</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
  <link rel="stylesheet" href="../Css/nav.css">
  <link rel="stylesheet" href="../Css/voucher.css">

  <link
    href="https://fonts.googleapis.com/css2?family=Birthstone&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap"
    rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Playfair:ital,opsz,wght@0,5..1200,300..900;1,5..1200,300..900&display=swap"
    rel="stylesheet">

<body>
  <section class="voucher-page">

    <h1>Trinity Vouchers</h1>
    <div class="voucher-filter">
      <button class="active">All</button>
      <button class="active">Available</button>
      <button class="active">Expiring</button>
      <button class="active">Used</button>
    </div>



    <?php require "../component/voucher/voucher.php" ?>
  </section>

  <?php require "../component/sectionFooter.php" ?>

  <?php require "../component/sectionMenu.php" ?>
</body>

<script src="../asset/contact.js"></script>
<script src="../asset/headerEmail.js"></script>
<script src="../asset/voucherJS/voucher.js"></script>
<script src="../asset/search.js"></script>

</html>