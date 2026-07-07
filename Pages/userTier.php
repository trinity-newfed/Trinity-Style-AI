<?php require "../component/userTier/header.php" ?>
<?php require "../component/userTier/rank.php" ?>
<?php require "../component/userTier/img.php" ?>
<?php require "../component/userTier/nextTier.php" ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRINITY — MEMBERSHIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#fcfbf9] min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl bg-white rounded-3xl shadow-sm border border-gray-100 sm:p-12 text-center">
        
        <h1 class="text-xl sm:text-2xl font-bold tracking-wide text-gray-800 uppercase mb-6">
            Membership Status
        </h1>

        <div class="flex flex-col items-center mb-8">
            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-100 shadow-sm mb-3">
                <img src="../<?=$img?>" alt="Avatar" class="w-full h-full object-cover">
            </div>
            <h2 class="font-bold text-gray-900 tracking-wide uppercase text-base"><?=$users['email']?>.</h2>
            <p class="text-xs text-gray-400 mt-1">Member ID: <?=$users['id']?></p>
        </div>

        <div class="bg-[<?=$color?>] rounded-2xl p-6 mb-8 text-left max-w-md mx-auto shadow-sm relative overflow-hidden">
            <div class="absolute top-6 right-6 flex flex-col items-center text-[#7a7267]">
                <i class="fa-solid fa-award text-3xl opacity-40"></i>
                <span class="text-[10px] font-bold uppercase tracking-wider bg-[<?=$secondColor?>] px-2 py-0.5 rounded mt-1 text-gray-700"><?=$tier?></span>
            </div>

            <div class="pr-20">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Your rank</p>
                <h3 class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-tight uppercase mt-0.5"><?=$tier?></h3>
            </div>

            <div class="mt-8">
                <div class="flex justify-between text-xs font-medium text-gray-700 mb-1.5">
                    <span>Next tier: <?=$next?><strong class="font-bold"></strong></span>
                    <span><?=$totalSpent?>/<?=$tierSpent?> point</span>
                </div>
                <div class="w-full bg-[#dcd3c5] h-2 rounded-full overflow-hidden">
                    <div class="bg-[#8fa89b] h-full rounded-full" style="width: <?=($totalSpent/$tierSpent)*100?>%"></div>
                </div>
            </div>

            <p class="text-[11px] text-gray-500 mt-3 italic"></p>
        </div>

        <div class="mb-10">
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-widest mb-6">Your benefits</h4>
            <div class="grid grid-cols-3 gap-4 max-w-lg mx-auto">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 flex items-center justify-center text-gray-700 text-xl mb-2">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-600">Discount Vouchers</span>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 flex items-center justify-center text-gray-700 text-xl mb-2">
                        <i class="fa-solid fa-gift"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-600 leading-tight">Limited deals<br>only for you</span>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 flex items-center justify-center text-gray-700 text-xl mb-2">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-600">Support 24/7</span>
                </div>
            </div>
        </div>

        <div class="text-left max-w-md mx-auto border-t border-gray-100 pt-6 mb-10">
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-widest mb-4">Recent order</h4>
            <ul class="space-y-2.5 text-xs text-gray-600">
                <li class="flex flex-col items-start">
                    <?php require "../component/userTier/order.php" ?>
                </li>
            </ul>
        </div>

        <div class="flex flex-col sm:flex-row justify-center items-center gap-3 max-w-md mx-auto">
            <button class="w-full sm:w-1/2 bg-[#e8e2d9] hover:bg-[#dfd7cc] text-gray-800 font-bold text-xs uppercase tracking-wider py-3.5 px-6 rounded-xl transition shadow-sm">
                <a href="products.php" class="w-full h-full">Upgrade Now</a>
            </button>
            <button class="w-full sm:w-1/2 bg-white hover:bg-gray-50 text-gray-800 font-bold text-xs uppercase tracking-wider py-3.5 px-6 rounded-xl border border-gray-300 transition shadow-sm">
                <a href="user.php" class="w-full h-full">Account Management</a>
            </button>
        </div>

    </div>

</body>
</html>