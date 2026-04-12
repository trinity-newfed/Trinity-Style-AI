<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $dbname = "TF_Database";

    $conn = new mysqli($host, $user, $password, $dbname);

    session_start();
    $id = $_GET['id'];
    $username = $_SESSION['username'] ?? null;
    $userID = $_SESSION['user_id'] ?? null;

    $sql = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $sql->bind_param("ii", $id, $userID);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();

    $items = $conn->prepare("SELECT
                        order_items.order_id as order_id,
                        order_items.product_id, products.id,
                        products.product_img, order_items.price,
                        products.product_name,
                        products.product_category, products.product_color,
                        order_items.size, order_items.quantity
                        FROM order_items
                        JOIN products
                        ON order_items.product_id = products.id
                        WHERE order_id = ?
                         ");
    $items->bind_param("i", $id);
    $items->execute();
    $item = $items->get_result();
    $rows = $item->fetch_all(MYSQLI_ASSOC);
    $count = 0;
    foreach($rows as $r){
      $count = $count + $r['quantity'];
    }

    $cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $cart->bind_param("i", $userID);
    $cart->execute();
    $res = $cart->get_result();
    $cItem = $res->fetch_all(MYSQLI_ASSOC);
    $noti = 0;
    foreach($cItem as $i){
      $noti++;
    }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Order</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      * {
        box-sizing: border-box;
      }
      html, body {
        margin: 0;
        font-family: "Montserrat", sans-serif;
        background-color: rgb(241, 241, 242);
        position: relative;
        margin: auto;
        min-height: 100vh;
        max-width: 1500px;
        top: 0;
        left: 0;
        overflow-x: hidden;
        user-select: none;
        scroll-behavior: smooth;
      }
      /*START MENU*/
#menu{
    width: 100%;
    max-width: 1500px;
    height: 70px;
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 60px;
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(10px);
    z-index: 1000;
}
#text span{
    position: relative;
    cursor: pointer;
}

#text span::after{
    content: "";
    position: absolute;
    left: 0;
    bottom: -4px;
    width: 0;
    height: 2px;
    background: linear-gradient(to right, #0d0d0d, #1f1f1f);
    transition: 0.3s;
}

#text span:hover::after{
    width: 100%;
}
#text-menu{
    width: 70%;
    height: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    left: 0;
}
#logo{
    cursor: pointer;
    position: relative;
    left: 2%;
    width: 10%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-family: "Montserrat", serif;
    color: rgb(0, 0, 0);
    font-size: clamp(.75rem, 1.75vw, 2.5rem);
}
#text{
    width: 60%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bolder;
}
#text span{
    font-size: clamp(0.65rem, 1.25vw, 1rem);
    padding: 0 5px;
    transition: .3s all;
}
#text span:hover{
    transition: .3s all;
    cursor: pointer;
    background: linear-gradient(to right, #0d0d0d, #2b2b2b);
    padding: 0 5px;
    color: white;
}


#utility-menu{
    width: 20%;
    height: 100%;
    display: flex;
    justify-content: space-around;
    align-items: center;
    font-size: clamp(0.75rem, 1.25vw, 1rem);
    font-family: Arial, Helvetica, sans-serif;
}
.icon{
    cursor: pointer;
    width: clamp(.75rem, 1.25vw, 1.9rem);
    height: clamp(.75rem, 1.25vw, 1.9rem);
}
.icon path{
    scale: 1;
}
#login-btn{
    width: 30%;
    height: 80%;
    background-color: orangered;
    border-radius: 2px;
    text-align: center;
    color: white;
    align-items: center;
    display: flex;
    justify-content: center;
    font-family: Arial, Helvetica, sans-serif;
    font-weight: bolder;
    cursor: pointer;
}
#login-btn:hover{
    background-color: rgb(183, 49, 0);
}
#user-account{
    width: clamp(.25rem, 3vw, 2.5rem);
    height: clamp(.25rem, 3vw, 2.5rem);
    z-index: 2;
    background-color: #111;
    border-radius: 50%;
}
#user-avatar{
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    object-position: center 30%;
}
/*Menu Button*/
.hamburger{
  cursor: pointer;
}
#menu-toggle:checked ~ #fast-menu{
    visibility: visible;
    opacity: 1;
    transform: translateX(0);
}
.hamburger input{
  display: none;
}
.hamburger svg{
  height: 2em;
  transition: transform 600ms cubic-bezier(0.4, 0, 0.2, 1);
}
.line{
  fill: none;
  stroke: rgb(0, 0, 0);
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-width: 3;
  transition: stroke-dasharray 600ms cubic-bezier(0.4, 0, 0.2, 1),
              stroke-dashoffset 600ms cubic-bezier(0.4, 0, 0.2, 1);
}
.line-top-bottom{
  stroke-dasharray: 12 63;
}
#menu-toggle:checked + #utility-menu .hamburger svg{
  transform: rotate(-45deg);
}

