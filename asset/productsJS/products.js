        //Toast
        const toastNoti = document.querySelector(".toast");
        function Toast(){
            toastNoti.classList.add("active");
            setTimeout(() => {
                toastNoti.classList.remove("active");
            }, 5000);
        }
        
        //Head observe
        const headObserve = new IntersectionObserver(entries =>{
            entries.forEach(entry =>{
                if(entry.isIntersecting){
                    document.getElementById("menu").style.background = "transparent";
                    document.getElementById("menu").style.backdropFilter = "blur(0px)";
                    document.getElementById("menu").style.transition = ".3s all";
                    document.getElementById("menu").classList.add("head");
                    userWelcome ? userWelcome.forEach(user => user.style.color = "") : null;

                    const lines = document.querySelectorAll(".line");
                    lines.forEach(line => line.style.stroke = "white");

                    const icons = document.getElementById("menu").querySelectorAll(".icon path");
                    icons.forEach(icon => icon.style.fill = "white");

                    const spans = document.getElementById("menu").querySelectorAll("span");
                    spans.forEach(span => span.style.color = "white");

                }else{
                    document.getElementById("menu").classList.remove("head");

                    userWelcome ? userWelcome.forEach(user => user.style.color = "black") : null;

                    const lines = document.querySelectorAll(".line");
                    lines.forEach(line => line.style.stroke = "black");

                    const icons = document.getElementById("menu").querySelectorAll(".icon path");
                    icons.forEach(icon => icon.style.fill = "black");

                    const spans = document.getElementById("menu").querySelectorAll("span");
                    spans.forEach(span => span.style.color = "");

                    document.getElementById("menu").style.background = "";
                    document.getElementById("menu").style.backdropFilter = "blur(10px)";
                }
            });
        }, {
            threshold: 0.7
        });
        headObserve.observe(head);

        //Search bar
        const searchBar = document.getElementById("searchBar");
        const searchItems = document.getElementById("search-Items");
        const searchResult = document.getElementById("searchResult");
        const searchBtn = document.getElementById("searchBtn");

        searchBar.addEventListener('keyup', () => {
            const items = document.querySelectorAll(".item");
            const searchKey = searchBar.value.toLowerCase().trim();

            if(searchKey.length > 0){
                searchItems.classList.add("active");

            }else{

                searchItems.classList.remove("active");
                searchResult.textContent = "";
                return;
            }

            let hasResult = false;

            items.forEach(item => {
                const name = item.dataset.name.toLowerCase();
                if(name.includes(searchKey) || searchKey === "all"){
                    item.style.display = "";
                    hasResult = true;    

                }else{
                    item.style.display = "none";
                }
            });

            if(hasResult){
                searchBtn.style.display = "";
                if(searchKey.length >= 3) searchResult.textContent = "Result for: " + searchKey;

            }else{
                searchBtn.style.display = "none";
                if(searchKey.length >= 3) searchResult.textContent = "No result for: " + searchKey; 
                else searchResult.textContent = "";
            }
        });

        //Animate class add on Viewport
        document.addEventListener("DOMContentLoaded", function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if(entry.isIntersecting){
                        entry.target.classList.add("animate");
                        observer.unobserve(entry.target); 
                    }
                });
            }, {
                root: null,
                threshold: 0.15
            });

            const elementsToAnimate = document.querySelectorAll('.animate-on-scroll');
            elementsToAnimate.forEach(element => observer.observe(element));
        });

        //Card modal popup
        const products = document.querySelectorAll(".group.cursor-pointer");

        const conModal = document.querySelector(".modal-container");
        const modal = document.getElementById("product-modal");
        const modalImg = document.getElementById("modal-img");
        const modalName = document.getElementById("modal-name");
        const modalPrice = document.getElementById("modal-price");
        const modalColor = document.querySelector(".colors");
        
        let modalSize = "S";
        let modalProductId = "";
        let modalProductCategory = "";
        let modalProductColor = "";

        const modalAddCart = document.querySelector(".modal-add");
        const sizeAdd = document.querySelectorAll(".sizes label");

        products.forEach(product => {
            product.addEventListener('click', function(){

            //Size reset
            sizeAdd.forEach(size =>{
                size.classList.remove("active");
            });
            sizeAdd[0].classList.add("active");

            //Stock
            if(this.dataset.stock <= 0){
                modalAddCart.disabled = true;
                modalAddCart.style.background = "gray";
                modalAddCart.textContent = "OUT OF STOCK";
            }else{
                modalAddCart.disabled = false;
                modalAddCart.style.background = "";
                modalAddCart.textContent = "ADD TO CART";
            }

            //Modal info
            let modalId = "";
            const modalVariant = this.querySelectorAll(".variants");
            modalImg.src = this.dataset.img;
            modalId.value = this.dataset.id;
            modalName.textContent = this.dataset.name.toUpperCase();
            modalPrice.textContent = "$" + this.dataset.price;
            modalProductId = this.dataset.id;
            modalProductCategory = this.dataset.category;
            modalProductColor = this.dataset.color;
            modal.style.setProperty("display", "flex", "important");


            //Render label color
            let htmlModal = "";
            modalVariant.forEach((variant) =>{


                const isActive = variant.classList.contains("active");

                const activeClasses = isActive ? "color active border-black" : "color bg-white text-black hover:border-black";

                const inlineStyle = isActive ? "border: 1px solid black;" : "border: 1px solid black;";

                htmlModal += `
                    <label class="${activeClasses} color text-xs md:text-sm uppercase border-solid border-black/20 border p-1 md:p-2 text-center cursor-pointer transition-all duration-300 hover:border-black"
                           data-id="${variant.dataset.id}"
                           data-variant="${variant.dataset.variant}"
                           data-img="${variant.dataset.img}"
                           data-name="${variant.dataset.name}"
                           data-price="${variant.dataset.price}"
                           data-stock="${variant.dataset.stock}">${variant.dataset.variant}</label>
                `;
            });

            modalColor.innerHTML = htmlModal;


            //Detail btn
            document.querySelector(".modal-detail").addEventListener('click', function(){
                window.location.href = `detail.php?id=${modalProductId}&color=${modalProductColor}`
            });


            //Size select
            sizeAdd.forEach(label =>
                label.addEventListener('click', function(){
                    modalSize = label.textContent;
                    sizeAdd.forEach(label => label.classList.remove("active"));
                    this.classList.add("active");
                })
            );

              //Color select
              const colorAdd = document.querySelectorAll(".colors label");

                colorAdd.forEach(color => {
                    color.addEventListener('click', function(){
                        colorAdd.forEach(c => c.classList.remove("active"));
                        this.classList.add("active");
                    });
                });



                //Color button change
                const outerColorBtn = document.querySelectorAll(".colors label");

                outerColorBtn.forEach(Btn =>{
                    Btn.addEventListener('click', function(e){
                        e.stopPropagation();

                        //Stock
                        if(this.dataset.stock <= 0){
                            modalAddCart.disabled = true;
                            modalAddCart.style.background = "gray";
                            modalAddCart.textContent = "OUT OF STOCK";
                        }else{
                            modalAddCart.disabled = false;
                            modalAddCart.style.background = "";
                            modalAddCart.textContent = "ADD TO CART";
                        } 

                        const vImg = this.dataset.img;
                        const baseName = this.dataset.name;
                        const vColor = this.dataset.variant;

                        modalImg.src = vImg;
                        modalPrice.textContent = "$" + product.dataset.price;
                        modalProductId = product.dataset.id;
                        modalProductCategory = product.dataset.category;
                        modalProductColor = vColor;

                        modal.style.setProperty("display", "flex", "important");

                    });
                });
            });
        });        

        //Add cart
        let isAddingToCart = false;

        modalAddCart.addEventListener('click', function(e){
            e.preventDefault();

            fetch('../Database/add_item_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_category=${modalProductCategory}&product_color=${modalProductColor}&cart_size=${modalSize}&product_id=${parseInt(modalProductId)}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.status == "success"){
                    Toast();
                    modal.style.display = "none";
                }else console.warn('Server warning:', data.message);
            })
            .catch(error => {
                console.error('Error updating cart:', error);
            });
        });

        //Card modal close
        const closeBtn = document.querySelector(".close-modal");
        closeBtn.addEventListener('click', ()=>{
            modal.style.display = "none";
        });

        modal.addEventListener('click', function(e){
            if(!conModal.contains(e.target)) modal.style.display = "none";
        });


        //Button Next - Previous
        let currentIndex = 0;
        const steps = 5;
        const productScrolls = document.querySelectorAll(".product");

        function scrollProduct(dir){
            currentIndex = (currentIndex + dir * steps) % productScrolls.length;
            if(productScrolls.length > 6){
                productScrolls.forEach(product =>{
                    product.style.animation = "none";
                    product.style.opacity = "1";
                });

                const target = productScrolls[currentIndex];

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                    inline: "start"
                });
            }
        }

        document.querySelector(".next").addEventListener('click', ()=>scrollProduct(1));

        document.querySelector(".previous").addEventListener('click', ()=>scrollProduct(-1));

        


        //Fast menu 
        const fastMenuContainer = document.getElementById("fast-menu-container");
        const menuToggle = document.getElementById("menu-toggle");
        const hamburger = document.querySelector(".hamburger");

        document.addEventListener('click', function(e){
            if(menuToggle.checked && !hamburger.contains(e.target) && menuToggle !== e.target && !fastMenuContainer.contains(e.target)){
                menuToggle.checked = false;
            }
        });

        const menuTitles = document.querySelectorAll(".menu-title");
            menuTitles.forEach(title =>{
                title.addEventListener("click", ()=>{
                    const parent = title.parentElement;
                    parent.classList.toggle("active");
            });
        });
        const submenuItems = document.querySelectorAll(".submenu-item");
            submenuItems.forEach(item =>{
                item.addEventListener("click",(e)=>{
                    e.stopPropagation();
                    item.classList.toggle("active");
            });
        });

        const search = document.querySelector(".icon.search");
        const menuSearch = document.getElementById("menu-search");
        const searchContainer = document.getElementById("search-Container");

        search.addEventListener('click', ()=>{
            document.getElementById("menu").classList.toggle("active");

            userWelcome ? userWelcome.forEach(user => user.classList.toggle("active")) : null;

            const lines = document.querySelectorAll(".line");
            lines.forEach(line => line.classList.toggle("active"));

            const icons = document.getElementById("menu").querySelectorAll(".icon path");
            icons.forEach(icon => icon.classList.toggle("active"));

            const spans = document.getElementById("menu").querySelectorAll("span");
            spans.forEach(span => span.classList.toggle("active"));

            document.getElementById("menu-search").classList.toggle("active");
            document.getElementById("search-Container").classList.toggle("active");
        });

        document.addEventListener('click', function(e){
            if(!searchContainer.contains(e.target) && e.target !== search){
                document.getElementById("menu").classList.remove("active");

                userWelcome ? userWelcome.forEach(user => user.classList.remove("active")) : null;

                const lines = document.querySelectorAll(".line");
                lines.forEach(line => line.classList.remove("active"));

                const icons = document.getElementById("menu").querySelectorAll(".icon path");
                icons.forEach(icon => icon.classList.remove("active"));

                const spans = document.getElementById("menu").querySelectorAll("span");
                spans.forEach(span => span.classList.remove("active"));
                document.getElementById("menu-search").classList.remove("active");
                document.getElementById("search-Container").classList.remove("active");
            }
        });