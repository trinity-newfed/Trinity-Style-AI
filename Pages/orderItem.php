<?php require "../component/orderItem/header.php" ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
  <link rel="stylesheet" href="../Css/nav.css">
  <link rel="stylesheet" href="../Css/orderItem.css">
  <title>Order Detail - Trinity</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet" />
</head>

<body>
  <div class="orderContainer">
    <div class="header-section mt-[100px] sm:mt-0">
      <div class="orderText">
        <p>Order ID</p>
        <div class="orderID text-[18px] font-medium" onclick="CopyText()" class="cursor-pointer">
          #<?= $row['order_name'] ?></div>
      </div>


      <div class="toolBox">
        <div class="notification" onclick="window.location.href='cart.php'">
          <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="13px" viewBox="0 -960 960 960" width="13px"
            onclick="window.location.href='cart.php'">
            <path
              d="M200-80q-33 0-56.5-23.5T120-160v-480q0-33 23.5-56.5T200-720h80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720h80q33 0 56.5 23.5T840-640v480q0 33-23.5 56.5T760-80H200Zm0-80h560v-480H200v480Zm421.5-298.5Q680-517 680-600h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85h-80q0 83 58.5 141.5T480-400q83 0 141.5-58.5ZM360-720h240q0-50-35-85t-85-35q-50 0-85 35t-35 85ZM200-160v-480 480Z" />
          </svg>
          <span
            class="absolute top-[-5px] right-[-5px] bg-red-400 text-white rounded-full w-[18px] h-[18px] text-[10px] flex items-center justify-center"><?= $noti ?></span>
        </div>
        <div class="cart">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
          </svg>
          <span class="text-[13px] font-semibold" onclick="window.location.href='#ItemList'"><?= $count ?> items</span>
        </div>
      </div>
    </div>

    <div class="info-grid-bottom">
      <div class="info-card-white">

        <table class="status-table w-full table-fixed">
          <colgroup>
            <col class="w-[25%] sm:w-[80px]">
            <col class="w-[75%]">
          </colgroup>

          <thead>
            <tr>
              <th colspan="2"
                class="text-left font-semibold pb-2 pt-0 border-none bg-transparent px-0 text-base text-black cursor-default">
                Delivery Status</th>
            </tr>
            <tr class="border-b border-gray-200">
              <th class="cursor-default text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Time
              </th>
              <th class="cursor-default text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">State
              </th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b border-gray-100">
              <td class="cursor-default py-3 text-sm text-gray-900">
                <?= date('j-n', strtotime($row['updated_at'])) ?>
              </td>
              <td class="cursor-default font-medium text-sm text-gray-900 uppercase">
                <?= $row['order_state'] ?>
              </td>
            </tr>
          </tbody>

          <thead>
            <tr>
              <th colspan="2"
                class="text-left font-semibold pb-2 pt-6 border-none bg-transparent px-0 text-base text-black cursor-default">
                Status Detail</th>
            </tr>
            <tr class="border-b border-gray-200">
              <th class="cursor-default text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">Time
              </th>
              <th class="cursor-default text-left text-xs font-medium text-gray-500 uppercase tracking-wider py-2">State
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <?php foreach ($status as $key => $value): ?>
              <tr>
                <td class="cursor-default py-3 text-sm text-gray-500 align-top">
                  <?= date('j-n', strtotime($value['created_at'])) ?>
                </td>
                <td class="cursor-default font-light text-sm text-gray-600 py-3 break-words pr-2">
                  <?= $value['status_detail'] ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

      </div>

      <div class="info-card-white">
        <div class="brand-box">
          <div>
            <p class="text-[11px] opacity-[0.6] m-0 font-semibold cursor-default">DISCOUNT</p>
            <h4 class="m-[5px_0] cursor-default">$<?= $row['discount'] ?></h4>
          </div>
          <div class="text-right">
            <p class="text-[11px] opacity-[0.6] m-0 font-semibold cursor-default">TOTALS</p>
            <p class="font-semibold m-[5px_0] cursor-default">$<?= $row['order_final_price'] ?></p>
          </div>
        </div>
        <hr class="border-none border-t border-[#eee] my-[15px]" />
        <p class="text-[11px] opacity-[0.6] m-0 font-semibold cursor-default">ADDRESS</p>
        <p class="cursor-default font-semibold text-[13px] m-[5px_0]"><?= $row['order_address'] ?></p>
      </div>
    </div>

    <div class="ItemList" id="ItemList">
      <p class="font-semibold text-[18px] my-[20px]">Order Details</p>
      <?php require "../component/orderItem/items.php" ?>
    </div>
  </div>

  <section id="menu">
    <input type="checkbox" id="menu-toggle" hidden>
    <label class="hamburger" for="menu-toggle">
      <svg viewBox="0 0 32 32">
        <path class="line line-top-bottom"
          d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22">
        </path>
        <path class="line" d="M7 16 27 16"></path>
      </svg>
    </label>

    <div id="text-menu" class="userPHP">

      <div id="logo" class="userPHP" onclick="window.location.href='../Pages/'">TRINITY</div>

      <div id="text" class="userPHP">
        <span onclick="window.location.href='user.php'" class="orderBlock">Orders</span>
        <span onclick="window.location.href='user.php?#profile'" class="profileBlock">Profile</span>
      </div>


    </div>

    <div id="utility-menu">
      <?php require "../component/menu.php" ?>
      <?php require "../component/user/img.php" ?>
    </div>

    <div id="fast-menu">
      <div id="fast-menu-container">
        <div class="menu-item">
          <div class="orderBlock menu-title" onclick="window.location.href='user.php'"><span>Orders</span></div>
        </div>

        <div class="menu-item">
          <div class="profileBlock menu-title" onclick="window.location.href='user.php?#profile'"><span>Profile</span>
          </div>
        </div>


      </div>
    </div>

  </section>

  </section>

  <footer class="footer-2 h-[200px] flex flex-col justify-around relative m-auto bottom-[0]">
    <div class="border-t border-black-300"></div>
    <div class="w-[50%] sm:w-[40%] grid grid-cols-1 sm:grid-cols-3 gap-y-0 sm:gap-y-2 mt-[20px]">
      <span class="text-[#B1953B] font-[montserrat] font-light text-xs underline">Warranty Policy</span>
      <span class="text-[#B1953B] font-[montserrat] font-light text-xs underline">Privacy Policy</span>
      <span class="text-[#B1953B] font-[montserrat] font-light text-xs underline">Term of Service</span>
    </div>
  </footer>

  <script src="../asset/headerEmail.js"></script>
  <script src="../asset/orderItem/orderItem.js"></script>
</body>

</html>