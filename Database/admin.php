<?php
include('host.php');

session_start();
if((!isset($_SESSION['role']) && $_SESSION['role'] != "adminTan") || (!isset($_SESSION['role']) && $_SESSION['role'] != "adminTrung")){
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/home.php");
    exit();
}

require "../component/admin/fetch.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link rel="stylesheet" href="../Css/admin.css">
</head>
<body>
    <div id="head">

        <?php require "../component/admin/modal.php" ?>

        <div id="header">
            <div class="brand" onclick="window.location.href='../Pages/'">TRINITY</div>
            <div class="head-section" id="h-product">Product</div>
            <div class="head-section" id="h-user">User</div>
            <div class="head-section" id="h-voucher">Voucher</div>
            <div class="head-section" id="h-order">Order</div>
            <div class="dashboard" onclick="window.location.href='dashboard.php'">Dashboard<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-105.4 105.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></div>
            <div></div>
        </div>
        <div id="header-body">
            <div id="search">
                <div id="s-product" class="search-div">
                    <input type="text" placeholder="Search " id="search-input-1" class="search">
                    <label id="total-products" style="text-align: center;"></label>
                    <div id="add-product" class="cursor-pointer bg-black text-white p-3 border rounded-[10px]">Add new product</div>

                    <?php if(empty($product)): ?>
                        <form action="insert_product_database_admin.php" method="post">
                            <input class="cursor-pointer bg-black text-white p-3 border rounded-[10px]" type="submit" value="Add database (product)">
                        </form>
                    <?php endif; ?>

                </div>

                <div id="s-user" style="display: none;" class="search-div">
                    <input type="text" placeholder="Search" id="search-input-2" class="search">
                    <label id="total-users" style="text-align: center;"></label>
                </div>

                <div id="s-voucher" style="display: none;" class="search-div">
                    <input type="text" placeholder="Search" id="search-input-3" class="search">
                    <label id="total-vouchers" style="text-align: center;"></label>
                    <div id="add-voucher" class="cursor-pointer bg-black text-white p-3 border rounded-[10px]">Add new voucher</div>

                        
                    <?php if(empty($product)): ?>
                        <form action="insert_voucher_database_admin.php">
                            <input class="cursor-pointer bg-black text-white p-3 border rounded-[10px]" type="submit" value="Add database (voucher)">
                        </form>
                    <?php endif; ?>
                    
                </div>

                <div id="s-order" style="display: none;" class="search-div">
                    <input type="text" placeholder="Search" id="search-input-4" class="search">
                    <label id="total-orders" style="text-align: center;"></label>
                </div>
            </div>
            <div id="list">
                <div id="product" class="grid h-[85%] overflow-y-auto list-view">
                    <?php require "../component/admin/product.php" ?>
                </div>

                <div id="user" style="display: none" class="list-view">
                    <?php require "../component/admin/user.php" ?>
                </div>
                
                <div id="voucher" style="display: none" class="list-view grid overflow-y-auto h-[80%]">
                    <?php require "../component/admin/voucher.php" ?>
                </div>

                <div id="order" style="display: none" class="list-view grid overflow-y-auto h-[80%]">
                    <?php require "../component/admin/order.php" ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        const h_product = document.getElementById("h-product");
        const product = document.getElementById("product");
        const section = document.querySelectorAll(".head-section");
        const lists = document.querySelectorAll(".list-view");
        const items = document.querySelectorAll(".items");
        const searchs = document.querySelectorAll(".search-div");
        const search_input_1 = document.getElementById("search-input-1");
        const search_input_2 = document.getElementById("search-input-2");
        const search_input_3 = document.getElementById("search-input-3");
        const search_input_4 = document.getElementById("search-input-4");
        const add_product = document.getElementById("add-product");
        const add_voucher = document.getElementById("add-voucher");

        h_product.classList.add("show");
        product.style.display = "grid";

        section.forEach(sec =>{
            sec.addEventListener('click', ()=>{
                section.forEach(s => s.classList.remove("show"));
                lists.forEach(list => list.style.display = "none");
                searchs.forEach(search => search.style.display = "none");
                sec.classList.add("show");
                const name = sec.id.replace("h-", "");
                document.getElementById(name).style.display = "grid";
                document.getElementById("s-" + name).style.display = "flex";
            });
        });

        //SEARCH BY NAME FEAT
        search_input_1.addEventListener('keyup', ()=>{
            const keyword = search_input_1.value.toLowerCase();
            const itemProduct = document.querySelectorAll(".items.product");

            itemProduct.forEach(item =>{
                const search = item.textContent.toLowerCase();
                if(search.includes(keyword)){
                    item.style.display = "";
                }else{
                    item.style.display = "none";
                }
            });
        });
        search_input_2.addEventListener('keyup', ()=>{
            const keyword = search_input_2.value.toLowerCase();
            const itemUser = document.querySelectorAll(".items.user");

            itemUser.forEach(item =>{
                const search = item.textContent.toLowerCase();
                if(search.includes(keyword)){
                    item.style.display = "";
                }else{
                    item.style.display = "none";
                }
            });
        });
        search_input_3.addEventListener('keyup', ()=>{
            const keyword = search_input_3.value.toLowerCase();
            const itemVoucher = document.querySelectorAll(".items.voucher");

            itemVoucher.forEach(item =>{
                const search = item.textContent.toLowerCase();
                if(search.includes(keyword)){
                    item.style.display = "";
                }else{
                    item.style.display = "none";
                }
            });
        });
        search_input_4.addEventListener('keyup', ()=>{
            const keyword = search_input_4.value.toLowerCase();
            const itemOrder = document.querySelectorAll(".items.order");

            itemOrder.forEach(item =>{
                const search = item.textContent.toLowerCase();
                if(search.includes(keyword)){
                    item.style.display = "";
                }else{
                    item.style.display = "none";
                }
            });
        });
        //SEARCH BY NAME FEAT








        add_product.addEventListener('click', ()=>{
            document.getElementById("modal-popup").classList.remove("invisible", "opacity-0");
        });
        document.getElementById("modal-popup").addEventListener('click', (e)=>{
            const form = document.getElementById("form-product");
            if(!form.contains(e.target)){
                document.getElementById("modal-popup").classList.add("invisible", "opacity-0");
            }
        });


        document.querySelectorAll('input[type="file"]').forEach(input =>{
            input.addEventListener('change', function (){
                const label = document.querySelector(`label[for="${this.id}"]`);
                const text = label.querySelector(".file-name");
                const image = label.querySelector(".preview");

                if(this.files.length > 0){
                    const file = this.files[0];
                    text.textContent = file.name;
                    image.src = URL.createObjectURL(file);
                }else{
                    text.textContent = "Select a product picture";
                    image.src = "";
                }
            });
        });





        const total_user = document.getElementById("total-users");
        const total_product = document.getElementById("total-products");
        const total_voucher = document.getElementById("total-vouchers");

        const user = document.querySelectorAll("#user .items");
        const products = document.querySelectorAll("#product .items");
        const voucher = document.querySelectorAll("#voucher .items");

    </script>
</body>
</html>