#menu-toggle:checked + #utility-menu .line-top-bottom{
  stroke-dasharray: 20 300;
  stroke-dashoffset: -32.42;
}

/*Menu Button*/
/*FAST MENU*/
#fast-menu{
    background: linear-gradient(180deg,#111 0%,#0a0a0a 50%,#0000005d 100%);
    color: #fff;
    position: fixed;
    width: 260px;
    top: 110%;
    right: 1%;
    transform: translate(200%, -50%);
    padding: 30px 40px;
    display: grid;
    gap: 30px;
    box-sizing: border-box;
    visibility: hidden;
    opacity: 0;
    border-radius: 12px 0 0 12px;
    box-shadow: -10px 0 30px rgba(0,0,0,0.5);
    transition: .4s ease;
}
.menu-item:hover .menu-title{
    text-decoration: underline;
}
.menu-item:hover .submenu{
    color: white;
    text-decoration: none;
}
.menu-title{
    padding: 10px;
    cursor: pointer;
    font-weight: bold;
    border-bottom: 1px solid #ddd;
}
.sub-sub{
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    padding-left: 15px;
    transition: .3s ease;
}
.sub-sub:hover{
    text-decoration: underline;
}
.submenu-item:hover{
    cursor: pointer;
}
.submenu-item.active .sub-sub{
    max-height: 100px;
    opacity: 1;
}
.submenu{
    max-height: 0;
    overflow: hidden;
    transition: .35s ease;
}

