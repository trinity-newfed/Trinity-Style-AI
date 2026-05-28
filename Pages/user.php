<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
session_start();
if(!isset($_SESSION['user_id'])){
  header("Location: reglog.php");
  exit();
}
$username = $_SESSION['username'];
$userID = $_SESSION['user_id'];

$sql = "SELECT * FROM userdata
        WHERE id = ?";
$stmt = $conn->prepare($sql);
if(!$stmt){
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$img = "SELECT * FROM tryon WHERE user_id = ?";
$stmt = $conn->prepare($img);
$stmt->bind_param("i", $userID);
$stmt->execute();

$fetchData = $stmt->get_result();
$tryonData = $fetchData->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$sql = "SELECT 
        orders.id,
        order_items.order_id,
        orders.order_state,
        orders.order_name,
        orders.order_original_price,
        orders.order_final_price,
        orders.created_at,
        order_items.product_name,
        order_items.img,    
        order_items.quantity
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        WHERE orders.user_id = ?
        ORDER BY orders.id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_all(MYSQLI_ASSOC);
$groupedOrders = [];
$count = 0;
foreach($data as $d){
    $orderID = $d['id'];
    
    if (!isset($groupedOrders[$orderID])) {
        $groupedOrders[$orderID] = [
            'order_info' => $d,
            'total_items' => 0
        ];
    }
    

    $groupedOrders[$orderID]['total_items'] += $d['quantity'];
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    montserrat: ['Montserrat', 'sans-serif'],
                },
                colors: {
                    gold: {
                        light: '#E6CA65',
                        DEFAULT: '#D4AF37',
                        dark: '#B1953B',
                    }
                }
            }
        }
    }
</script>
    <link rel="stylesheet" href="../Css/nav.css">
    <link rel="stylesheet" href="../Css/user.css">
    <title>Trinity Style - User</title>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link
      href="https://fonts.googleapis.com/css2?family=Birthstone&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
      rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
  </head>
  <body>
<section id="head">
<section id="menu">
        <input type="checkbox" id="menu-toggle" hidden>
        <label class="hamburger" for="menu-toggle">
            <svg viewBox="0 0 32 32">
                <path class="line line-top-bottom" d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22"></path>
                <path class="line" d="M7 16 27 16"></path>
            </svg>
        </label>

        <div id="text-menu" class="userPHP">

            <div id="logo" class="userPHP" onclick="window.location.href='../Pages/'">TRINITY</div>
            
            <div id="text" class="userPHP">
                <span onclick="window.location.href='#'" class="orderBlock">Order</span>
                <span onclick="window.location.href='#'" class="profileBlock">Profile</span>
            </div>

            
        </div>
        
        <div id="utility-menu">
            
            <?php if(isset($_SESSION['username'])): ?>
                <p onclick="window.location.href='user.php'" class="menu-Username account" style="cursor: pointer;"></p>
            <?php else: ?>
                    <input type="submit" value="Login" id="login-input" onclick="window.location.href='reglog.php'" hidden>
                    <label for="login-input">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon user" viewBox="0 0 448 512">
                            <path d="M144 128a80 80 0 1 1 160 0 80 80 0 1 1 -160 0zm208 0a128 128 0 1 0 -256 0 128 128 0 1 0 256 0zM48 480c0-70.7 57.3-128 128-128l96 0c70.7 0 128 57.3 128 128l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8c0-97.2-78.8-176-176-176l-96 0C78.8 304 0 382.8 0 480l0 8c0 13.3 10.7 24 24 24s24-10.7 24-24l0-8z"/>
                        </svg>
                    </label>
            <?php endif; ?>

            <?php if(!empty($user['img']) || $user['img'] != null): ?>
                <img src="../<?=$user['img']?>" class="w-[40px] h-[40px] object-scale-down rounded-full border border-solid border-black-400" alt="">
            <?php else: ?>
                <img class="w-[40px] h-[40px] object-scale-down rounded-full border border-solid border-black-400" src="../Pictures/Banners/BA.webp" alt="">
            <?php endif; ?>
        </div>

        <div id="fast-menu">
            <div id="fast-menu-container">
                <div class="menu-item">
                    <div class="orderBlock menu-title"><span>Order</span></div>
                </div>

                <div class="menu-item">
                    <div class="profileBlock menu-title"><span>Profile</span></div>
                </div>


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
                    <?php foreach($baseProduct as $p):?>
                        <div class="item" data-name="<?=$p['product_name']?>">
                            <div class="item-Img">
                                <img src="../<?=$p['product_img']?>" alt="" onclick="window.location.href='detail.php?id=<?=$p['id']?>'">
                            </div>

                            <div>
                                <h4 onclick="window.location.href='detail.php?id=<?=$p['id']?>'"><?=$p['product_name']?></h4>
                                <span>$<?=$p['product_price']?></span>
                            </div>
                        </div>
                    <?php endforeach; ?> 
            </div>   

            <button id="searchBtn" onclick="window.location.href='products.php'"><p>View All Products</p></button>
        </div>

    </section>

