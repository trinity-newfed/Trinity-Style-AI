
        const items = document.querySelectorAll(".items");
        const sizeAdd = document.querySelectorAll(".size label");
        const bigImg = document.getElementById("bigImg");
        const smallImg = document.querySelectorAll(".smallImg");
        const addCart = document.querySelector(".add-cart"); 
        const mainColor = document.getElementById("mainColor").dataset.color.toLowerCase();
        const colorAdd = document.querySelectorAll(".colors label"); 

        //Color Active
        colorAdd.forEach(color => {
          if(color.dataset.color.toLowerCase() == mainColor) color.classList.add("active")
        })


        //Toast
        const toastNoti = document.querySelector(".toast");
        function Toast(){
            toastNoti.classList.add("active");
            setTimeout(() => {
                toastNoti.classList.remove("active");
            }, 5000);
        }

        //Quantity Button
        const decreaseBtn = document.getElementById("decrease-qty");
        const increaseBtn = document.getElementById("increase-qty");
        const qtyInput = document.getElementById('quantity-input');
        
        function qty(dir){
          let stock = document.querySelector(".color.active").dataset.stock;
          let currentVal = parseInt(qtyInput.value) || 1;
          qtyInput.value == stock && dir == 1 ? dir = 0 : dir = dir;
          if(currentVal + dir > 0 && currentVal + dir <= 99) qtyInput.value = currentVal + dir;
          if(qtyInput.value > stock) qtyInput.value = stock;
        }

        decreaseBtn.addEventListener('click', ()=>qty(-1));
        increaseBtn.addEventListener('click', ()=>qty(1));

        qtyInput.addEventListener('change', () => {
          let currentVal = parseInt(qtyInput.value);
          let stock = parseInt(document.querySelector(".color.active").dataset.stock) || 99;

          if(isNaN(currentVal) || currentVal < 1) qtyInput.value = 1;
          else if(currentVal > stock) qtyInput.value = stock;
        });

        //Size Select
        let modalSize = "S";
        sizeAdd.forEach(label =>
          label.addEventListener('click', function(){
            modalSize = label.textContent;
            sizeAdd.forEach(label => label.classList.remove("active"));
            this.classList.add("active");
          })
        );

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

        smallImg.forEach(img =>{
            img.addEventListener('click', ()=>{
                let temp = bigImg.src;
                bigImg.src = img.src;
                img.src = temp;
            });
        });

        //Color Select
        colorAdd.forEach(color => {
          color.addEventListener('click', function(){
            colorAdd.forEach(c => c.classList.remove("active"));
            this.classList.add("active");
          });
        });

        const outerColorBtn = document.querySelectorAll(".color");
        const price = document.querySelector(".price");
        const category = document.querySelector("#mainCategory");
        let img1 = document.querySelector(".smallImg.img-1");
        let img2 = document.querySelector(".smallImg.img-2");

        let colors = mainColor;

        outerColorBtn.forEach(Btn =>{
          Btn.addEventListener('click', function(e){
            e.stopPropagation();

            //Quantity Stock Reset
            qty(0);
            

            //Stock
            if(this.dataset.stock <= 0){
              addCart.disabled = true;
              addCart.style.background = "gray";
              addCart.textContent = "OUT OF STOCK";
            }else{
              addCart.disabled = false;
              addCart.style.background = "";
              addCart.textContent = "ADD TO CART";
            } 

              bigImg.src = this.dataset.img;
              document.querySelector(".price").textContent = "$" + this.dataset.price;
              colors = this.dataset.color;

              img1.src = this.dataset.img1;
              img2.src = this.dataset.img2;
          });
        });

        //Add cart
        let isAddingToCart = false;
        const mainId = document.getElementById("mainId").dataset.id;
        const mainCategory = document.getElementById("mainCategory").dataset.category;

        addCart.addEventListener('click', function(e){
            e.preventDefault();

            fetch('../Database/add_item_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `product_category=${mainCategory}&product_color=${colors}&cart_size=${modalSize}&product_id=${parseInt(mainId)}&quantity=${parseInt(qtyInput.value)}`
            })
            .then(response => response.json())
            .then(data => {
                if(data.status == "success") Toast();
                else console.warn('Server warning:', data.message);
            })
            .catch(error => {
                console.error('Error updating cart:', error);
            });
        });

        //Button Next - Previous
        let currentIndex = 0;
        let steps = 5;
        const productScrolls = document.querySelectorAll(".classic");

        function scrollProduct(dir){
            window.innerWidth < 768 ? steps = 2 : steps = 5;
            currentIndex = (currentIndex + dir * steps) % productScrolls.length;
            if(productScrolls.length > 6){
                productScrolls.forEach(product =>{
                    product.style.animation = "none";
                    product.style.opacity = "1";
                });

                const target = productScrolls[currentIndex];

                if(window.innerWidth < 768)

                target.scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                    inline: "center"
                });

                else 
                
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "nearest",
                    inline: "start"
                });
            }
        }

        document.querySelector(".next").addEventListener('click', ()=>scrollProduct(1));
        document.querySelector(".previous").addEventListener('click', ()=>scrollProduct(-1));