.menu-item.active .submenu{
    max-height: 300px;
}
/*FAST MENU*/
/*END MENU*/

      .orderContainer {
        width: 100%;
        max-width: 1500px;
        background: white;
        padding: 10% 40px;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        position: relative;
        margin: auto;
      }

      .header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 30px;
      }

      .orderText p {
        font-size: 14px;
        font-weight: 700;
        opacity: 0.5;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin: 0 0 5px 0;
      }

      .orderID {
        font-weight: 700;
        font-size: 24px;
        color: #333;
      }

      .toolBox {
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .notification,
      .cart {
        height: 40px;
        background-color: #eee;
        border-radius: 7px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 15px;
        transition: 0.3s;
        position: relative;
      }
      .cart {
        width: auto;
        gap: 8px;
      }

      .delivery-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
      }

      .card-box {
        background-color: #ddd;
        border-radius: 15px;
        padding: 20px;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }

      .DeliveryProgress {
        width: 100%;
        height: 12px;
        background-color: #fff;
        border-radius: 10px;
        margin-top: 15px;
        overflow: hidden;
      }

      .DeliveryProgressBar {
        width: 0;
        height: 100%;
        background: linear-gradient(90deg, #c6a96b, #e5d3a1);
      }

      .info-grid-bottom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
      }

      .info-card-white {
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        padding: 20px;
      }

      .status-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
      }
      .status-table th {
        text-align: left;
        opacity: 0.5;
        font-size: 12px;
        padding-bottom: 10px;
      }
      .status-table td {
        padding: 8px 0;
        font-weight: 600;
      }

      .brand-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .ItemList {
        display: flex;
        flex-direction: column;
      }

      .ItemRow {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20%;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        transition: transform 0.2s;
      }
      .ItemInfoContainer{
        display: flex;
        justify-content: space-between;
        margin-left: 3%;
        width: 30%;
      }
      .ItemInfo{
        width: fit-content;
        flex-direction: column;
      }
      .ItemRow :nth-child(3){
        width: 10%;
        display: flex;
        justify-content: center;
      }

      .ItemRow:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      }

      .ItemRow img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        background-color: #f9f9f9;
      }

      .ItemInfo .ItemName {
        font-weight: 700;
        font-size: 14px;
        color: #333;
        margin: 0;
      }

      .ItemPrice, .ItemQuantity {
        font-weight: 600;
        font-size: 14px;
        color: #555;
      }

      .ItemStatus {
        text-align: right;
      }

      .StatusBadge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      .status-delivering {
        background-color: #fff3e0;
        color: #ff781f;
      }
      
      .status-completed {
        background-color: #e8f5e9;
        color: #2e7d32;
      }

      .ItemListHeader {
        display: flex;
        justify-content: center;
        gap: 20%;
        margin-bottom: 10px;
      }
      .ItemListHeader :nth-child(1){
        margin-left: 3%;
        width: 30%;
      }
      .ItemListHeader :nth-child(2){
        width: 40px;
        display: flex;
        justify-content: center;
      }
      .ItemListHeader :nth-child(3){
        width: 10%;
        display: flex;
        justify-content: center;
      }
      .ItemListHeader p {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        opacity: 0.5;
        letter-spacing: 1px;
      }

      @media (max-width: 768px) {
        .delivery-grid,
        .info-grid-bottom {
          grid-template-columns: 1fr;
        }
        .orderContainer {
          padding: 20px;
        }
        .header-section {
          flex-direction: column;
        }
      }
      .mobile-only {
        padding-top: 5%;
        display: none;
      }
      .app-header {
        background: white;
        padding: 15px;
        display: flex;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        border-bottom: 1px solid #eee;
      }

      .app-header h1 {
        font-size: 18px;
        margin: 0;
        font-weight: 500;
      }

      .status-banner {
        background: #2c2c2c;
        color: white;
        padding: 20px 15px;
        font-size: 16px;
        font-weight: 500;
      }

      .m-card {
        background: white;
        margin-bottom: 10px;
        padding: 15px;
      }

      .m-section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 12px;
      }

      .m-info-row {
        display: flex;
        gap: 12px;
      }

      .m-info-text {
        font-size: 13px;
        line-height: 1.5;
        color: #555;
      }

      .m-info-text b {
        color: #222;
        display: block;
        margin-bottom: 2px;
      }

      .m-product-item {
        display: flex;
        gap: 12px;
        margin-bottom: 15px;
      }

      .m-product-img {
        width: 80px;
        height: 80px;
        border-radius: 4px;
        object-fit: cover;
        background: #f9f9f9;
      }

      .m-total-row {
        border-top: 1px solid #f5f5f5;
        padding-top: 15px;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 8px;
        font-size: 14px;
      }

      .m-total-amount {
        color: black;
        font-size: 18px;
        font-weight: 600;
      }

      .m-bottom-bar {
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 10px 15px;
        border-top: 1px solid #eee;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
      }

      .btn-buy-again {
        background: linear-gradient(to right, #0d0d0d, #1f1f1f);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 2px;
        width: 100%;
        font-weight: 600;
        font-size: 14px;
        bottom: 0;
        left: 0;
        cursor: pointer;
      }

      .spacer {
        height: 70px;
      }

      small {
        color: #999;
        font-size: 12px;
      }
      .m-report-issue {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 0;
        border-top: 1px dashed #eee; 
        margin-top: 5px;
      }

      .m-report-issue span {
        font-size: 11px;
        color: #666;
      }

      .m-report-issue a {
        font-size: 11px;
        color: #26aa99; 
        text-decoration: none;
        font-weight: 600;
      }

      .m-report-issue a:hover {
        text-decoration: underline;
      }
      @media (max-width: 768px) {
        .mobile-only {
          display: block;
          position: relative;
          z-index: 1000;
        }
        .orderContainer {
          display: none;
        }
      }
      .footer-2 {
  background: #020225;
  color: #fff;
  padding: 60px 80px 30px;
  margin: 40px;
  font-family: sans-serif;
  position: relative;
  margin: auto;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  gap: 60px;
  flex-wrap: wrap;
}

.footer-left {
  max-width: 400px;
}

.footer-label {
  font-size: 12px;
  color: #aaa;
  margin-bottom: 10px;
}

.footer-title {
  font-size: 28px;
  font-weight: 600;
  margin-bottom: 20px;
}

.footer-btn {
  background: #e5e7eb;
  color: #000;
  border: none;
  padding: 12px 20px;
  border-radius: 25px;
  cursor: pointer;
  margin-bottom: 25px;
  transition: 0.3s;
}

.footer-btn:hover {
  background: #fff;
}

.footer-email-label {
  font-size: 12px;
  color: #aaa;
  margin-bottom: 10px;
}

.footer-email {
  background: #0a0a3a;
  padding: 10px 15px;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
}

.footer-right {
  display: flex;
  gap: 80px;
}

.footer-col {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.footer-col-title {
  font-size: 12px;
  color: #aaa;
  margin-bottom: 10px;
}

.footer-col a {
  text-decoration: none;
  color: #fff;
  font-size: 14px;
  transition: 0.2s;
}

.footer-col a:hover {
  color: #bbb;
}

.footer-bottom {
  margin-top: 40px;
  padding-top: 20px;
  border-top: 1px solid #222;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
}

.footer-bottom p {
  font-size: 12px;
  color: #aaa;
}

.footer-social {
  display: flex;
  gap: 15px;
}

.footer-social span {
  cursor: pointer;
  font-size: 14px;
  transition: 0.2s;
}

.footer-social span:hover {
  color: #ccc;
}
@media (max-width: 768px) {
  .footer-2 {
    padding: 40px 20px;
  }

  .footer-container {
    flex-direction: column;
  }

  .footer-right {
    gap: 40px;
  }
  #menu{
        justify-content: space-around;
        padding: 0;
    }
    #text-menu{
        width: 30%;
    }
    #logo{
        max-width: fit-content;
        max-height: fit-content;
        min-width: fit-content;
    }
    #text{
        display: none;
    }
    #utility-menu{
        width: 40%;
        justify-content: space-around;
    }
}
    </style>
  </head>
  <body>
    <div class="orderContainer">
      <div class="header-section">
        <div class="orderText">
          <p>Order ID</p>
          <div class="orderID" onclick="CopyText()" style="cursor: pointer;">#<?=$row['order_name']?></div>
        </div>


        <div class="toolBox">
          <div class="notification" onclick="window.location.href='cart.php'">
            <svg class="icon" viewBox="0 0 640 512" aria-hidden="true">
                <path fill="currentColor" d="M24 0C10.7 0 0 10.7 0 24s10.7 24 24 24h45.3c3.9 0 7.2 2.8 7.9 6.6l52.1 286.3C135.5 375.1 165.3 400 200.1 400H456c13.3 0 24-10.7 24-24s-10.7-24-24-24H200.1c-11.6 0-21.5-8.3-23.6-19.7l-5.1-28.3h303.6c30.8 0 57.2-21.9 62.9-52.2L568.9 85.9C572.6 66.2 557.5 48 537.4 48H124.7l-.4-2C119.5 19.4 96.3 0 69.2 0H24zm184 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm224 0a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/>
            </svg>
            <span
              style="
                position: absolute;
                top: -5px;
                right: -5px;
                background: red;
                color: white;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                font-size: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
              "
              ><?=$noti?></span
            >
          </div>
          <div class="cart">
            <svg
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"
              ></path>
              <line x1="3" y1="6" x2="21" y2="6"></line>
            </svg>
            <span style="font-size: 13px; font-weight: 700">2 items</span>
          </div>
        </div>
      </div>

      <div class="delivery-grid">
        <div class="card-box">
          <div>
            <p
              style="margin: 0; opacity: 0.7; font-size: 14px; font-weight: 700; cursor: default;"
            >
              DELIVERY STATE
            </p>
            <h3 style="margin: 10px 0; cursor: default;"><?=$row['order_state']?></h3>
          </div>
          <div class="DeliveryProgress">
            <div class="DeliveryProgressBar"></div>
          </div>
        </div>
        <div class="card-box">
          <p style="margin: 0; opacity: 0.7; font-size: 14px; font-weight: 700; cursor: default;">
            TIME EXPECTED
          </p>
          <p style="font-weight: 700; font-size: 18px; margin: 10px 0; cursor: default;">
            28/2/2027 - 30/2/2027
          </p>
        </div>
      </div>

      <div class="info-grid-bottom">
        <div class="info-card-white">
          <p style="font-weight: 700; margin-top: 0; cursor: default;">Delivery Status</p>
          <table class="status-table">
            <thead>
              <tr>
                <th style="cursor: default;">Time</th>
                <th style="cursor: default;">State</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="cursor: default;">
                  <?=$row['created_at']?> <br />
                </td>
                <td style="cursor: default;"><?=$row['order_state']?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="info-card-white">
          <div class="brand-box">
            <div>
              <p
                style="
                  font-size: 11px;
                  opacity: 0.6;
                  margin: 0;
                  font-weight: 700;
                  cursor: default;
                "
              >
                DISCOUNT
              </p>
              <h4 style="margin: 5px 0; cursor: default;">$<?=$row['discount']?></h4>
            </div>
            <div style="text-align: right">
              <p
                style="
                  font-size: 11px;
                  opacity: 0.6;
                  margin: 0;
                  font-weight: 700;
                  cursor: default;
                "
              >
                TOTALS
              </p>
              <p
                style="
                  font-weight: 700;
                  margin: 5px 0;
                  cursor: default;
                "
                
              >
                $<?=$row['order_final_price']?>
              </p>
            </div>
          </div>
          <hr
            style="border: none; border-top: 1px solid #eee; margin: 15px 0;"
          />
          <p style="font-size: 11px; opacity: 0.6; margin: 0; font-weight: 700; cursor: default;">
            ADDRESS
          </p>
          <p style="font-weight: 700; font-size: 13px; margin: 5px 0; cursor: default;">
            <?=$row['order_address']?>
          </p>
        </div>
      </div>

      <div class="ItemList">
        <p style="font-weight: 700; font-size: 18px; margin-top: 20px;">Order Details</p>
        
        <div class="ItemListHeader">
          <p>Product</p>
          <p>Price</p>
          <p>Quantity</p>
        </div>
        <?php foreach($rows as $r): ?>
        <div class="ItemRow">
          <div class="ItemInfoContainer">
            <img src="../<?=$r['product_img']?>" alt="Product">
            <div class="ItemInfo">
              <p class="ItemName"><?=$r['product_name']?></p>
              <p style="font-size: 11px; opacity: 0.6; margin: 3px 0 0 0;"><?=$r['product_color']?> / <?=$r['size']?></p>
            </div>
          </div>
          <div class="ItemPrice" data-price="<?=$r['price']?>"></div>
          <div class="ItemQuantity">x<?=$r['quantity']?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="mobile-only">
      <div class="app-header">
        <svg
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="black"
          stroke-width="2"
          style="margin-right: 15px"
        >
          <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
        <h1>Order Information</h1>
      </div>

      <div class="status-banner">Order State</div>

      <div class="m-card">
        <div class="m-section-title">Delivery Information</div>
        <div class="m-info-text"></div>
        <div class="m-info-row" style="margin-top: 10px">
          <div class="m-info-text">
            <span style="color: #26aa99"><?=$row['order_state']?></span><br /><small
              ><?=$row['created_at']?></small
            >
          </div>
        </div>
      </div>

      <div class="m-card">
        <div class="m-section-title">Order Address</div>
        <div class="m-info-text"></b><?=$row['order_address']?></div>
      </div>

      <div class="m-card">
        <?php foreach($rows as $r): ?>
        <div class="m-product-item">
          <img
            src="../<?=$r['product_img']?>"
            class="m-product-img"
          />
          <div style="flex: 1">
            <div class="m-info-text"><?=$r['product_name']?></div>
            <div style="text-align: right">
              <small>x<?=$r['quantity']?></small><br />
              <b class="short" data-price="<?=$r['price']?>"></b>
            </div>
          </div>
        </div>
        <div class="m-total-row">
          Totals: <span class="m-total-amount">$<?=$row['order_final_price']?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="m-report-issue">
        <svg
          width="14"
          height="14"
          viewBox="0 0 24 24"
          fill="none"
          stroke="#26aa99"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <circle cx="12" cy="12" r="10"></circle>
          <line x1="12" y1="16" x2="12" y2="12"></line>
          <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <span>Found a problem / Report Order?</span>
        <a href="#">Report</a>
      </div>

      <div class="spacer"></div>
      <div class="m-bottom-bar">
        <button class="btn-buy-again">Resent Order</button>
      </div>
    </div>
