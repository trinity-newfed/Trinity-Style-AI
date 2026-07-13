<section id="menu" class="head mix-blend-difference">
    <input type="checkbox" id="menu-toggle" hidden>
    <label class="hamburger" for="menu-toggle">
        <svg viewBox="0 0 32 32">
            <path class="line line-top-bottom"
                d="M27 10 13 10C10.8 10 9 8.2 9 6 9 3.5 10.8 2 13 2 15.2 2 17 3.8 17 6L17 26C17 28.2 18.8 30 21 30 23.2 30 25 28.2 25 26 25 23.8 23.2 22 21 22L7 22">
            </path>
            <path class="line" d="M7 16 27 16"></path>
        </svg>
    </label>

    <div id="text-menu">

        <div id="text"
            class="hidden md:flex space-x-12 text-[10px] tracking-[0.25em] pointer-events-auto uppercase font-light">
            <a href="../Pages/home.php" class="hover:opacity-40 transition-opacity">Home</a>
            <a href="products.php?#product-section" class="hover:opacity-40 transition-opacity">Shop</a>
            <a href="search.php?content=collections" class="hover:opacity-40 transition-opacity">Collection</a>
            <a href="contact.php" class="hover:opacity-40 transition-opacity">Contact</a>
        </div>

        <div id="logo" onclick="window.location.href='../Pages/'">TRINITY</div>
    </div>

    <div id="utility-menu">
        <div class="relative">
            <svg class="icon cart" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                width="21px" onclick="window.location.href='cart.php'">
                <path
                    d="M200-80q-33 0-56.5-23.5T120-160v-480q0-33 23.5-56.5T200-720h80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720h80q33 0 56.5 23.5T840-640v480q0 33-23.5 56.5T760-80H200Zm0-80h560v-480H200v480Zm421.5-298.5Q680-517 680-600h-80q0 50-35 85t-85 35q-50 0-85-35t-35-85h-80q0 83 58.5 141.5T480-400q83 0 141.5-58.5ZM360-720h240q0-50-35-85t-85-35q-50 0-85 35t-35 85ZM200-160v-480 480Z" />
            </svg>
            <span
                class="absolute top-[-5px] right-[-5px] bg-[transparent] text-[white] rounded-full w-[14px] h-[14px] text-[7px] flex items-center justify-center"><?= $noti ?></span>
        </div>

        <svg class="icon search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
                d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376C296.3 401.1 253.9 416 208 416 93.1 416 0 322.9 0 208S93.1 0 208 0 416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
        </svg>
        <?php require "../component/menu.php" ?>
    </div>

    <div id="fast-menu">
        <div id="fast-menu-container">
            <div class="menu-item">
                <div class="menu-title"><span>TRINITY</span></div>

                <div class="submenu">

                    <div class="submenu-item">T-shirt
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Basic T-shirt'">Basic
                        </div>
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Oversize T-shirt'">
                            Oversize</div>
                    </div>

                    <div class="submenu-item">Polo shirt
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Basic Polo'">Basic</div>
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Logo Polo'">Logo</div>
                    </div>

                    <div class="submenu-item">Hoodie
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Hoodie'">Signature</div>
                    </div>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-title"><span>TRINITY LADIES</span></div>

                <div class="submenu">

                    <div class="submenu-item">Blouse
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Classic Blouse'">Classic
                        </div>
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Wrap Blouse'">Warp</div>
                    </div>

                    <div class="submenu-item">Crop top
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Basic Crop Top'">Basic
                        </div>
                        <div class="sub-sub" onclick="window.location.href='search.php?content=Tank Crop Top'">Tank
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="window.location.href='products.php'"><span>SHOP</span></div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="window.location.href='voucher.php'"><span>GIFT VOUNCHER</span></div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="window.location.href='userTier.php'"><span>TRINITY TIER</span></div>
            </div>

            <div class="menu-item">
                <div class="menu-title" onclick="window.location.href='about.php'"><span>ABOUT</span></div>
            </div>

            <?php require "../component/menu2.php" ?>
        </div>
    </div>



</section>

<div id="menu-search">
    <div id="search-Container">
        <span>
            <svg class="icon active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                <path
                    d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376C296.3 401.1 253.9 416 208 416 93.1 416 0 322.9 0 208S93.1 0 208 0 416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" />
            </svg>
        </span>

        <input type="text" id="searchBar" placeholder="Search..." />

    </div>


    <div id="search-Items">
        <p id="searchResult"></p>
        <div id="items-Container">
            <?php require "../component/base.php" ?>
        </div>

        <button id="searchBtn" onclick="window.location.href='products.php'">
            <p>View All Products</p>
        </button>
    </div>
</div>

