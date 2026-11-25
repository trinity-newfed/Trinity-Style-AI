<?php
include('host.php');

if ($conn->connect_error) {
    die("error" . $conn->connect_error);
}
session_start();
if((!isset($_SESSION['role']) && $_SESSION['role'] != "adminTan") || (!isset($_SESSION['role']) && $_SESSION['role'] != "adminTrung")){
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/home.php");
    exit();
}

$id = $_GET['id'];
$order = $conn->query("SELECT 
            orders.id,
            order_items.order_id,
            orders.order_state,
            orders.order_name,
            orders.order_original_price,
            orders.order_final_price,
            orders.order_delivery_fee,
            orders.created_at,
            orders.discount,
            orders.ship_discount AS sdiscount,
            orders.order_address,

            order_items.product_name,    
            order_items.quantity AS order_quantity,
            product_variant.product_img AS img
        FROM orders
        JOIN order_items ON orders.id = order_items.order_id
        LEFT JOIN product_variant ON (
            order_items.product_id = product_variant.product_id 
            AND LOWER(TRIM(order_items.color)) = LOWER(TRIM(product_variant.product_color)))
        WHERE orders.id = $id")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Admin</title>
<link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
<script src="https://cdn.tailwindcss.com"></script>
<style>
@layer utilities {
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #252525;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #252525;
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #252525;
        }
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #585858 #ffffff;
        }
    }

body {
    font-family: Arial, sans-serif;
    background: #f4f6f8;
    padding: 40px;
    position: relative;
}

.container {
    max-width: 900px;
    margin: auto;
}

.card {
    background: #fff;
    padding: 24px;
    margin-bottom: 30px;
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

.card h2 {
    margin-bottom: 20px;
    color: #333;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group select{
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}
label {
    font-size: 14px;
    margin-bottom: 6px;
    color: #555;
}

input {
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
}

input:focus {
    outline: none;
    border-color: #4f46e5;
}

.avatar {
    grid-column: span 2;
    display: flex;
    align-items: center;
    gap: 16px;
}

button {
    margin-top: 20px;
    padding: 12px;
    border: none;
    border-radius: 10px;
    background: black;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
}

button:hover {
    background: gray;
}
</style>
</head>

<body>
<div class="container">

<?php foreach ($order as $row): ?>
<form action="update_order.php" method="post" enctype="multipart/form-data">

<div class="card">
    <h2 class="text-medium">Order Manage #<?= $row['id']?></h2>

    <input type="hidden" name="id" value="<?= $row['id'] ?>">

    <div class="avatar flex flex-col gap-x-1 relative items-start">
        <img class="h-[80px] w-[80px] object-cover bg-[#808080]" src="../<?= $row['img'] ?>">
    </div>

    <div class="form-grid">

        <div class="form-group">
            <label>Order Name</label>
            <input class="py-2 px-3" type="text" value="<?= $row['order_name']?>" readonly/>
        </div>

        <div class="form-group">
            <label>Order Total</label>
            <input class="py-2 px-3" type="text" value="<?= $row['order_original_price'] ?>" readonly/>
        </div>

        <div class="form-group">
            <label>Order Discount</label>
            <input class="py-2 px-3" type="text" value="<?= $row['discount']?>" readonly/>
        </div>

        <div class="form-group">
            <label>Order Delivery Discount</label>
            <input class="py-2 px-3" type="text" value="<?= $row['sdiscount']?>" readonly/>
        </div>

        <div class="form-group">
            <label>Order State</label>
                <input id="stateCurrent" type="hidden" value="<?=$row['order_state']?>">
                <select id="stateSelect" name="order_state">
                    <?php
                        $state = ['success', 'cancel', 'delivery', 'delivered'];
                        foreach ($state as $status): ?>
                        <option value="<?=$status?>" ><?=ucwords($status)?></option>
                    <?php endforeach; ?>
                </select>
        </div>

        <div class="form-group">
            <label>Order Address</label>
            <textarea class="w-full custom-scrollbar auto-expand overflow-hidden transition-all duration-150 resize-none py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600" name="order_address"><?= $row['order_address'] ?></textarea>
        </div>

        <div class="form-group">
            <label>Order Grand Total</label>
            <input class="py-2 px-3" type="text" value="<?= $row['order_final_price']?>" readonly/>
        </div>

    <button class="absolute bottom-3 p-2 py-3 border border-bg-black bg-black text-white w-[10rem]" type="submit">Update</button>
</div>
</form>

<div id="modal" class="modal fixed hidden top-0 left-0 w-[100%] h-[100%] flex justify-center items-center">
    <div class="modalBox absolute bg-white w-[25rem] h-[28rem] opacity-1 z-[2] flex items-end justify-end rounded p-4">
        <span class="absolute top-[1rem] w-[100%] text-center text-lg left-[50%] translate-x-[-50%]">PROOF OF DELIVERY UPLOAD</span>
        <div class="modalButtonsContainer flex gap-x-2">
            <div id="modalCancel" class="border border-gray-200 bg-gray-200 text-black rounded p-2 px-3 cursor-pointer hover:bg-gray-300">Cancel</div>
            <div id="modalUpload" class="border bg-black text-white rounded p-2 px-3 cursor-pointer hover:bg-gray-900">Upload</div>
        </div>
    </div>
    <div id="modalBackground" class="modalBackground opacity-[0.5] bg-black min-w-[100%] h-[inherit] relative left-[50%] translate-x-[-50%] top-[50%] translate-y-[-50%] z-[1]"></div>
</div>
<?php endforeach; ?>
</div>
</body>
<script>
    const stateCurrent = document.getElementById('stateCurrent').value.toLocaleLowerCase();
    const stateSelect = document.getElementById('stateSelect').querySelectorAll('option');
    const select = document.getElementById('stateSelect');
    const modalCancel = document.getElementById('modalCancel');
    const modalUpload =document.getElementById('modalUpload');
    const modal = document.getElementById('modal');
    const modalBackground = document.getElementById('modalBackground');

    function modalClose() {
        modal.classList.add('hidden');
        stateSelect.forEach(state => {
            const content = state.textContent.toLowerCase();
            
            if (content === stateCurrent) state.selected = this;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const textareas = document.querySelectorAll('textarea.auto-expand');

        textareas.forEach(textarea => {
            function adjustHeight() {
                textarea.style.height = 'auto'; 
                textarea.style.height = textarea.scrollHeight + 'px'; 
            }

            adjustHeight();

            textarea.addEventListener('input', adjustHeight);
        }); 
    });    

    window.addEventListener('load', function(e) {
        stateSelect.forEach(state => {
            const content = state.textContent.toLowerCase();
            
            if (content === stateCurrent) state.selected = this;
        });
    });

    select.addEventListener('change', function() {
        if(select.value.toLowerCase() === 'delivered') {
            modal.classList.remove('hidden');
        }
    });
    
    modalCancel.addEventListener('click', function(e) {
        modalClose();
    });

    modalBackground.addEventListener('click', function() {
        modalClose();
    });
</script>
</html>