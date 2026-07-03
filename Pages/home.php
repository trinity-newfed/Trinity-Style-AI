<?php require "../component/home/header.php" ?>
<?php require "../component/cartItem.php" ?>
<!DOCTYPE html>
<html lang="vi" class="scroll-smooth bg-white">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRINITY</title>
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="../Css/home.css">
    <link rel="stylesheet" href="../Css/nav.css">

    <style>
        .reveal-curtain {
            clip-path: inset(100% 0 0 0);
            transition: clip-path 1.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-curtain.active {
            clip-path: inset(0 0 0 0);
        }

        .text-mask {
            overflow: hidden;
            display: block;
        }

        .text-mask span {
            display: inline-block;
            transform: translateY(110%);
            transition: transform 1.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-target.active .text-mask span {
            transform: translateY(0);
        }

        .reveal-fade {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 1.4s ease, transform 1.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal-fade.active {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes marquee {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .animate-marquee {
            display: flex;
            width: 200%;
            animation: marquee 25s linear infinite;
        }

        .img-zoom-hover {
            transition: transform 2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .img-zoom-container:hover .img-zoom-hover {
            transform: scale(1.04);
        }
    </style>
</head>

<body class="bg-white text-black font-sans antialiased selection:bg-black selection:text-white overflow-x-hidden">

    <?php require "../component/sectionMenu.php" ?>

    <main class="w-full">

        <section
            class="relative w-full h-screen flex flex-col md:flex-row items-center justify-center px-6 md:px-16 pt-20 border-b border-neutral-100">
            <div class="absolute left-6 md:left-16 bottom-24 md:bottom-32 z-10 space-y-4 reveal-target">
                <p class="text-[9px] tracking-[0.5em] text-neutral-400 font-mono uppercase">Collection 2026 / Noir Et
                    Blanc</p>
                <h1 class="text-6xl md:text-9xl font-extralight tracking-tighter uppercase leading-none">
                    <span class="text-mask"><span>ARCHITECTURAL</span></span>
                    <span class="text-mask"><span
                            class="font-serif italic font-normal ml-8 md:ml-20">SILHOUETTE</span></span>
                </h1>
            </div>
            <div
                class="w-full md:w-7/12 h-[65vh] md:h-[80vh] ml-auto overflow-hidden bg-neutral-50 img-zoom-container reveal-target">
                <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=1200"
                    alt="Hero Noir Editorial"
                    class="w-full h-full object-cover object-center reveal-curtain img-zoom-hover">
            </div>
        </section>

        <section
            class="py-40 md:py-56 px-6 md:px-12 max-w-5xl mx-auto text-left md:text-center space-y-10 reveal-target">
            <p class="text-[10px] tracking-[0.6em] text-neutral-400 font-mono uppercase reveal-fade">01 / BRAND ETHOS
            </p>
            <h2 class="text-2xl md:text-4xl font-extralight tracking-tight leading-relaxed uppercase text-neutral-900 reveal-fade"
                style="transition-delay: 150ms;">
                "We eliminate color to release form, turning raw fabric into a quiet sculpture embracing the body."
            </h2>
        </section>

        <section class="py-24 px-6 md:px-16 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 md:gap-8 items-start">
                <div class="md:col-span-5 space-y-6">
                    <div class="aspect-[3/4] overflow-hidden bg-neutral-50 w-full img-zoom-container reveal-target">
                        <img src="../Pictures/Banners/homeBanner2.png" alt="Lookbook 01"
                            class="w-full h-full object-cover reveal-curtain img-zoom-hover">
                    </div>
                    <div class="flex justify-between items-start text-[10px] tracking-widest uppercase reveal-target">
                        <span class="reveal-fade">Lookbook Piece 01</span>
                        <span class="text-neutral-400 reveal-fade" style="transition-delay: 100ms;">Structured Wool
                            Coat</span>
                    </div>
                </div>

                <div class="md:col-span-6 md:col-start-7 md:mt-48 space-y-8">
                    <div class="aspect-square overflow-hidden bg-neutral-50 w-full img-zoom-container reveal-target">
                        <img src="../Pictures/Banners/homeBanner3.png" alt="Details Look"
                            class="w-full h-full object-cover reveal-curtain img-zoom-hover">
                    </div>
                    <div class="space-y-4 max-w-sm reveal-target">
                        <h3 class="text-xs tracking-[0.4em] uppercase font-medium reveal-fade">02 / Asymmetrical Cutouts
                        </h3>
                        <p class="text-xs text-neutral-500 font-light leading-relaxed reveal-fade"
                            style="transition-delay: 150ms;">
                            Sharp boxy cuts contrast with the natural drape of premium fibers, defining a contemporary
                            look with subtle depth.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-32 px-6 md:px-16 max-w-4xl mx-auto space-y-12">
            <div class="border-b border-black pb-4 flex justify-between items-baseline reveal-target">
                <span class="text-xs tracking-[0.3em] uppercase font-medium reveal-fade">03 / THE ARCHIVE LIST</span>
                <span class="text-[9px] font-mono text-neutral-400 reveal-fade">2026 EDITION</span>
            </div>

            <div class="divide-y divide-neutral-100">
                <?php require "../component/home/feat.php"?>
            </div>
        </section>

        <section class="py-12 bg-neutral-50 overflow-hidden border-t border-b border-neutral-100 select-none">
            <div
                class="animate-marquee text-2xl md:text-5xl font-extralight tracking-[0.3em] uppercase text-black font-serif italic">
                <span>· LESS IS ALL · TRINITY EDITORIAL ARCHIVE 2026 · PIECE OF ART </span>
                <span>· LESS IS ALL · TRINITY EDITORIAL ARCHIVE 2026 · PIECE OF ART </span>
            </div>
        </section>

        <section class="py-32 px-6 md:px-16 max-w-7xl mx-auto space-y-12">
            <p class="text-[10px] tracking-[0.5em] text-neutral-400 font-mono uppercase">04 / VISUAL RYTHM</p>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 relative items-end">
                <div class="md:col-span-4 aspect-[4/5] bg-neutral-100 overflow-hidden img-zoom-container reveal-target">
                    <img src="https://images.unsplash.com/photo-1532453288672-3a27e9be9efd?q=80&w=600" alt="Grid Small"
                        class="w-full h-full object-cover reveal-curtain img-zoom-hover">
                </div>
                <div
                    class="md:col-span-8 aspect-[16/10] bg-neutral-100 overflow-hidden img-zoom-container reveal-target">
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200"
                        alt="Grid Landscape"
                        class="w-full h-full object-cover object-center reveal-curtain img-zoom-hover">
                </div>
            </div>
        </section>

        <section class="py-24 px-6 w-[100%] mx-auto gap-16 items-center relative">
            <img class="blur-[0px] w-[100%] max-h-[800px] object-cover"
                src="https://images.unsplash.com/photo-1618220179428-22790b461013?q=80&w=800">

            <div
                class="space-y-8 order-2 md:order-1 reveal-target absolute left-[50%] translate-x-[-50%] top-[60%] z-[100] flex flex-col items-center">
                <h3 class="text-4xl font-extralight tracking-tight uppercase reveal-fade"
                    style="transition-delay: 100ms;">Visit us on TRINITY</h3>
                <p class="text-xs text-neutral-200 bg-[rgba(0,0,0,0.2)] font-light leading-relaxed max-w-md reveal-fade"
                    style="transition-delay: 200ms;">
                    Where minimalism meets modern tailoring
                </p>
                <div class="reveal-fade" style="transition-delay: 300ms;">
                    <a href="products.php"
                        class="inline-block border-b border-white pb-1 text-[10px] text-[white] tracking-widest uppercase hover:text-neutral-400 hover:border-[gray] transition-colors">Show
                        All</a>
                </div>
            </div>
        </section>

        <?php require "../component/sectionFooter.php" ?>

    </main>
    <script src="../asset/contact.js"></script>
    <script src="../asset/headerEmail.js"></script>
    <script src="../asset/search.js"></script>
    <script src="../asset/homeJS/home.js"></script>
</body>

</html>