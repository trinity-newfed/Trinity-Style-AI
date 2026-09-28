<?php require "../component/tryon/header.php" ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Virtual Try-On | Luxury Fashion</title>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0b0b0b;
        }
    </style>
</head>

<body class="text-zinc-100 min-h-screen flex flex-col justify-between">
    <input type="hidden" data-id="<?=$productID?>" id="productID">
    <header
        class="border-b border-zinc-800 bg-zinc-950/80 backdrop-blur-md sticky top-0 z-50 px-4 sm:px-6 py-4 flex flex-wrap sm:flex-nowrap justify-between items-center gap-3">
        <div class="text-sm tracking-widest font-light uppercase cursor-pointer">
            <a href="home.php">TRINITY <span class="font-semibold">ARCHIVE</span></a>
        </div>
        <div
            class="flex items-center gap-4 sm:gap-6 text-[11px] sm:text-xs tracking-wider uppercase text-zinc-400 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0">
            <a href="search.php?content=collections"
                class="whitespace-nowrap hover:text-white transition">Collection</a>
            <a href="#" class="whitespace-nowrap text-white pb-0.5">AI Try-On</a>
            <a href="contact.php" class="whitespace-nowrap hover:text-white transition">Contact</a>
        </div>
    </header>

    <main
        class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-6 md:p-8 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

        <section
            class="lg:col-span-7 bg-zinc-900/40 border border-zinc-800 rounded-2xl p-3.5 sm:p-5 md:p-6 flex flex-col gap-4 lg:sticky lg:top-24">

            <div id="slider-container" class="relative aspect-[3/4] max-h-[65vh] sm:max-h-[75vh] lg:max-h-none w-full bg-zinc-950 rounded-xl overflow-hidden group flex items-center justify-center border border-zinc-800/80 select-none">
                <div id="progressContainer" class="relative hidden w-40 h-40 flex items-center justify-center z-[3]">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle
                        cx="50"
                        cy="50"
                        r="20"
                        stroke-width="8"
                        class="text-zinc-800 stroke-current fill-none"
                        />
                        <circle
                        id="progress-circle"
                        cx="50"
                        cy="50"
                        r="20"
                        stroke-width="8"
                        stroke-dasharray="125.66"
                        stroke-dashoffset="125.66"
                        stroke-linecap="round"
                        class="text-indigo-500 stroke-current fill-none transition-all duration-500 ease-out"
                        />
                    </svg>

                    <div class="absolute flex items-center justify-center">
                        <span id="progress-text" class="text-1xl font-bold text-white">0%</span>
                    </div>
                </div>

                <img id="main-preview" src="" 
                    class="absolute inset-0 w-full h-full object-cover z-[2]"/>

                <img id="sub-preview" src="" 
                    class="absolute z-[1] inset-0 w-full h-full object-cover z-[1] opacity-0" /> 

                <input type="range" min="0" max="100" value="0" id="slider" 
                    class="absolute inset-0 w-full h-full opacity-0 z-[4] cursor-ew-resize" />

                <div id="slider-line" class="absolute top-0 bottom-0 z-[3] w-0.5 bg-white pointer-events-none right-0 -translate-x-1/2 shadow-[0_0_10px_rgba(0,0,0,0.5)]">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full shadow-lg flex items-center justify-center text-zinc-800 text-sm font-bold border border-zinc-200">
                        ↔
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3">
                <label
                    class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg bg-zinc-800/80 border border-zinc-700/60 text-zinc-100 text-xs font-semibold hover:bg-zinc-700 hover:border-zinc-500 cursor-pointer active:scale-[0.99] transition-all text-center">
                    <svg class="w-4 h-4 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Upload your image
                    <input type="file" class="hidden" id="uploadInput">
                </label>
            </div>

            <p class="text-[10px] sm:text-[11px] text-zinc-500 text-center leading-normal px-2">
                Uploaded photos are completely secure and automatically deleted after your session ends. By uploading an
                image, you agree to our
                <a class="text-sky-400 hover:underline" href="../legal/ai-usage-policy.php" target="_blank"
                    rel="noopener noreferrer">AI Usage Policy</a> and
                <a class="text-sky-400 hover:underline" href="../legal/term-of-service.php" target="_blank"
                    rel="noopener noreferrer">Terms of Service</a>.
            </p>
        </section>

        <section class="lg:col-span-5 flex flex-col gap-6 sm:gap-8">

            <?php require "../component/tryon/product-info.php" ?>

            <div class="border-t border-zinc-800/80 pt-5 sm:pt-6">
                <div class="flex justify-between text-xs mb-3">
                    <span class="text-zinc-400 uppercase tracking-wider text-[11px] sm:text-xs">1. Select other variant
                        colors</span>
                </div>
                <?php require "../component/tryon/variant.php"; ?>
            </div>

            <div class="border-t border-zinc-800/80 pt-5 sm:pt-6">
                <h3 class="text-[11px] sm:text-xs uppercase tracking-wider text-zinc-400 mb-3">2. Try on with different
                    style</h3>

                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                    <?php require "../component/tryon/other.php"; ?>
                </div>
            </div>

            <div
                class="p-3.5 sm:p-4 bg-amber-500/5 border border-amber-500/10 rounded-xl text-zinc-400 text-xs flex gap-3 leading-relaxed">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="text-[11px] sm:text-xs">
                    <strong class="text-zinc-200 font-medium block mb-0.5">Disclaimer</strong>
                    Please note that this service is powered by AI and outputs may contain inaccuracies. We encourage
                    you to read our
                    <a class="text-sky-400 hover:underline" href="../legal/ai-usage-policy.php" target="_blank"
                        rel="noopener noreferrer">AI Usage Policy</a> for detailed guidance prior to use.
                </div>
            </div>

            <div class="flex items-start gap-3 rounded-xl border border-neutral-800 bg-neutral-900/60 p-3.5 text-xs text-neutral-300 backdrop-blur-md shadow-sm">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                </svg>
    
                <p class="leading-relaxed">
                    <span class="font-medium text-white">For best results:</span> Upload a clear, <span class="text-white underline underline-offset-2 decoration-neutral-600">full-body photo</span>. AI try-on is a preview and may have minor imperfections.
                </p>
            </div>

            <div class="border-t border-zinc-800/80 pt-5 sm:pt-6 flex flex-col gap-3 pb-6 lg:pb-0">
                <button
                    class="w-full py-3.5 sm:py-4 bg-white text-zinc-950 font-semibold tracking-wider uppercase text-xs rounded-lg hover:bg-zinc-200 active:scale-[0.99] transition shadow-lg shadow-white/5">
                    Add to bag
                </button>

                <button
                    class="tryonBtn group relative w-full py-3.5 px-6 rounded-lg bg-zinc-950 border border-zinc-700/80 hover:border-zinc-400 text-zinc-100 font-medium text-xs tracking-[0.15em] uppercase transition-all duration-300 flex items-center justify-center gap-2 overflow-hidden shadow-md hover:shadow-zinc-700/20 active:scale-[0.99]">

                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out">
                    </div>

                    <svg class="w-4 h-4 text-zinc-400 group-hover:text-amber-300 transition-colors duration-300 shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z" />
                    </svg>

                    <span class="relative z-10 font-semibold text-zinc-200 group-hover:text-white transition-colors">
                        Virtual Try-On
                    </span>
                </button>
            </div>

        </section>
    </main>

    <?php require "../component/sectionFooter.php"; ?>

    <script src="../asset/tryonJS/tryon.js"></script>
</body>

</html>