<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "TF_Database";

$conn = new mysqli($host, $user, $password, $dbname);
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== "admin"){
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/");
    exit();
}
//PRODUCT FETCH
$product = $conn
    ->query("SELECT * FROM products")
    ->fetch_all(MYSQLI_ASSOC);

//USER FETCH
$userdata = $conn
    ->query("SELECT * FROM userdata")
    ->fetch_all(MYSQLI_ASSOC);

//VOUCHER FETCH
$voucher = $conn
    ->query("SELECT * FROM vouchers")
    ->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <style>
        html{
            user-select: none;
        }
        #head{
            top: 0;
            width: 100vw;
            height: 100svh;
            max-width: 1500px;
            max-height: 900px;
            position: relative;
            margin: auto;
            display: flex;
            gap: 2%;
            align-items: center;
        }
        #header{
            position: sticky;
            width: 20%;
            height: 100%;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            margin-top: 5%;
            display: flex;
            flex-direction: column;
            justify-content: start;
            align-items: center;
        }
        .brand{
            border-bottom: 1px solid rgba(0, 0, 0, 0.5);
            padding: 20px 0;
            margin-bottom: 50px;
            font-size: 40px;
            width: 90%;
            height: 6%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .head-section{
            width: 90%;
            height: 6%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: default;
            border-radius: 20px;
            margin-top: 3px;
            border: 1px solid transparent;
            transition: .2s all;
        }
        .head-section:hover{
            background: white;
            color: black;
            transition: .2s all;
            cursor: pointer;
            border: 1px dashed black;
        }
        .dashboard{
            width: 90%;
            height: 6%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: default;
            border-radius: 20px;
            margin-top: 3px;
            border: 1px solid transparent;
            transition: .2s all;
            position: absolute;
            bottom: 10%;
            background: black;
            color: white;
            gap: 5px;
        }
        .dashboard:hover{
            background: white;
            color: black;
            fill: black;
            border: 1px solid black;
            cursor: pointer;
        }
        .dashboard:hover .icon{
            fill: black;
        }
        .icon{
            cursor: pointer;
            width: clamp(.75rem, 1.25vw, 1.9rem);
            height: clamp(.75rem, 1.25vw, 1.9rem);
            fill: white;
        }
        #header-body{
            width: 75%;
            height: 100%;
            position: relative;
            margin-top: 5%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
        }
        #list{
            position: relative;
            width: 100%;
            height: 100%;
            border-right: none;
            border-top: none;
            padding-top: 2%;
        }
        .list-view::-webkit-scrollbar{
            width: 0;
            height: 0;
        }
        .list-view{
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            overflow-x: hidden;
            padding: 30px 0;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
        }
        #search{
            width: 100%;
            min-height: 70px;
            border-top: none;
            display: flex; 
            justify-content: center;
            align-items: center;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }
        .search-div{
            width: 90%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-around;
            gap: 10px;
        }
        .search{
            width: 30%;
            height: 30px;
            background: whitesmoke;
            display: flex;
            align-items: center;
            justify-content: space-around;
            border: none;
            padding: 5px;
            border-radius: 10px;
        }
        .search:focus{
            border: none;
        }
        #search label{
            min-width: fit-content;
        }
        .add{
            width: 170px;
            text-align: center;
            cursor: pointer;
            padding: 10px;
            background: black;
            color: white;
            border-radius: 10px;
            border: 1px solid black;
            transition: .3s all;
        }
        .add:hover{
            background: white;
            color: black;
            border: 1px dashed black;
            transition: .3s all;
        }

        .head-section.show{
            background: black;
            color: white;
        }
        .head-section.show:hover{
            background: white;
            color: black;
            cursor: pointer;
            border: 1px dashed black;
        }

        .items{
            width: 200px;
            height: 320px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 3px 7px rgba(0, 0, 0, 0.5);
            border-radius: 5px;
            text-align: center;
            transition: .3s all;
            position: relative;
        }
        .items.user{
            width: 100px; 
            height: 100px; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center; 
            border: 1px solid black; 
            font-size: 10px; 
            border: none;
        }
        .items.voucher{
            width: 290px;
            height: 150px;
        }
        .items:hover{
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: .3s all;
            scale: 1.02;
        }
        .items:hover img{
            filter: brightness(90%);
        }
        .items img{
            width: 100%;
            height: 80%;
            object-fit: cover;
        }
        .stock{
            position: absolute;
            top: 0;
            font-weight: bold;
            color: rgb(126, 201, 255);
        }
        .stock.low{
            color: rgb(255, 126, 126);
        }
        .function-container{
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-around;
            font-size: clamp(.7rem, .8vw, .8rem);
        }
        .Delete{
            background: red;
            color: white;
            padding: 5px;
            border-radius: 3px;
            cursor: pointer;
            transition: .3s all;
        }
        .Delete:hover{
            transition: .3s all;
            background: rgb(133, 7, 7);
        }
        .Update{
            background: black;
            color: white;
            padding: 5px;
            border-radius: 3px;
            cursor: pointer;
            transition: .3s all;
        }
        .Update:hover{
            transition: .3s all;
            opacity: .7;
        }
        .Restore{
            background: rgb(0, 166, 255);
            color: white;
            padding: 5px;
            border-radius: 3px;
            cursor: pointer;
            transition: .3s all;
        }
        #user{
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            grid-template-rows: repeat(10, 100px);
            overflow-x: hidden;
        }
        #voucher{
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            grid-template-rows: repeat(10, 200px);
            overflow-x: hidden;
        }



        #modal-popup{
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 2;
            position: fixed;
            display: flex; 
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: .1s all;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        #modal-popup.show{
            visibility: visible;
            opacity: 1;
            transition: .5s all;
        }
        .form{
            width: 50%;
            height: 90%;
            max-width: 500px;
            max-height: 650px;
            background-color: white;
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.85);
            position: relative;
        }
        #form{
            padding-top: 10px;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: start;
        }
        .input{
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }
        .input-container{
            width: 100%;
        }
        .input-container label{
            display: flex;
            flex-direction: column;
        }
        .category-container{
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 2fr;
            border-bottom: 1px solid rgba(0, 0, 0, 0.2);
            padding: 10px 0;
        }
        .category-container label{
            display: inline;
            align-items: center;
        }
        #addBtn{
            position: absolute;
            padding: 10px 40px;
            border: none;
            background: black;
            color: white;
            cursor: pointer;
            bottom: 3%;
            left: 50%;
            transform: translateX(-50%);
        }
        #addBtn:hover{
            opacity: .8;
        }
        .file-label{
            flex-direction: row !important;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }
        .file-label img{
            object-fit: cover;
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
    <div id="head">
        <div id="modal-popup">
            <div id="form-product" class="form">
                <h2 style="margin: 0;">Add Item Form</h2>
            <form id="form" action="add_product_admin.php" method="post" enctype="multipart/form-data" style="gap: 10px; display: flex; flex-direction: column;">
                <div class="input-container">
                    <label for="product_name">
                        <span>Product Name</span>
                        <input type="text" name="product_name" class="input" id="product_name">
                    </label>
                </div>
                <div class="input-container">
                    <label>
                        <span>Product Price</span>
                        <input type="text" name="product_price" class="input" id="product_price">
                    </label>
                </div>
                <div class="input-container">
                    
                    <label for="product_describe">
                        <span>Product Describe</span>
                        <input type="text" name="product_describe" class="input" id="product_describe">
                    </label>
                </div>
                <div class="input-container">
                    <label for="product_color">
                        <span>Product Color</span>
                        <input type="text" name="product_color" class="input" id="product_color">
                    </label>
                </div>
                <div class="input-container">
                    <label for="product_size">
                        <span>Product Size</span>
                        <input type="text" name="product_size" class="input" id="product_size">
                    </label>
                </div>
                <div class="input-container" style="flex-direction: column;">
                    <div class="category-container">
                        
                        <label>
                            <input type="radio" name="product_category" class="input-radio" value="collections"  checked>
                            <span>Collections</span>
                        </label>
                        
                        <label>
                            <input type="radio" name="product_category" class="input-radio" value="men">
                            <span>Men</span>
                        </label>
                        
                        <label>
                            <input type="radio" name="product_category" class="input-radio" value="women">
                            <span>Women</span>
                        </label>
                
                        <label>
                            <input type="radio" name="product_category" class="input-radio" value="accesories">
                            <span>Accesories</span>
                        </label>
                    </div>
                </div>
                <div class="input-container" style="display: grid;">
                    <input type="file" name="product_img" id="product_img" hidden>
                    <label for="product_img" class="file-label">
                        <span class="file-name">Select a product picture</span>
                        <img class="preview">
                    </label>

                    <input type="file" name="product_img1" id="product_img1" hidden>
                    <label for="product_img1" class="file-label">
                        <span class="file-name">Select a product picture</span>
                        <img class="preview">
                    </label>

                    <input type="file" name="product_img2" id="product_img2" hidden>
                    <label for="product_img2" class="file-label">
                        <span class="file-name">Select a product picture</span>
                        <img class="preview">
                    </label>

                </div>
                <button type="submit" id="addBtn">Add</button>
            </form>
            </div>
            <div id="form-voucher" style="display: none;" class="form"></div>
        </div>
        <div id="header">
            <div class="brand" onclick="window.location.href='../Pages/'">TRINITY</div>
            <div class="head-section" id="h-product">Product</div>
            <div class="head-section" id="h-user">User</div>
            <div class="head-section" id="h-voucher">Voucher</div>
            <div class="dashboard" onclick="window.location.href='dashboard.php'">Dashboard<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-105.4 105.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></div>
            <div></div>
        </div>
        <div id="header-body">
            <div id="search">
                <div id="s-product" class="search-div">
                    <input type="text" placeholder="Search " id="search-input-1" class="search">
                    <label id="total-products" style="text-align: center;"></label>
                    <div id="add-product" class="add">Add new product</div>
                    <?php if(empty($product)): ?>
                    <form action="insert_product_database_admin.php" method="post">
                        <input class="add full" type="submit" value="add database (product)">
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
                    <div id="add-voucher" class="add">Add new voucher</div>
                    <?php if(empty($voucher)): ?>
                    <form action="insert_voucher_database_admin.php">
                        <input class="add full" type="submit" value="add database (voucher)">
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <div id="list">
                <div id="product" class="list-view">
                    <?php if(empty($product)): ?>
                        <span>Nothing in product table</span>
                    <?php else: ?>
                        <?php foreach($product as $p): ?>
                            <?php if($p['product_is_delete'] == 1): ?>
                                <div class="items" style="opacity: .8; filter: grayscale(50%);">
                                    <span style="color: red; transform: rotateZ(40deg); font-size: 20px; z-index: 3; position: absolute;">Deleted</span>
                                    <img src="../<?=$p['product_img']?>" alt="">
                                    <span><?=$p['product_name']?></span>
                                    <?php if($p['product_stock'] < 5): ?>
                                        <span class="stock low">Stock: <?=$p['product_stock']?></span>
                                    <?php else: ?>
                                        <span class="stock">Stock: <?=$p['product_stock']?></span>
                                    <?php endif; ?>
                                    <div class="function-container">
                                        <form action="restore_item_admin.php" method="post">
                                            <input type="submit" id="p-restore-<?=$p['id']?>" hidden>
                                            <input type="hidden" name="id" value="<?=$p['id']?>">
                                            <label class="Restore" for="p-restore-<?=$p['id']?>">Restore Product</label>
                                        </form>
                                        <span class="Update" onclick="window.location.href='update.php?id=<?=$p['id']?>'">Update product</span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="items" style="opacity: 1;">
                                    <img src="../<?=$p['product_img']?>" alt="">
                                    <span><?=$p['product_name']?></span>
                                    <?php if($p['product_stock'] < 5): ?>
                                        <span class="stock low">Stock: <?=$p['product_stock']?></span>
                                    <?php else: ?>
                                        <span class="stock">Stock: <?=$p['product_stock']?></span>
                                    <?php endif; ?>
                                    <div class="function-container">
                                        <form action="delete_item_admin.php" method="post">
                                            <input type="submit" id="p-delete-<?=$p['id']?>" hidden>
                                            <input type="hidden" name="id" value="<?=$p['id']?>">
                                            <label class="Delete" for="p-delete-<?=$p['id']?>">Delete Product</label>
                                        </form>
                                        <span class="Update" onclick="window.location.href='update.php?id=<?=$p['id']?>'">Update product</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div id="user" style="display: none;" class="list-view">
                    <?php if(empty($userdata)): ?>
                        <span>Nothing in user table</span>
                    <?php else: ?>
                        <?php foreach($userdata as $u): ?>
                            <div class="items user">
                                <?php if(empty($u['img'])): ?>
                                    <img style="width: 60px; height: 60px; border-radius: 50%;" src="../Pictures/Banners/BA.webp" alt="">
                                <?php else: ?>
                                    <img style="width: 60px; height: 60px; border-radius: 50%;" src="../upload/<?=$u['img']?>" alt="">
                                <?php endif; ?>
                                <span><?=$u['email']?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div id="voucher" style="display: none" class="list-view">
                    <?php if(empty($voucher)): ?>
                        <span>Nothing in voucher table</span>
                    <?php else: ?>
                        <?php foreach($voucher as $v):
                        $tierNames = ["All", "🥈 Silver", "🪙 Gold", "💎 Diamond"];
                        $tier = (int)$v['voucher_min_tier'];
                        ?>
                        <div class="items voucher">
                            <h3>Tier: <?=$tierNames[$tier - 1]?> || Type: <?=$v['voucher_type']?></h3>
                            <span>Discount: $<?=$v['voucher_discount']?></span>
                            <span>Max discount: $<?=$v['voucher_max']?></span>
                            <span>Condition: $<?=$v['voucher_condition']?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

            items.forEach(item =>{
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

            items.forEach(item =>{
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

            items.forEach(item =>{
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
            document.getElementById("modal-popup").classList.add("show");
        });
        document.getElementById("modal-popup").addEventListener('click', (e)=>{
            const form = document.getElementById("form-product");
            if(!form.contains(e.target)){
                document.getElementById("modal-popup").classList.remove("show");
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