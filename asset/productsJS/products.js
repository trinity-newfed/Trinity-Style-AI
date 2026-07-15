        //Toast
        const toastNoti = document.querySelector(".toast");
        function Toast(){
            toastNoti.classList.add("active");
            setTimeout(() => {
                toastNoti.classList.remove("active");
            }, 5000);
        }

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