<section id="menu">
        <div id="text-menu">
            <div id="logo" onclick="window.location.href='../Pages/'">TRINITY</div>
            <div id="text">
                <span onclick="window.location.href='../Pages/'">Home</span>
                <span onclick="window.location.href='products.php?#product-section'">Shop</span>
                <span onclick="window.location.href='products.php?#product-section'">Collection</span>
                <span onclick="window.location.href='contact.php'">Contact</span>
            </div>
        </div>
        <input type="checkbox" id="menu-toggle" hidden>
        <div id="utility-menu">
            <label class="hamburger" for="menu-toggle">
                    <svg viewBox="0 0 32 32">
                        <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                        <path class="line" d="M7 16 27 16"></path>
                    </svg>
            </label>
            <svg class="icon" viewBox="0 0 640 512" aria-hidden="true" onclick="window.location.href='cart.php'">
                <path fill="currentColor" d="M24 0C10.7 0 0 10.7 0 24s10.7 24 24 24h45.3c3.9 0 7.2 2.8 7.9 6.6l52.1 286.3C135.5 375.1 165.3 400 200.1 400H456c13.3 0 24-10.7 24-24s-10.7-24-24-24H200.1c-11.6 0-21.5-8.3-23.6-19.7l-5.1-28.3h303.6c30.8 0 57.2-21.9 62.9-52.2L568.9 85.9C572.6 66.2 557.5 48 537.4 48H124.7l-.4-2C119.5 19.4 96.3 0 69.2 0H24zm184 512a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm224 0a48 48 0 1 0 0-96 48 48 0 1 0 0 96z"/>
            </svg>
            <?php if(isset($_SESSION['username'])): ?>
                <p onclick="window.location.href='user.php'" id="menu-Username" style="cursor: pointer;"></p>
                <?php if(!empty($_SESSION['img'])): ?>
                    <div id="user-account" onclick="window.location.href='user.php'">
                        <img id="user-avatar" src="../upload/<?= htmlspecialchars($_SESSION['img'])?>" alt="avatar">
                    </div>
                <?php endif; ?>
            <?php else: ?>
                    <input type="submit" value="Login" id="login-input" onclick="window.location.href='reglog.php'" hidden>
                    <label for="login-input">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 448 512">
                            <path d="M144 128a80 80 0 1 1 160 0 80 80 0 1 1 -160 0zm208 0a128 128 0 1 0 -256 0 128 128 0 1 0 256 0zM48 480c0-70.7 57.3-128 128-128l96 0c70.7 0 128 57.3 128 128l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8c0-97.2-78.8-176-176-176l-96 0C78.8 304 0 382.8 0 480l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8z"/>
                        </svg>
                    </label>
            <?php endif; ?>
        </div>
