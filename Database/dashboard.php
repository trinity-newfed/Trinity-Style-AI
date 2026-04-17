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
    ->query("SELECT * FROM products ORDER BY product_sold DESC LIMIT 5")
    ->fetch_all(MYSQLI_ASSOC);

$inventory = $conn
    ->query("SELECT * FROM products ORDER BY product_stock DESC LIMIT 5")
    ->fetch_all(MYSQLI_ASSOC);

$totalSold = 0;
$totalStock = 0;

foreach($product as $item){
    $totalSold += $item['product_sold'];
}
foreach($inventory as $i){
    $totalStock += $i['product_stock'];
}

//USER FETCH
$userdata = $conn
    ->query("SELECT * FROM userdata")
    ->fetch_all(MYSQLI_ASSOC);

//VOUCHER FETCH
$voucher = $conn
    ->query("SELECT * FROM vouchers")
    ->fetch_all(MYSQLI_ASSOC);

$year = 2026;
$date = date("Y:m");
$orders = $conn->query("SELECT
    COALESCE(SUM(CASE 
    WHEN created_at >= '$year-01-01' AND  created_at < '$year-02-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS jan,

    COALESCE(SUM(CASE 
    WHEN created_at >= '$year-02-01' AND created_at < '$year-03-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS feb,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-03-01' AND created_at < '$year-04-01' AND order_state = 'delivered'
    THEN order_final_price 
  END), 0) AS mar,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-04-01' AND created_at < '$year-05-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS apr,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-05-01' AND created_at < '$year-06-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS may,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-6-01' AND created_at < '$year-07-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS jun,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-7-01' AND created_at < '$year-08-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS jul,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-8-01' AND created_at < '$year-09-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS aug,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-9-01' AND created_at < '$year-10-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS sep,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-10-01' AND created_at < '$year-11-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS oct,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-11-01' AND created_at < '$year-12-01' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS nov,

  COALESCE(SUM(CASE 
    WHEN created_at >= '$year-12-01' AND created_at <= '$year-12-31' AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS dece,

  COALESCE(SUM(CASE 
    WHEN created_at >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01') 
    AND created_at <  DATE_FORMAT(NOW(), '%Y-%m-01')
    AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS lastMonth,

  COALESCE(SUM(CASE 
    WHEN created_at >= DATE_FORMAT(NOW(), '%Y-%m-01')
    AND created_at <  DATE_FORMAT(NOW() + INTERVAL 1 MONTH, '%Y-%m-01')
    AND order_state = 'delivered'
    THEN order_final_price  
  END), 0) AS thisMonth
FROM orders;
");
if(!$orders){
    die("SQL Error: " . $conn->error);
}

$res = $orders->fetch_assoc();

$revenueLM = $res['lastMonth'] <= 0 ? 1 : round(($res['lastMonth'] / 100), 2);
$revenueTM = $res['thisMonth'] <= 0 ? 1 : round(($res['thisMonth'] / 100), 2);
$grown = round(($res['thisMonth'] - $res['lastMonth']) / $res['lastMonth'] * 100, 2);

$recent = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 10");

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
            overflow-x: hidden;
        }
        #head{
            top: 0;
            width: 100vw;
            height: 100svh;
            max-width: 2000px;
            max-height: 1000px;
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
        .chart{
            position: relative;
            width: 100%;
            height: 45%;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2%;
        }
        .chart h3{
            top: 0;
            position: absolute;
            left: 2%;
            color: white;
            color: black;
        }
        .hot{
            width: 49%;
            height: 100%;
            display: flex;
            justify-content: space-around;
            align-items: end;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .revenue{
            width: 49%;
            height: 100%;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .Monthly{
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: white;
            position: relative;
        }
        .Grown{
            width: 60%;
            height: 60%;
            border-radius: 50%;
            background: white;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 12px;
        }
        .Grown :nth-child(1){
            color: green;
        }
        .Monthly span{
            text-align: center;
            width: 100%;
            bottom: -1.5rem;
            left: 50%;
            transform: translateX(-50%);
            position: absolute;
        }
        .explain{
            width: 100px;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .Monthlylabel{
            display: flex;
            gap: 3px;
        }
        .box{
            width: 10px;
            height: 10px;
            border: 3px solid black;
        }
        .box.last{
            background: #64748B;
        }
        .box.this{
            background: #4F46E5;
        }
        .venue.last{
            width: 50%;
            height: 100%;
        }
        .column{
            width: 40px;
            height: 0;
            max-height: 90%;
            display: flex;
            justify-content: center;
            align-items: start;
            position: relative;
            background: #eaeaea;
        }
        .column.top1{
            background: #2d2d2d;
        }
        .column.top2{
            background: #555;
        }
        .column.top3{
            background: #999;
        }
        .column img{
            width: 100%;
            height: 30px;
            top: 0;
            transform: translateY(-100%);
            object-fit: scale-down;
            position: absolute;
            background: rgba(0, 0, 0, 0.3);
        }
        .column span{
            font-size: 11px;
            position: absolute;
            bottom: 0;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, .05);
            color: rgb(255, 255, 255);
        }
        .chart.inventory .hot{
            width: 100%;
            color: black;
        }
        .chart.inventory span{
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.2);
            color: rgb(245, 245, 245);
        }
        .list-view::-webkit-scrollbar{
            width: 0;
            height: 0;
        }
        .list-view{
            position: relative;
            width: 100%;
            height: 55%;
            display: flex;
            padding-bottom: 20px;
            overflow-y: hidden;
            padding-top: 30px;
        }
        .lineChart{
            width: 90%;
            height: 100%;
            border: 1px solid black;
            display: flex;
            align-items: end;
            justify-content: space-around;
        }
        .line{
            height: 0;
            max-height: 95%;
            width: 45px;
            background: black;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: .8;
        }
        .line:hover{
            opacity: 1;
        }
        .line:hover .lineRevenue{
            opacity: 1;
            transition: .3s all;
        }
        .line span{
            opacity: 0;
            transition: .3s all;
            position: absolute;
            top: 0;
            transform: translateY(-100%);
        }
        .monthContainer{
            width: 90%;
            display: flex;
            align-items: start;
            justify-content: space-around;
        }
        .moneyScale{
            width: 10%;
            height: 100%;
            position: relative;
        }
        .max{
            top: 0;
            left: 0;
            position: absolute;
        }
        .mid{
            top: 50%;
            left: 0;
            position: absolute;
        }
        .min{
            left: 0;
            position: absolute;
            bottom: 0;
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
            width: 180px;
            height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
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
            bottom: 5%;
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
            <div class="head-section" id="h-order">Order</div>
            <div class="dashboard" onclick="window.location.href='admin.php'">Crud<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l370.7 0-105.4 105.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></div>
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
                <div id="s-order" style="display: none;" class="search-div">
                    <input type="text" placeholder="Search" id="search-input-3" class="search">
                    <label id="total-orders" style="text-align: center;"></label>
                    <?php if(empty($orders)): ?>
                    <?php endif; ?>
                </div>
            </div>
            <div id="list" class="listView product">
                <h3>Top Selling</h3>
                <div class="chart trending">
                    <div class="revenue">
                        <div class="Monthly" style="background: conic-gradient(#64748B 0deg <?=$revenueLM?>deg, #4F46E5 <?=$revenueLM?>deg <?=$revenueTM?>deg, #E5E7EB <?=$revenueTM?>deg 360deg);">
                            <div class="Grown">
                                <p data-value=<?=round($res['thisMonth'])?>><?=round($res['thisMonth'], 0)?>$</p>
                                <?php if($grown > 0): ?>
                                    <p>+<?=$grown?>%</p>
                                <?php else: ?>
                                    <p style="color: red;"><?=$grown?>%</p>
                                <?php endif; ?>
                            </div>
                            <span> Monthly Revenue</span>
                        </div>
                        <div class="explain">
                            <label class="Monthlylabel">
                                <div class="box last" id="boxLast"></div>    
                                <span>Last Month</span>
                            </label>

                            <label class="Monthlylabel">
                                <div class="box this"></div>
                                <span>This Month</span>
                            </label>
                        </div>
                    </div>
                    <div class="hot">
                        <?php foreach($product as $index => $p):?>
                            <div class="column
                            <?= $index == 0 ? 'top1' : '' ?> 
                            <?= $index == 1 ? 'top2' : '' ?>
                            <?= $index == 2 ? 'top3' : '' ?>" 
                            style="height: calc(<?=$p['product_sold']/$totalSold?>% * 100);">

                                <?php if($p['product_sold'] > 0): ?>
                                    <img src="../<?=$p['product_img']?>" alt="">
                                <?php endif; ?>
                                <span><?=$p['product_sold']?></span>  
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div id="product" class="list-view">
                    <div class="lineChart">
                        <div class="line" style="height: <?=$res['jan'] <= 0 ? 0 : ($res['jan'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['jan'])?>><?=round($res['jan'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['feb'] <= 0 ? 0 : ($res['feb'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['feb'])?>><?=round($res['feb'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['mar'] <= 0 ? 0 : ($res['mar'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['mar'])?>><?=round($res['mar'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['apr'] <= 0 ? 0 : ($res['apr'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['apr'])?>><?=round($res['apr'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['may'] <= 0 ? 0 : ($res['may'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['may'])?>><?=round($res['may'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['jun'] <= 0 ? 0 : ($res['jun'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['jun'])?>><?=round($res['jun'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['jul'] <= 0 ? 0 : ($res['jul'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['jul'])?>><?=round($res['jul'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['aug'] <= 0 ? 0 : ($res['aug'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['aug'])?>><?=round($res['aug'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['sep'] <= 0 ? 0 : ($res['sep'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['sep'])?>><?=round($res['sep'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['oct'] <= 0 ? 0 : ($res['oct'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['oct'])?>><?=round($res['oct'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['nov'] <= 0 ? 0 : ($res['nov'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['nov'])?>><?=round($res['nov'])?></span>
                        </div>
                        <div class="line" style="height: <?=$res['dece'] <= 0 ? 0 : ($res['dece'] / 100000) * 100?>%">
                            <span class="lineRevenue" data-value=<?=round($res['dece'])?>><?=round($res['dece'])?></span>
                        </div>
                    </div>
                    <div class="moneyScale">
                        <span class="max">-1.000.000$</span>
                        <span class="mid">-500.000$</span>
                        <span class="min">-0$</span>
                    </div>
                    <?php if(empty($product)): ?>
                        <span>No data in product table</span>
                    <?php else: ?>
                    <?php endif; ?>
                </div>
                <div class="monthContainer">
                    <div class="month">Jan</div>
                    <div class="month">Feb</div>
                    <div class="month">Mar</div>
                    <div class="month">Apr</div>
                    <div class="month">May</div>
                    <div class="month">Jun</div>
                    <div class="month">Jul</div>
                    <div class="month">Aug</div>
                    <div class="month">Sep</div>
                    <div class="month">Oct</div>
                    <div class="month">Nov</div>
                    <div class="month">Dec</div>
                </div>

                <div class="chart inventory">
                    <div class="hot">
                        <h3>Top Inventory</h3>
                        <?php foreach($inventory as $index => $i):?>
                            <div class="column
                            <?= $index == 0 ? 'top1' : '' ?> 
                            <?= $index == 1 ? 'top2' : '' ?>
                            <?= $index == 2 ? 'top3' : '' ?>" 
                            style="height: calc(<?=$i['product_stock']/$totalStock?>% * 100);">

                                <?php if($i['product_stock'] > 2): ?>
                                    <img src="../<?=$i['product_img']?>" alt="">
                                <?php endif; ?>
                                <span><?=$i['product_stock']?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div id="list" style="display: none;" class="listView user"></div>
            <div id="list" style="display: none;" class="listView order"></div>
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
        const revenue = document.querySelector(".Grown p");
        const monthlyRevenue = document.querySelectorAll(".lineRevenue");

        if(revenue.textContent.length > 6){
            revenue.textContent = (revenue.dataset.value / 1000000).toFixed(2) + "M";
        }

        monthlyRevenue.forEach(monthly =>{
            if(monthly.textContent.length > 6){
                monthly.textContent = (monthly.dataset.value / 1000000).toFixed(2) + "M";
            }else if(monthly.textContent.length > 4){
                monthly.textContent = (monthly.dataset.value / 1000).toFixed(1) + "K";
            }
        })

        h_product.classList.add("show");
        product.style.display = "flex";

        section.forEach(sec =>{
            sec.addEventListener('click', ()=>{
                section.forEach(s => s.classList.remove("show"));
                searchs.forEach(search => search.style.display = "none");
                sec.classList.add("show");
                const name = sec.id.replace("h-", "");
                const list = document.querySelectorAll(".listView");
                document.getElementById("s-" + name).style.display = "flex";
                list.forEach(li =>{
                    li.style.display = "none";
                    if(li.classList.contains(name)) li.style.display = "";
                });
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