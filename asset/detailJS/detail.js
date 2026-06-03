
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
        
        //Img change
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