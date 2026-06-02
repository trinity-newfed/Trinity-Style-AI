
        let deleteTimeout = null;
        let currentDeleteId = null;
        let currentCartItemRow = null;

        const toastNoti = document.querySelector(".toast");
        const toastUndo = toastNoti.querySelector(".underline");

        document.querySelectorAll(".deleteItem").forEach(del => {
            del.addEventListener('click', function(e){
                e.preventDefault();
        
                if(deleteTimeout){
                    clearTimeout(deleteTimeout);
                    executeDelete(currentDeleteId, false);
                }

                currentCartItemRow = this.closest(".cartItem");
                currentDeleteId = currentCartItemRow.dataset.id;

                currentCartItemRow.style.display = "none";

                triggerToast();
            });
        });


        function triggerToast(){
            toastNoti.classList.add("active");

            deleteTimeout = setTimeout(() =>{
                toastNoti.classList.remove("active");
                executeDelete(currentDeleteId, true);
            }, 5000);
        }

        toastUndo.addEventListener('click', function(e){
            e.preventDefault();
    
            if(deleteTimeout){
                clearTimeout(deleteTimeout);
                deleteTimeout = null;
            }

            toastNoti.classList.remove("active");


            if(currentCartItemRow){
                currentCartItemRow.style.display = "flex";
            }
        });

        function executeDelete(cartId, shouldReload){
            fetch('../Database/delete_item_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cartId=${cartId}`
            })
            .then(() => {
                if(shouldReload) location.reload();
            })
            .catch(error => {
                console.error("Failed to delete", error);
                if(currentCartItemRow) currentCartItemRow.style.display = "flex";
            });
        }
        
        //Price Display
        const finalTotal = document.getElementById("final-total");
        const priceDisplay = document.querySelectorAll(".items-price-container");

        if(priceDisplay){
            priceDisplay.forEach(pDisplay =>{
                pDisplay.textContent = "$" + parseFloat(pDisplay.dataset.price);
            });
        }

        //COLOR & SIZE SELECT
        const colorSelect = document.querySelectorAll('.cartColor');
        const sizeSelect = document.querySelectorAll('.cartSize');
        

        function cartUpdate(item){
            const id = item.querySelector('.cartColor').dataset.id;
            const cartColor = item.querySelector('.cartColor').value.toLowerCase();
            const cartSize = item.querySelector('.cartSize').value;
            const colorIndicator = item.querySelector('.color');

            if(colorIndicator){
                colorIndicator.style.backgroundColor = cartColor; 
            }

            fetch('../Database/cart_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `cart_id=${id}&cart_color=${cartColor}&cart_size=${cartSize}&action=update`
            })
            .catch(error => {
                console.error('Error updating cart:', error)
            })

        }
        
        const items = document.querySelectorAll(".cartItem");
        items.forEach(item =>{
            const qty = parseInt(item.querySelector(".item-quantity").textContent);
            const stock = parseInt(item.querySelector(".cartColor").querySelector(".active").dataset.stock);
            const id = item.dataset.id;
            if(qty > stock){
                item.querySelector(".item-quantity").textContent = stock;

                fetch('../Database/cart_update.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `cart_id=${id}&action=reset&quantity=${stock}`
                })
                .catch(error => {
                    console.error('Error updating cart:', error)
                })
            }
        });

        colorSelect.forEach(color =>{
            const item = color.closest(".cartItem");
            const img = color.closest(".cartItem").querySelector(".item-img-container img").src = color.options[color.selectedIndex].dataset.img;

            color.addEventListener('change', function(){
                colorSelect.forEach(color => color.classList.remove("active"));

                this.options[this.selectedIndex].classList.add("active");
                let stock = this.querySelector("option:checked").dataset.stock;
                let quantities = item.querySelector(".item-quantity");
                
                setTimeout(() => {
                    location.reload();
                }, 100);

                cartUpdate(item);
            });
        });

        sizeSelect.forEach(size =>{
            size.addEventListener('change', function(){
                const item = this.closest(".cartItem");
                cartUpdate(item);
            });
        });


        //QUANTITY BTN
        const operationBtn = document.querySelectorAll(".operation-button");
        operationBtn.forEach(btn =>{
            btn.addEventListener('click', function(e){
            
                const id = this.dataset.id;
                const action = this.dataset.action;
                const item = this.closest(".cartItem");
                const selectOperation = document.querySelector(".cartColor");

                let stock = item.querySelector(".cartColor").querySelector(".active").dataset.stock;
                let quantity = parseInt(item.querySelector(".item-quantity").textContent);

                console.log(stock)

                if(action == "plus" && quantity < stock){
                    quantity = quantity + 1;
                    fetch('../Database/cart_update.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `cart_id=${id}&action=${action}`
                    })
                    .then(() => {
                        btn.disabled = false;
                        calculateFinalTotal();
                    }); 
                } 

                else if(action == "minus" && quantity > 1){
                    quantity = quantity - 1;
                    fetch('../Database/cart_update.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `cart_id=${id}&action=${action}`
                    })
                    .then(() => {
                        btn.disabled = false;
                        calculateFinalTotal();
                    }); 
                } 
    
                if(quantity > stock) quantity = stock;  

                item.querySelector(".item-quantity").textContent = quantity;                        
            });
        });

        //CHECKOUT BUTTON
        document.querySelector('#order-btn').addEventListener('click', function(){
            const ids = [];
            const sizes = [];
            const colors = [];

            document.querySelectorAll('.cartItem').forEach(item => {
                const id = item.dataset.id;
        
                const active = parseInt(item.dataset.active, 10) || 0;
                const stock = parseInt(item.querySelector(".cartColor .active").dataset.stock, 10) || 0;
        
                const size = item.dataset.size || 'L'; 
                const color = item.dataset.color || 'Black';

                if(active === 1 && stock > 0){
                    ids.push(id);
                    sizes.push(size);
                    colors.push(color);
                }
            });

            if(ids.length == 0){
                console.log(ids)
                return;
            }

            const voucherId = document.querySelector('#voucher-select')?.value || null;

            fetch('../Database/checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    cart_ids: ids,
                    cart_size: sizes,
                    cart_color: colors,
                    id: voucherId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success'){
                    window.location.href = data.redirect; 
                }else{
                    alert(data.message);
                }
            })
            .catch(error => console.error("Lỗi kết nối:", error));
        });

        const dropDown = document.getElementById("main-voucher");
        if(dropDown){
            dropDown.addEventListener('click', ()=>{
                document.querySelector(".voucher-list").style.display = "block";
            });
            document.addEventListener('click', function(e){
                if(e.target !== dropDown) document.querySelector(".voucher-list").style.display = "none";
            });
        }


        //MAP API
        let currentKm = 0;
        let coords = {
            from: [106.5775, 10.8908],
            to: null
        };

        window.onload = async function() {
            const toName = "<?=$address?>";
            const coordsTo = await getCoordsFromName(toName);
            coords.to = coordsTo;
            document.getElementById("to").value = toName;
            if(coordsTo){
                coords.to = coordsTo;
                const km = await calc();
                const fee = calculateShippingFee(km);
            }
        };

        async function calc(){
            const url = `https://router.project-osrm.org/route/v1/driving/${coords.from[0]},${coords.from[1]};${coords.to[0]},${coords.to[1]}?overview=false`;
            const res = await fetch(url);
            const data = await res.json();
            const km = (data.routes[0].distance / 1000);
            return km;
        }

        async function getCoordsFromName(name){
            const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(name)}&limit=1`);
            const data = await res.json();
            if(data.features.length > 0){
                return data.features[0].geometry.coordinates;
            }
            return null;
        }


        

    //DELIVERY CALCULATE
    function calculateShippingFee(km){
        if(isNaN(km) || km <= 0) return 0;
        currentKm = km; 
        if(km < 20) return 2;
        else if(km < 100) return 5;
        else if(km < 1000) return 15;
        else return 25;
    }

    window.onload = async function(){
        const toName = "<?=$address?>";
        const coordsTo = await getCoordsFromName(toName);
        if(coordsTo){
            coords.to = coordsTo;
            const km = await calc();
            const fee = calculateShippingFee(km);
            document.getElementById("deli-fee").textContent = fee.toLocaleString() + "$";
        }
        await calculateFinalTotal();
    };
    


//CALCULATE TOTAL
function calculateFinalTotal(){
    const selectedVoucher = document.querySelector("#voucher-select option:checked");
    const finalTotalDisplay = document.getElementById("final-total");
    const deliFeeDisplay = document.getElementById("deli-fee");
    const mainVoucher = document.getElementById("main-voucher");

    let total = 0;
    const items = document.querySelectorAll(".cartItem");
    

    //FOREACH ITEM
    items.forEach(item =>{
        const itemsTotalSpan = item.querySelector(".items-total-container");
        const price = parseFloat(item.querySelector(".items-price-container").textContent.replace("$", ""));
        const quantity = parseInt(item.querySelector(".item-quantity").textContent);
        
        const itemTotal = price * quantity;
        if (itemsTotalSpan) itemsTotalSpan.textContent = "Item total: $" + itemTotal;
            total += itemTotal;

        if(item.dataset.stock <= 0){
            item.style.opacity = "0.7";
            item.querySelector(".notice").classList.remove("hidden");
            item.querySelector(".notice").textContent = "OUT OF STOCK";
        }
        if(item.dataset.active == 0){
            item.style.opacity = "0.7";
            item.querySelector(".notice").classList.remove("hidden");
            item.querySelector(".notice").textContent = "TEMPORARILY UNAVAILABLE";
        }
    });

    //FREESHIP THRESHOLD
    const FREE_SHIP_THRESHOLD = 700;
    const isAutoFreeShip = total >= FREE_SHIP_THRESHOLD;
    let totalDiscount = 0;
    let shipDiscount = 0;
    let currentShippingFee = (typeof calculateShippingFee === 'function') ? calculateShippingFee(currentKm) : 0;

    //VOUCHER SELECT
    const vouchers = document.querySelectorAll(".voucher-list .voucher");
    vouchers.forEach(voucher => {
        const condition = parseFloat(voucher.dataset.condition) || 0;
        const isShipVoucher = parseInt(voucher.dataset.ship) === 1;


        if(total < condition || (isShipVoucher && isAutoFreeShip)){
            voucher.classList.add("disabled");
            let disabledVouchers = document.querySelectorAll(".voucher.disabled");
            disabledVouchers.forEach(disabledVoucher =>{
                const text = disabledVoucher.textContent;
                if(mainVoucher.textContent == text) mainVoucher.textContent = "No Selection";
            })
        }else{
            voucher.classList.remove("disabled");
        }
    });

    const activeVoucher = document.querySelector(".voucher.active");

    //CHECK VOUCHER
    if(activeVoucher && !activeVoucher.disabled && activeVoucher.value !== "0"){
        const val = parseFloat(activeVoucher.dataset.discount) || 0;
        const isShipVoucher = parseInt(activeVoucher.dataset.ship) === 1;
        if(isShipVoucher){
            shipDiscount = val; 
            totalDiscount = 0; 
        }else{
            const maxLimit = parseFloat(activeVoucher.dataset.max) || Infinity;
            totalDiscount = Math.min(total * (val / 100), maxLimit);
            shipDiscount = 0;
        }
    }

    let finalShippingFee = Math.max(0, currentShippingFee - shipDiscount);
    if(total >= FREE_SHIP_THRESHOLD){
        finalShippingFee = 0;
    }
    const finalTotal = Math.max(0, total - totalDiscount + finalShippingFee);
    
    //DISPLAY FEE
    if(finalTotalDisplay){
        finalTotalDisplay.textContent = "$" + finalTotal;
    }
    if(deliFeeDisplay){
        deliFeeDisplay.textContent = finalShippingFee === 0 ? "$0" : "$" + finalShippingFee.toLocaleString();
    }
}
const vouchers = document.querySelectorAll(".voucher-list .voucher");

vouchers.forEach(voucher => {
    voucher.addEventListener('click', ()=>{
        if(voucher.classList.contains("disabled")) return;
        vouchers.forEach(v => v.classList.remove("active"));
        voucher.classList.add("active");
        const activeVoucher = document.querySelector(".voucher.active");
        const input = document.getElementById("id");
        if(input){
            input.value = activeVoucher ? activeVoucher.dataset.id : "";
        }
        document.querySelector(".voucher-list").style.display = "none";
        const mainVoucher = document.getElementById("main-voucher");
        if(mainVoucher){
            mainVoucher.textContent = voucher.textContent;
        }
        calculateFinalTotal();
    });
});        
            
        //Menu toggle

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

        //Search bar

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