<div id="fast-menu">
    <div class="menu-item">
        <div class="menu-title">TRINITY</div>
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
        <div class="menu-title">TRINITY LADIES</div>
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
        <div class="menu-title" onclick="window.location.href='voucher.php'">GIFT VOUNCHER</div>
    </div>
    <div class="menu-item">
        <div class="menu-title" onclick="window.location.href='userTier.php'">TRINITY TIER</div>
    </div>
    <div class="menu-item">
        <div class="menu-title" onclick="window.location.href='about.php'">ABOUT</div>
    </div>
</div>
</section>
<footer class="footer-2">
  <div class="footer-container">
    <div class="footer-left">
      <p class="footer-label">CONTACT US</p>
      <h2 class="footer-title">
        Let’s Discuss Your <br> Style. With Us
      </h2>

      <button class="footer-btn" onclick="window.location.href='contact.php'">
        Schedule a call now →
      </button>

      <p class="footer-email-label">OR EMAIL US AT</p>

      <div class="footer-email">
        triple3tbusiness@gmail.com
        <span>📋</span>
      </div>
    </div>

    <div class="footer-right">
      <div class="footer-col">
        <p class="footer-col-title">QUICK LINKS</p>
        <a href="#head">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="voucher.php">Vouchers</a>
        <a href="userTier.php">User Tier</a>
        <a href="about.php">About Us</a>
      </div>
      <div class="footer-col">
        <p class="footer-col-title">INFORMATION</p>
        <a href="../legal/term-of-service.php">Terms of Service</a>
        <a href="../legal/privacy-policy.php">Privacy Policy</a>
        <a href="../legal/delivery-policy.php">Delivery Policy</a>
        <a href="../legal/ai-usage-policy.php">AI Usage Policy</a>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p>Copyright (c) 2026 trinity-newfed</p>
    <div class="footer-social">
      <span>f</span>
      <span>t</span>
      <span>ig</span>
      <span>in</span>
    </div>
  </div>
</footer>
    <script>
      const email = <?= isset($_SESSION['username']) ? json_encode($_SESSION['username']) : '""' ?>;
      const userWelcome = document.getElementById("menu-Username");
        let username1 = email.split("@")[0] || "";
        let displayName = username1.length > 6
        ? username1.substring(0, 6) + "..."
        : username1;

        if(userWelcome){
            userWelcome.textContent = "Hi, " + displayName;
        }

      function CopyText() {
        navigator.clipboard.writeText("#Trinity120605050106").then(() => {
          alert("Đã sao chép mã vận đơn!");
        });
      }
      const prices = document.querySelectorAll(".ItemPrice");
      prices.forEach(price =>{
        price.textContent = "$" + parseFloat(price.dataset.price).toFixed(0);
      });

      const shortPrices = document.querySelectorAll(".short");
      shortPrices.forEach(prices =>{
        prices.textContent = "$" + parseFloat(prices.dataset.price).toFixed(0);
      });
    </script>
  </body>
</html>