</section>
    <section class="body">
      <div class="user-box">
        
        <div class="user-cart">
          <div class="title px-4 sm:px-2">
            <p class="w-[50px] sm:w-[150px]">Your Orders</p>
            <div class="flex gap-x-2 sm:gap-x-5 pl-1">
                <div id="order-state-option" class="flex justify-between items-center relative">
                    <span>Success</span>
                    <div class="w-fit h-fit flex justify-center items-center">
                        <svg class="svg select transition-all duration-300 w-[13px] h-[13px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path d="M169.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L192 205.3 54.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/>
                        </svg>
                    </div>

                    <div class="absolute top-[50px] left-[0] w-[100%] flex flex-col justify-start items-start p-1 sm:p-2 opacity-0  transition-all duration-300 h-[0px] overflow-hidden select-animate select bg-white z-[100] border rounded-[10px]">
                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 hover:bg-[whitesmoke]">Success</span>
                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 hover:bg-[whitesmoke]">Cancelled</span>
                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 hover:bg-[whitesmoke]">Delivery</span>
                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 hover:bg-[whitesmoke]">Delivered</span>
                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 hover:bg-[whitesmoke]">All</span>
                    </div>
                </div>

                <div id="order-state-layout" class="cursor-pointer flex z-[101] justify-between items-center relative border-b border-[rgba(0,0,0,0.3)] w-[55px]">
                    <span>View</span>
                    <div class="w-fit h-fit flex justify-center items-center">
                        <svg class="svg layout transition-all duration-300 w-[13px] h-[13px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                            <path d="M169.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L192 205.3 54.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/>
                        </svg>
                    </div>

                    <div class="absolute max-h-fit w-fit top-[50px] left-[0] w-[100%] flex flex-col justify-start items-start p-1 sm:p-2 opacity-0  transition-all duration-300 h-[0px] overflow-hidden select-animate layout bg-white z-[100] border rounded-[10px]">
                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 flex justify-between items-center text-end gap-1 hover:bg-[whitesmoke] active">
                            View
                            <svg xmlns="http://www.w3.org/2000/svg" height="19px" viewBox="0 -960 960 960" width="19px" fill="#000000">
                                <path d="M171.27-171.27v-617.46h617.46v617.46H171.27Zm569.5-47.96v-236.89H504.08v236.89h236.69Zm0-521.54H504.08v236.69h236.69v-236.69Zm-521.54 0v236.69h236.89v-236.69H219.23Zm0 521.54h236.89v-236.89H219.23v236.89Z"/>
                            </svg>
                        </span>

                        <span class="z-[100] text-start w-[100%] rounded-[5px] p-1 flex justify-between items-end text-end gap-1 hover:bg-[whitesmoke]">
                            List
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#000000">
                                <path d="M304-592.04V-640h483.92v47.96H304Zm0 135.62v-47.96h483.92v47.96H304Zm0 135.61v-47.96h483.92v47.96H304ZM198.75-589.73q-9.4 0-18.04-8.5-8.63-8.49-8.63-18.92 0-10.11 8.65-17.63 8.66-7.53 18.81-7.53 10.15 0 18.04 7.42 7.88 7.42 7.88 18.39 0 9.78-7.68 18.27-7.68 8.5-19.03 8.5Zm0 135.23q-9.4 0-18.04-8.54-8.63-8.54-8.63-18.72 0-10.77 8.65-18.45 8.66-7.67 18.81-7.67 10.15 0 18.04 7.52 7.88 7.53 7.88 19.19 0 9.62-7.68 18.15-7.68 8.52-19.03 8.52Zm.02 136.31q-9.39 0-18.04-8.4-8.65-8.4-8.65-18.58 0-10.72 8.65-18.56 8.66-7.85 18.81-7.85 10.15 0 18.04 7.71 7.88 7.71 7.88 19.1 0 9.78-7.67 18.18-7.68 8.4-19.02 8.4Z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
          </div>

        <div id="order-history" class="hidden">
            <?php if(!empty($groupedOrders)): ?>
            <?php foreach($groupedOrders as $order_id => $order): 
                $info = $order['order_info']; 
                $state = strtolower($info['order_state']);
                $time = date('j-n', strtotime($info['created_at']));
                $count++;
            ?>
            <div class="order-block bg-[#F9F9F9] p-5 justify-around">
                <button class="absolute right-[20px] z-[100] hidden cursor-pointer reBuy-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#A96424">
                        <path d="M263.33-429.62q-21.04 0-35.61-14.78-14.56-14.78-14.56-35.81 0-21.04 14.78-35.61 14.77-14.56 35.81-14.56 21.04 0 35.61 14.78 14.56 14.78 14.56 35.81 0 21.04-14.78 35.61-14.78 14.56-35.81 14.56Zm216.46 0q-21.04 0-35.61-14.78-14.56-14.78-14.56-35.81 0-21.04 14.78-35.61 14.78-14.56 35.81-14.56 21.04 0 35.61 14.78 14.56 14.78 14.56 35.81 0 21.04-14.78 35.61-14.78 14.56-35.81 14.56Zm216.46 0q-21.04 0-35.61-14.78-14.56-14.78-14.56-35.81 0-21.04 14.78-35.61 14.78-14.56 35.81-14.56 21.04 0 35.61 14.78 14.56 14.78 14.56 35.81 0 21.04-14.78 35.61-14.77 14.56-35.81 14.56Z"/>
                    </svg>
                </button>
            <div class="order-state max-w-[350px] m-auto border-1 rounded-[5px] w-[100%] h-fit py-4 bg-[whitesmoke] opacity-[0.9] justify-start px-2 flex flex-col">
                <div>
                    <span class="state font-medium text-sm"><?=ucfirst($info['order_state'])?></span>
                </div>
                <span class="text-xs"><?=$time?></span>
            </div>
            
                <div class="order-img mt-[20px]" onclick="window.location.href='orderItem.php?id=<?=$info['id']?>'">
                    <img class="bg-[whitesmoke] border-1 opacity-[0.9]" src="../<?= htmlspecialchars($info['img'])?>" alt="">
                </div>

                

                    <div class="order-info">
                        <div class="order-img-info w-[100%] h-[80%]">
                            <div>
                                <span class="font-medium"><?=$order['total_items']?> item</span>
                                <h3 class="order-name font-light text-xs">#<?= htmlspecialchars($info['order_name']) ?></h3>
                            </div>

                            <span class="font-medium text-lg">$<?=number_format($info['order_final_price'])?></span>
                        </div>
                    </div>


                    <form action="../Database/reOrder.php" class="w-[100%] flex justify-center h-[60px]" method="POST" id="blockForm">
                        <input type="hidden" name="order_id" value="<?=$info['id']?>">
                        <button class="re-order w-[100%] h-[100%] border-black-100 border border-solid rounded-[5px]" type="submit">Re-Buy</button>
                    </form>
                </div>
                
            <?php endforeach; ?>
            <?php else: ?>
            <h3 class="h3-alert">Nothing here...</h3>
            <?php endif; ?>
            
        </div>

        <div id="profile" class="hidden relative flex-col w-[100%] h-[600px] p-2 sm:p-5 px-[10px] sm:px-[50px] mt-[20px] justify-start gap-[50px]">
            <div class="flex flex-col w-[100%] p-5 bg-white rounded-[5px] gap-1">
                <h4 class="font-medium">Basic Information</h4>
                <span class="text-sm opacity-[0.5]">Email</span>
                <span><?=$user['email']?></span>
                <span class="text-sm opacity-[0.5]">Sex</span>
                <span><?=$user['user_sex']?></span>
            </div>

            <div class="flex flex-col p-5 bg-white rounded-[5px] h-[50%] gap-1">
                <h4 class="font-medium">Address</h4>
                <span><?=$user['user_address']?></span>
                <span><?=$user['user_hotline']?></span>
            </div>

            <button onclick="window.location.href='logout.php'" class="justify-self-start w-fit p-2 border border-solid rounded-[5px] cursor-pointer border-b">Log out</button>

            <div class="border-b border-black-300 h-[1px] left-[0] right-[0] px-2 sm:px-5 bottom-[0]"></div>
        </div>
    </div>
    </section>
    <footer class="footer-2 h-[200px] flex flex-col justify-around relative m-auto bottom-[0]">
        <div class="border-t border-black-300"></div>
        <div class="w-[50%] sm:w-[40%] grid grid-cols-1 sm:grid-cols-3 gap-y-0 sm:gap-y-2 mt-[20px]">
            <span class="text-[#B1953B] font-[montserrat] font-light text-xs underline">Warranty Policy</span>
            <span class="text-[#B1953B] font-[montserrat] font-light text-xs underline">Privacy Policy</span>
            <span class="text-[#B1953B] font-[montserrat] font-light text-xs underline">Term of Service</span>
        </div>
    </footer>
