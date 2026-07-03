<!DOCTYPE html>
<html lang="vi" class="scroll-smooth bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRINITY — ARCHIVE 2026</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="../Css/home.css">
    <link rel="icon" type="image/png" href="../Pictures/Banners/logo.png">
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
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
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

    <nav class="fixed top-0 w-full z-50 mix-blend-difference text-white px-6 md:px-12 py-6 flex justify-between items-center pointer-events-none">
        <a href="#" class="text-xs tracking-[0.6em] font-medium pointer-events-auto uppercase">TRINITY.STUDIO</a>
        <div class="hidden md:flex space-x-12 text-[10px] tracking-[0.25em] pointer-events-auto uppercase font-light">
            <a href="products.php" class="hover:opacity-40 transition-opacity">Archive</a>
            <a href="search.php?content=collections" class="hover:opacity-40 transition-opacity">Collections</a>
            <a href="#" class="hover:opacity-40 transition-opacity">Contact</a>
        </div>
        <button class="text-[10px] tracking-[0.2em] pointer-events-auto uppercase font-mono hover:opacity-40 transition-opacity">Bag (0)</button>
    </nav>

    <main class="w-full">

        <section class="relative w-full h-screen flex flex-col md:flex-row items-center justify-center px-6 md:px-16 pt-20 border-b border-neutral-100">
            <div class="absolute left-6 md:left-16 bottom-24 md:bottom-32 z-10 space-y-4 reveal-target">
                <p class="text-[9px] tracking-[0.5em] text-neutral-400 font-mono uppercase">Collection 2026 / Noir Et Blanc</p>
                <h1 class="text-6xl md:text-9xl font-extralight tracking-tighter uppercase leading-none">
                    <span class="text-mask"><span>ARCHITECTURAL</span></span>
                    <span class="text-mask"><span class="font-serif italic font-normal ml-8 md:ml-20">SILHOUETTE</span></span>
                </h1>
            </div>
            <div class="w-full md:w-7/12 h-[65vh] md:h-[80vh] ml-auto overflow-hidden bg-neutral-50 img-zoom-container reveal-target">
                <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=1200" 
                     alt="Hero Noir Editorial" 
                     class="w-full h-full object-cover object-center reveal-curtain img-zoom-hover">
            </div>
        </section>

        <section class="py-40 md:py-56 px-6 md:px-12 max-w-5xl mx-auto text-left md:text-center space-y-10 reveal-target">
            <p class="text-[10px] tracking-[0.6em] text-neutral-400 font-mono uppercase reveal-fade">01 / BRAND ETHOS</p>
            <h2 class="text-2xl md:text-4xl font-extralight tracking-tight leading-relaxed uppercase text-neutral-900 reveal-fade" style="transition-delay: 150ms;">
                "Chúng tôi triệt tiêu sắc màu để giải phóng cấu trúc, biến mỗi thớ vải thô thành một tác phẩm điêu khắc tĩnh lặng bao bọc cơ thể."
            </h2>
        </section>

        <section class="py-24 px-6 md:px-16 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-16 md:gap-8 items-start">
                <div class="md:col-span-5 space-y-6">
                    <div class="aspect-[3/4] overflow-hidden bg-neutral-50 w-full img-zoom-container reveal-target">
                        <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?q=80&w=800" 
                             alt="Lookbook 01" class="w-full h-full object-cover reveal-curtain img-zoom-hover">
                    </div>
                    <div class="flex justify-between items-start text-[10px] tracking-widest uppercase reveal-target">
                        <span class="reveal-fade">Lookbook Piece 01</span>
                        <span class="text-neutral-400 reveal-fade" style="transition-delay: 100ms;">Structured Wool Coat</span>
                    </div>
                </div>

                <div class="md:col-span-6 md:col-start-7 md:mt-48 space-y-8">
                    <div class="aspect-square overflow-hidden bg-neutral-50 w-full img-zoom-container reveal-target">
                        <img src="https://images.unsplash.com/photo-1485968579580-b6d095142e6e?q=80&w=800" 
                             alt="Details Look" class="w-full h-full object-cover reveal-curtain img-zoom-hover">
                    </div>
                    <div class="space-y-4 max-w-sm reveal-target">
                        <h3 class="text-xs tracking-[0.4em] uppercase font-medium reveal-fade">02 / ĐƯỜNG CẮT PHI TỶ LỆ</h3>
                        <p class="text-xs text-neutral-500 font-light leading-relaxed reveal-fade" style="transition-delay: 150ms;">
                            Phom dáng hộp dứt khoát kết hợp cùng độ rủ tự nhiên của vải sợi tự nhiên cao cấp, tạo nên diện mạo đương đại đầy chiều sâu.
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
                <div class="flex justify-between items-center py-8 group cursor-pointer reveal-target">
                    <div class="flex items-center space-x-8 md:space-x-16">
                        <div class="w-14 h-16 overflow-hidden bg-neutral-100 hidden md:block">
                            <img src="https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=300" alt="Item Preview" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <span class="text-[10px] font-mono text-neutral-400">01 /</span>
                        <h4 class="text-sm md:text-lg font-light tracking-wide uppercase transition-all duration-300 group-hover:translate-x-3">Raw Tailored Blazer</h4>
                    </div>
                    <span class="text-[10px] tracking-widest text-neutral-400 opacity-0 group-hover:opacity-100 transition-opacity">Explore &rarr;</span>
                </div>
                <div class="flex justify-between items-center py-8 group cursor-pointer reveal-target">
                    <div class="flex items-center space-x-8 md:space-x-16">
                        <div class="w-14 h-16 overflow-hidden bg-neutral-100 hidden md:block">
                            <img src="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?q=80&w=300" alt="Item Preview" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <span class="text-[10px] font-mono text-neutral-400">02 /</span>
                        <h4 class="text-sm md:text-lg font-light tracking-wide uppercase transition-all duration-300 group-hover:translate-x-3">Asymmetric Linen Trouser</h4>
                    </div>
                    <span class="text-[10px] tracking-widest text-neutral-400 opacity-0 group-hover:opacity-100 transition-opacity">Explore &rarr;</span>
                </div>
                <div class="flex justify-between items-center py-8 group cursor-pointer reveal-target">
                    <div class="flex items-center space-x-8 md:space-x-16">
                        <div class="w-14 h-16 overflow-hidden bg-neutral-100 hidden md:block">
                            <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=300" alt="Item Preview" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        </div>
                        <span class="text-[10px] font-mono text-neutral-400">03 /</span>
                        <h4 class="text-sm md:text-lg font-light tracking-wide uppercase transition-all duration-300 group-hover:translate-x-3">Minimalist Boxy Overcoat</h4>
                    </div>
                    <span class="text-[10px] tracking-widest text-neutral-400 opacity-0 group-hover:opacity-100 transition-opacity">Explore &rarr;</span>
                </div>
            </div>
        </section>

        <section class="py-12 bg-neutral-50 overflow-hidden border-t border-b border-neutral-100 select-none">
            <div class="animate-marquee text-2xl md:text-5xl font-extralight tracking-[0.3em] uppercase text-black font-serif italic">
                <span>· LESS IS ALL · TRINITY EDITORIAL ARCHIVE 2026 · PIECE OF ART </span>
                <span>· LESS IS ALL · TRINITY EDITORIAL ARCHIVE 2026 · PIECE OF ART </span>
            </div>
        </section>

        <section class="py-32 px-6 md:px-16 max-w-7xl mx-auto space-y-12">
            <p class="text-[10px] tracking-[0.5em] text-neutral-400 font-mono uppercase">04 / VISUAL RYTHM</p>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 relative items-end">
                <div class="md:col-span-4 aspect-[4/5] bg-neutral-100 overflow-hidden img-zoom-container reveal-target">
                    <img src="https://images.unsplash.com/photo-1532453288672-3a27e9be9efd?q=80&w=600" 
                         alt="Grid Small" class="w-full h-full object-cover reveal-curtain img-zoom-hover">
                </div>
                <div class="md:col-span-8 aspect-[16/10] bg-neutral-100 overflow-hidden img-zoom-container reveal-target">
                    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200" 
                         alt="Grid Landscape" class="w-full h-full object-cover object-center reveal-curtain img-zoom-hover">
                </div>
            </div>
        </section>

        <section class="py-24 px-6 md:px-16 max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div class="space-y-8 order-2 md:order-1 reveal-target">
                <p class="text-[10px] tracking-[0.5em] text-neutral-400 font-mono uppercase reveal-fade">05 / TACTILE SENSATION</p>
                <h3 class="text-3xl font-extralight tracking-tight uppercase reveal-fade" style="transition-delay: 100ms;">Cấu trúc thô mộc</h3>
                <p class="text-xs text-neutral-500 font-light leading-relaxed max-w-md reveal-fade" style="transition-delay: 200ms;">
                    Sự thô ráp của bê tông kết hợp cùng sự mộc mạc của sợi vải tự nhiên tạo nên một đối thoại thị giác hoàn mỹ. Chúng tôi tôn trọng tính nguyên bản của vật liệu.
                </p>
                <div class="reveal-fade" style="transition-delay: 300ms;">
                    <a href="#" class="inline-block border-b border-black pb-1 text-[10px] tracking-widest uppercase hover:text-neutral-400 hover:border-neutral-200 transition-colors">Xem chất liệu</a>
                </div>
            </div>
            <div class="aspect-[4/5] overflow-hidden bg-neutral-50 order-1 md:order-2 img-zoom-container reveal-target">
                <img src="https://images.unsplash.com/photo-1618220179428-22790b461013?q=80&w=800" 
                     alt="Concrete Texture Minimal" class="w-full h-full object-cover reveal-curtain img-zoom-hover">
            </div>
        </section>

        <section class="py-24 px-6 md:px-16 max-w-7xl mx-auto border-t border-neutral-100 grid grid-cols-1 md:grid-cols-3 gap-12 text-left">
            <div class="space-y-3 reveal-target">
                <h5 class="text-[11px] tracking-widest uppercase font-medium reveal-fade">01 / Limited Archive</h5>
                <p class="text-xs text-neutral-400 font-light leading-relaxed reveal-fade" style="transition-delay: 100ms;">Mỗi thiết kế đều được đánh số thứ tự sản xuất thủ công, không tái bản hàng loạt để bảo lưu tính độc bản.</p>
            </div>
            <div class="space-y-3 reveal-target">
                <h5 class="text-[11px] tracking-widest uppercase font-medium reveal-fade">02 / Minimal Packaging</h5>
                <p class="text-xs text-neutral-400 font-light leading-relaxed reveal-fade" style="transition-delay: 100ms;">Hộp đóng gói sử dụng chất liệu giấy mỹ thuật thô tái chế, mang dải màu xám đá nguyên bản sang trọng.</p>
            </div>
            <div class="space-y-3 reveal-target">
                <h5 class="text-[11px] tracking-widest uppercase font-medium reveal-fade">03 / Global Courier</h5>
                <p class="text-xs text-neutral-400 font-light leading-relaxed reveal-fade" style="transition-delay: 100ms;">Hỗ trợ giao hàng hoả tốc và áp dụng quy trình đổi trả minh bạch trong vòng 7 ngày tận nơi.</p>
            </div>
        </section>

        <footer class="bg-black text-white px-6 md:px-16 py-24 grid grid-cols-1 md:grid-cols-12 gap-12 items-end">
            <div class="md:col-span-5 space-y-4">
                <h6 class="text-4xl font-extralight tracking-[0.2em] uppercase">TRINITY</h6>
                <p class="text-[9px] tracking-widest text-neutral-500 font-mono uppercase">ESSENCE OVER EXCESS. EST 2026</p>
            </div>
            <div class="md:col-span-7 grid grid-cols-2 md:grid-cols-3 gap-8 text-[10px] tracking-[0.2em] uppercase font-light text-neutral-400">
                <div class="flex flex-col space-y-3">
                    <span class="text-neutral-600 font-medium">Studio</span>
                    <a href="#" class="hover:text-white transition-colors">Saigon Branch</a>
                    <a href="#" class="hover:text-white transition-colors">Lookbook Journal</a>
                </div>
                <div class="flex flex-col space-y-3">
                    <span class="text-neutral-600 font-medium">Customer Service</span>
                    <a href="#" class="hover:text-white transition-colors">Shipping & Returns</a>
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                </div>
                <div class="flex flex-col space-y-3 col-span-2 md:col-span-1">
                    <span class="text-neutral-600 font-medium">Contact</span>
                    <p class="font-mono text-[9px] text-neutral-500">triple3tbusiness@gmail.com</p>
                </div>
            </div>
        </footer>

    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const targets = document.querySelectorAll('.reveal-target');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {

                        entry.target.classList.add('active');


                        entry.target.querySelectorAll('.reveal-curtain, .reveal-fade').forEach(child => {
                            child.classList.add('active');
                        });


                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: "0px 0px -40px 0px"
            });

            targets.forEach(target => observer.observe(target));
        });
    </script>
</body>
</html>