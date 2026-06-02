        //URL
        document.addEventListener('DOMContentLoaded', function(){
            function ProfileClick(){
                const tabName = window.location.hash.replace("#", "").toLowerCase();
                const targetBtn = document.querySelectorAll("." + tabName + "Block");

                if(targetBtn) targetBtn.forEach(btn => btn.click());
            }

            ProfileClick();
            window.addEventListener("hashchange", ProfileClick);
        });

        const imgPopup = document.querySelectorAll(".line3 img");
        const modal = document.getElementById("product-modal");
        const conModal = document.querySelector(".modal-container");
        const closeModal = document.getElementById("closeModal");
        const imgEdit = document.querySelector(".user-avatar");
        const edit = document.getElementById("edit");

        //Select order state

        const select = document.getElementById("order-state-option");
        select.addEventListener("click", function (){
            this.querySelector(".select-animate").classList.toggle("active");
            this.querySelector(".svg").classList.toggle("active");
            const currentState = this.querySelector("span").textContent;

            this.querySelector(".select-animate").querySelectorAll("span").forEach(span =>{
                span.classList.remove("active");
                if(span.textContent == currentState) span.classList.add("active");
                span.addEventListener('click', function(){
                    select.querySelector("span").textContent = this.textContent;
                });
            });

            const orderBlocks = document.querySelectorAll(".order-block");
            orderBlocks.forEach(block =>{
                const state = select.querySelector("span").textContent.toLowerCase();
                const blockState = block.querySelector(".state").textContent.toLowerCase();
                if(blockState.includes(state) || state == "all"){
                    block.style.display = "";
                }else{
                    block.style.display = "none";
                }
            });
        });

        const Blocks = document.querySelectorAll(".order-block");
        Blocks.forEach(blocks =>{
            const blockStates = blocks.querySelector(".state").textContent.toLowerCase();
            if(blockStates == "success"){
                blocks.style.display = "";
            }else{
                blocks.style.display = "none";
            }
        });

        //Select order layout
        const layout = document.getElementById("order-state-layout");
        layout.addEventListener('click', function(){
            this.querySelector(".select-animate").classList.toggle("active");
            this.querySelector(".svg").classList.toggle("active");
            const currentState = this.querySelector("span").textContent;
            
            const div = this.querySelector(".select-animate");
            div.querySelectorAll("span").forEach(span =>{
                
                if(span.textContent == currentState) span.classList.add("active");
                span.addEventListener('click', function(){
                    div.querySelectorAll("span").forEach(s => s.classList.remove("active"));
                    layout.querySelector("span").textContent = this.textContent;
                    
                    setTimeout(() => {
                        if(layout.querySelector("span").textContent.includes("List")){
                            Blocks.forEach(block => block.classList.add("list"));
                            document.getElementById("order-history").classList.add("list");
                        }
                            
                        else{
                            Blocks.forEach(block => block.classList.remove("list"));
                            document.getElementById("order-history").classList.remove("list");
                        }
                    }, 100);
                });
            });
        });
 
        //Close Select state
        document.addEventListener('click', function(e){
            if(!select.contains(e.target)){
                select.querySelector(".select-animate.select").classList.remove("active");
                select.querySelector(".svg.select").classList.remove("active");
            }
        });

        document.addEventListener('click', function(e){
            if(!layout.contains(e.target)){
                layout.querySelector(".select-animate.layout").classList.remove("active");
                layout.querySelector(".svg.layout").classList.remove("active");
            }
        });

        //Rebuy - list layout toggle
        document.querySelectorAll(".reBuy-toggle").forEach(reBuy => {
            reBuy.addEventListener('click', function() {
                const form = this.closest(".order-block").querySelector("#blockForm");
                if(form.style.display === "none" || form.style.display === "") form.style.display = "flex";
                else form.style.display = "none";
            });
        });

        //Menu close

        const fastMenuContainer = document.getElementById("fast-menu-container");
        const menuToggle = document.getElementById("menu-toggle");
        const hamburger = document.querySelector(".hamburger");

        document.addEventListener('click', function(e){
            if(menuToggle.checked && !hamburger.contains(e.target) && menuToggle !== e.target && !fastMenuContainer.contains(e.target)){
                menuToggle.checked = false;
            }
        });

        //Order & Profile toggle

        document.querySelector(".orderBlock").classList.add("active");
        let action = "";

        function OrderProfileToggle(){
            if(action == "order"){
                document.getElementById("order-history").style.display = "grid";
                document.getElementById("profile").style.display = "none";
                document.getElementById("order-state-option").style.display = "";
                document.getElementById("order-state-layout").style.display = "";
                document.querySelector(".title p").textContent = "Your Orders";
                document.querySelectorAll("#text span").forEach(span => span.classList.remove("active"));
                document.querySelectorAll(".orderBlock").forEach(order => order.classList.add("active"));
                menuToggle.checked = false;
            }else{
                document.getElementById("order-history").style.display = "none";
                document.getElementById("profile").style.display = "flex";
                document.querySelector(".title p").textContent = "Profile";
                document.getElementById("order-state-option").style.display = "none";
                document.getElementById("order-state-layout").style.display = "none";
                document.querySelectorAll("#text span").forEach(span => span.classList.remove("active"));
                document.querySelectorAll(".profileBlock").forEach(profile => profile.classList.add("active"));
                menuToggle.checked = false;
            }
        }

        document.querySelectorAll(".profileBlock").forEach(profile =>{
            profile.addEventListener('click', function(){
                action = "profile";
                OrderProfileToggle(action);
                this.classList.add("active");
            })
        });

        document.querySelectorAll(".orderBlock").forEach(order =>{
            order.addEventListener('click', function(){
                action = "order";
                OrderProfileToggle(action);
                this.classList.add("active");
            })
        });

        let coords = {
            from: [106.5775, 10.8908],
            to: null
        };

        async function search(input, listId){
            const q = input.value;
            if(q.length < 3)return;

            const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(q)}&limit=5`);
            const data = await res.json();

            const list = document.getElementById(listId);
            list.innerHTML = "";

            data.features.forEach(place => {
                const name = place.properties.name || place.properties.city || place.properties.country;
                const div = document.createElement("div");
                div.className = "item";
                div.innerText = name;

                div.onclick = () => {
                    input.value = name;
                    list.innerHTML = "";

                    if(listId === "fromList") coords.from = place.geometry.coordinates;
                    else coords.to = place.geometry.coordinates;
      
                }

                list.appendChild(div);
            });
        }