<div id="product-modal">
  <div class="modal-container">
    <button id="closeModal">&times;</button>
    <img src="" alt="">
    <div id="modal-access">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg>
    </div>
  </div>  
</div>


<div id="edit-info">
    <div class="info-container">
        <button id="closeEdit">&times;</button>
        <form action="../Database/user_info_update.php" method="POST" enctype="multipart/form-data">
            <h2>Edit Information</h2>
            <div class="edit-avatar">
                <label for="edit-avatar">
                    <?php if(empty($user['img'])): ?>
                        <img src="../Pictures/Banners/BA.webp" alt="" id="temp-avatar">
                    <?php else: ?>
                        <img src="../<?=$user['img']?>" alt="" id="temp-avatar">
                    <?php endif; ?>
                </label>
                <input type="file" id="edit-avatar" name="img" accept="image/*" hidden>
            </div>
            <div style="display: flex; width: 90%; justify-content: space-between;">
                <label for="user_sex">Sex:</label>
                <?php if($user['user_sex'] == "Male"): ?>
                    <select name="user_sex" id="user_sex">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                </select>
                <?php elseif($user['user_sex'] == "Female"): ?>
                    <select name="user_sex" id="user_sex">
                        <option value="Female">Female</option>
                        <option value="Male">Male</option>
                        <option value="Other">Other</option>
                </select>
                <?php elseif($user['user_sex'] == "Other"): ?>
                    <select name="user_sex" id="user_sex">
                        <option value="Other">Other</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                </select>
                <?php endif; ?>
            </div>
            <div class="info-input-container">
                <label for="hotline">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M451.5-531.5Q440-543 440-560t11.5-28.5Q463-600 480-600t28.5 11.5Q520-577 520-560t-11.5 28.5Q497-520 480-520t-28.5-11.5ZM640-520q-17 0-28.5-11.5T600-560q0-17 11.5-28.5T640-600q17 0 28.5 11.5T680-560q0 17-11.5 28.5T640-520Zm160 0q-17 0-28.5-11.5T760-560q0-17 11.5-28.5T800-600q17 0 28.5 11.5T840-560q0 17-11.5 28.5T800-520Zm-2 400q-125 0-247-54.5T329-329Q229-429 174.5-551T120-798q0-18 12-30t30-12h162q14 0 25 9.5t13 22.5l26 140q2 16-1 27t-11 19l-97 98q20 37 47.5 71.5T387-386q31 31 65 57.5t72 48.5l94-94q9-9 23.5-13.5T670-390l138 28q14 4 23 14.5t9 23.5v162q0 18-12 30t-30 12ZM241-600l66-66-17-94h-89q5 41 14 81t26 79Zm358 358q39 17 79.5 27t81.5 13v-88l-94-19-67 67ZM241-600Zm358 358Z"/></svg>
                    Hotline:
                </label>
                <input type="text" inputmode="numeric" placeholder="<?=$user['user_hotline']?>" name="user_hotline" pattern="0[0-9]{9}" maxlength="10" id="hotline">
            </div>
            <div class="info-input-container">
                <label for="hotline">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000"><path d="M240-200h120v-240h240v240h120v-360L480-740 240-560v360Zm-80 80v-480l320-240 320 240v480H520v-240h-80v240H160Zm320-350Z"/></svg>
                    Address:
                </label>
                    <div class="address-container">
                        <input type="text" placeholder="<?=$user['user_address']?>" id="address" name="user_address" oninput="search(this, 'toList')">
                        <div id="toList" class="suggest"></div>
                    </div>
            </div>
            <button type="submit" class="re-order">Submit</button>
        </form>
    </div>
