<?php require "../component/user/header.php" ?>
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
                }
            }
        }
    }
</script>
    <link rel="stylesheet" href="../Css/nav.css">
    <link rel="stylesheet" href="../Css/user.css">
    <title>My Account - TRINITY</title>
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
                <span onclick="window.location.href='#'" class="orderBlock">Orders</span>
                <span onclick="window.location.href='#'" class="profileBlock">Profile</span>
            </div>

            
        </div>
        
        <div id="utility-menu">
            <?php require "../component/menu.php" ?>
            <?php require "../component/user/img.php" ?>
        </div>

        <div id="fast-menu">
            <div id="fast-menu-container">
                <div class="menu-item">
                    <div class="orderBlock menu-title"><span>Orders</span></div>
                </div>

                <div class="menu-item">
                    <div class="profileBlock menu-title"><span>Profile</span></div>
                </div>


            </div>
        </div>
    </section>

</section>
    <section class="body">
      <div class="user-box">
        
        <div class="user-cart">
          <div class="title px-4 sm:px-2">
            <p class="w-[50px] sm:w-[150px] text-black ml-[0px] sm:ml-[50px] text-[18px] tracking-widest font-sans font-medium">Your Orders</p>
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
            <?php require "../component/user/block.php" ?>
        </div>

        <div id="profile" class="hidden relative flex-col w-[100%] h-[600px] p-2 sm:p-5 px-[10px] sm:px-[50px] mt-[20px] justify-start gap-[50px]">
            <div class="flex flex-col w-[100%] p-5 bg-white rounded-[5px] gap-1">
                <h4 class="font-medium flex items-center gap-2">
                    Basic Information
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#A96424">
                        <path d="M220.04-220.04h41.23L655.54-614 614-655.54 220.04-260.96v40.92Zm-47.96 47.96v-109.5L663.23-774q7.25-7.61 15.56-10.77 8.31-3.15 17.74-3.15 9.36 0 17.86 3.11 8.49 3.12 17.19 10.43L774-732.65q7.31 8.19 10.61 16.54 3.31 8.35 3.31 17.57 0 9.83-3.38 18.61t-10.58 15.85l-492.38 492h-109.5Zm568.27-525.96-42.31-42.31 42.31 42.31Zm-105.82 63.51L614-655.54 655.54-614l-21.01-20.53Z"/>
                    </svg>
                </h4>
                <span class="text-sm opacity-[0.5]">Email</span>
                <span><?=$user['email']?></span>
                <span class="text-sm opacity-[0.5]">Sex</span>
                <span><?=$user['user_sex']?></span>
            </div>

            <div class="flex flex-col p-5 bg-white rounded-[5px] h-[50%] gap-1">
                <h4 class="font-medium flex items-center gap-2">
                    Address
                    <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#A96424">
                        <path d="M220.04-220.04h41.23L655.54-614 614-655.54 220.04-260.96v40.92Zm-47.96 47.96v-109.5L663.23-774q7.25-7.61 15.56-10.77 8.31-3.15 17.74-3.15 9.36 0 17.86 3.11 8.49 3.12 17.19 10.43L774-732.65q7.31 8.19 10.61 16.54 3.31 8.35 3.31 17.57 0 9.83-3.38 18.61t-10.58 15.85l-492.38 492h-109.5Zm568.27-525.96-42.31-42.31 42.31 42.31Zm-105.82 63.51L614-655.54 655.54-614l-21.01-20.53Z"/>
                    </svg>
                </h4>
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

    <script src="../asset/headerEmail.js"></script>
    <script src="../asset/userJS/user.js"></script>
  </body>
</html>
