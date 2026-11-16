<?php
include('host.php');

if ($conn->connect_error) {
    die("error" . $conn->connect_error);
}
session_start();
if((!isset($_SESSION['role']) && $_SESSION['role'] != "adminTan") || (!isset($_SESSION['role']) && $_SESSION['role'] != "adminTrung")){
    $_SESSION['error'] = "Restrict permission!";
    header("Location: ../Pages/");
    exit();
}

$id = $_GET['id'];
$color = $_GET['color'];
$product = $conn
    ->execute_query("SELECT 
                products.id AS id,
                products.product_name, 
                products.product_is_delete,
                products.product_state,
                products.product_describe,
                products.product_type,

                product_variant.id AS variant_id,
                product_variant.product_img as product_img,
                product_variant.product_price, 
                product_variant.product_color,
                product_variant.product_size,
                product_variant.product_stock

             FROM products
             INNER JOIN product_variant ON products.id = product_variant.product_id
             WHERE products.id = ?
             AND product_variant.product_color = ?",[$id, $color])
    ->fetch_all(MYSQLI_ASSOC);
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

.avatar img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: scale-down;
    border: 2px solid #eee;
    background: #b2b2b2;
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

<?php foreach ($product as $row): ?>
<form action="update_admin.php" method="post" enctype="multipart/form-data">

<div class="card">
    <h2>Product Update #<?= $row['id']?></h2>

    <input type="hidden" name="id" value="<?= $row['id'] ?>">
    <input type="hidden" name="old_img" value="../<?=$row['product_img'] ?>">

    <div class="form-grid">

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="product_name" value="<?= $row['product_name']?>">
        </div>

        <div class="form-group">
            <label>Product Price</label>
            <input type="text" name="product_price" value="<?= $row['product_price'] ?>">
        </div>

        <div class="form-group">
            <label>Product Type</label>
            <input type="text" name="product_type" value="<?= $row['product_type'] ?>">
        </div>

        <div class="form-group">
            <label>Product State</label>
            <?php if($row['product_state'] == "active"): ?>
                <select name="product_state">
                    <option value="active" selected>active</option>
                    <option value="inactive">inactive</option>
                </select>
            <?php else: ?>
                <select name="product_state">
                    <option value="inactive" selected>inactive</option>
                    <option value="active">active</option>
                </select>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Product Color</label>
            <input type="text" name="product_color" value="<?= $row['product_color'] ?>">
        </div>

        <div class="form-group">
            <label>Stocks</label>
            <input type="text" name="product_stock" value="<?= $row['product_stock'] ?>">
        </div>

        <div class="form-group">
            <label>Product Describe</label>
            <textarea class="w-full custom-scrollbar auto-expand overflow-hidden transition-all duration-150 resize-none py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600" name="product_describe"><?= $row['product_describe'] ?></textarea>
    </div>
    <button class="absolute bottom-3 p-2 py-3 border border-bg-black bg-black text-white w-[10rem]" type="submit">Update</button>
</div>
</form>
<?php endforeach; ?>

<?php foreach ($product as $row): ?>

<div class="flex items-start py-4 gap-x-5">
    <form action="update_employee_img.php" method="post">
        <div class="form-group">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <label for="file" class="w-[110px] h-[110px] border-dashed border-2 rounded cursor-pointer hover:border-black flex items-center justify-center text-center">
                <span>Choose New Image</span>
                <input class="hidden" id="file" type="file" placeholder="Ảnh" name="img" value="">
            </label>
        </div>
        <button class="relative mt-3 p-2 bg-black text-white hover:cursor-pointer" type="submit">Update Image</button>
    </form>

    <div class="avatar flex flex-col gap-x-1">
        <img src="../<?= $row['product_img'] ?>">
        <span class="text-xs">Current Image</span>
    </div>
</div>

<?php endforeach; ?>
</div>
</body>
<script>
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
</script>
</html>