</div>

    <script>
        const email = <?= isset($_SESSION['username']) ? json_encode($_SESSION['username']) : '""' ?>;
        let username1 = email.split("@")[0] || "";
        let displayName = username1.length > 6
        ? username1.substring(0, 6) + "..."
        : username1;
        const userWelcome = document.querySelectorAll(".menu-Username");
        if(userWelcome){
            userWelcome.forEach(user => user.textContent = "Hi, " + displayName);
        }

        const imgPopup = document.querySelectorAll(".line3 img");
        const modal = document.getElementById("product-modal");
        const conModal = document.querySelector(".modal-container");
        const closeModal = document.getElementById("closeModal");
        const imgEdit = document.querySelector(".user-avatar");
        const edit = document.getElementById("edit");

        //Select order state

        const select = document.getElementById("order-state-option");
        select.addEventListener("click", function (){
            this.querySelector(".select-animate").classList.toggle("active");
            this.querySelector(".svg").classList.toggle("active");
            const currentState = this.querySelector("span").textContent;

            this.querySelector(".select-animate").querySelectorAll("span").forEach(span =>{
                span.classList.remove("active");
                if(span.textContent == currentState) span.classList.add("active");
                span.addEventListener('click', function(){
                    select.querySelector("span").textContent = this.textContent;
                });
            });

            const orderBlocks = document.querySelectorAll(".order-block");
            orderBlocks.forEach(block =>{
                const state = select.querySelector("span").textContent.toLowerCase();
                const blockState = block.querySelector(".state").textContent.toLowerCase();
                if(blockState.includes(state) || state == "all"){
                    block.style.display = "";
                }else{
                    block.style.display = "none";
                }
            });
        });

        const Blocks = document.querySelectorAll(".order-block");
        Blocks.forEach(blocks =>{
            const blockStates = blocks.querySelector(".state").textContent.toLowerCase();
            if(blockStates == "success"){
                blocks.style.display = "";
            }else{
                blocks.style.display = "none";
            }
        });

        //Select order layout
        const layout = document.getElementById("order-state-layout");
        layout.addEventListener('click', function(){
            this.querySelector(".select-animate").classList.toggle("active");
            this.querySelector(".svg").classList.toggle("active");
            const currentState = this.querySelector("span").textContent;
            
            const div = this.querySelector(".select-animate");
            div.querySelectorAll("span").forEach(span =>{
                
                if(span.textContent == currentState) span.classList.add("active");
                span.addEventListener('click', function(){
                    div.querySelectorAll("span").forEach(s => s.classList.remove("active"));
                    layout.querySelector("span").textContent = this.textContent;
                    
                    setTimeout(() => {
                        if(layout.querySelector("span").textContent.includes("List")){
                            Blocks.forEach(block => block.classList.add("list"));
                            document.getElementById("order-history").classList.add("list");
                        }
                            
                        else{
                            Blocks.forEach(block => block.classList.remove("list"));
                            document.getElementById("order-history").classList.remove("list");
                        }
                    }, 100);
                });
            });
        });
 
        //Close Select state
        document.addEventListener('click', function(e){
            if(!select.contains(e.target)){
                select.querySelector(".select-animate.select").classList.remove("active");
                select.querySelector(".svg.select").classList.remove("active");
            }
        });

        document.addEventListener('click', function(e){
            if(!layout.contains(e.target)){
                layout.querySelector(".select-animate.layout").classList.remove("active");
                layout.querySelector(".svg.layout").classList.remove("active");
            }
        });

        //Rebuy - list layout toggle
        document.querySelectorAll(".reBuy-toggle").forEach(reBuy => {
            reBuy.addEventListener('click', function() {
                const form = this.closest(".order-block").querySelector("#blockForm");
                if(form.style.display === "none" || form.style.display === "") form.style.display = "flex";
                else form.style.display = "none";
            });
        });

        //Menu close

        const fastMenuContainer = document.getElementById("fast-menu-container");
        const menuToggle = document.getElementById("menu-toggle");
        const hamburger = document.querySelector(".hamburger");

        document.addEventListener('click', function(e){
            if(menuToggle.checked && !hamburger.contains(e.target) && menuToggle !== e.target && !fastMenuContainer.contains(e.target)){
                menuToggle.checked = false;
            }
        });

        //Order & Profile toggle

        document.querySelector(".orderBlock").classList.add("active");
        let action = "";

        function OrderProfileToggle(){
            if(action == "order"){
                document.getElementById("order-history").style.display = "grid";
                document.getElementById("profile").style.display = "none";
                document.getElementById("order-state-option").style.display = "";
                document.getElementById("order-state-layout").style.display = "";
                document.querySelector(".title p").textContent = "Your Orders";
                document.querySelectorAll("#text span").forEach(span => span.classList.remove("active"));
                document.querySelectorAll(".orderBlock").forEach(order => order.classList.add("active"));
                menuToggle.checked = false;
            }else{
                document.getElementById("order-history").style.display = "none";
                document.getElementById("profile").style.display = "flex";
                document.querySelector(".title p").textContent = "Profile";
                document.getElementById("order-state-option").style.display = "none";
                document.getElementById("order-state-layout").style.display = "none";
                document.querySelectorAll("#text span").forEach(span => span.classList.remove("active"));
                document.querySelectorAll(".profileBlock").forEach(profile => profile.classList.add("active"));
                menuToggle.checked = false;
            }
        }

        document.querySelectorAll(".profileBlock").forEach(profile =>{
            profile.addEventListener('click', function(){
                action = "profile";
                OrderProfileToggle(action);
                this.classList.add("active");
            })
        });

        document.querySelectorAll(".orderBlock").forEach(order =>{
            order.addEventListener('click', function(){
                action = "order";
                OrderProfileToggle(action);
                this.classList.add("active");
            })
        });
        
        
        //Popup img edit
        if(imgPopup){
            imgPopup.forEach(img =>{
                img.addEventListener('click', ()=>{
                    modal.style.display = "flex";
                    const modalImg = conModal.querySelector("img");
                    modalImg.src = img.src;
                });
            });
        }

        closeModal.addEventListener('click', ()=>{
            modal.style.display = "none";
        });

        modal.addEventListener('click', function(e){
            if(e.target === modal) modal.style.display = "none";
        });

        const fileInput = document.getElementById("edit-avatar");
        const preview = document.getElementById("temp-avatar");
        fileInput.addEventListener('change', function(){
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

const addressInput = document.getElementById("address");
const suggestBox = document.getElementById("toList");

addressInput.addEventListener("focus", () => {
  suggestBox.style.display = "block";
});

document.addEventListener("click", function(e){
  if (!e.target.closest(".address-container")) {
    suggestBox.style.display = "none";
  }
});

let coords = {
  from: [106.5775, 10.8908],
  to: null
};

async function search(input, listId){
  const q = input.value;
  if (q.length < 3) return;

  const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(q)}&limit=5`);
  const data = await res.json();

  const list = document.getElementById(listId);
  list.innerHTML = "";

  data.features.forEach(place => {
    const name = place.properties.name || place.properties.city || place.properties.country;
    const div = document.createElement("div");
    div.className = "item";
    div.innerText = name;

    div.onclick = () => {
      input.value = name;
      list.innerHTML = "";

      if (listId === "fromList") {
        coords.from = place.geometry.coordinates;
      } else {
        coords.to = place.geometry.coordinates;
      }
    };

    list.appendChild(div);
  });
}

        

const user_id = <?php echo json_encode($userID); ?>;

if(user_id){
  const interval = setInterval(async () =>{
    try {
      const res = await fetch(`http://localhost:5000/api/progress/${user_id}`);
      const data = await res.json();

      if(data.status === "done"){
        clearInterval(interval);
        const goUser = confirm("Redirect to user page for result?");
        if(goUser){
          window.location.href = data.redirect;
        }
      }
    }catch (err){
      console.error(err);
    }
  }, 3000);
}
    </script>
  </body>
</html>
