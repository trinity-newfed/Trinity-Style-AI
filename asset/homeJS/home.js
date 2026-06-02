
        const head = document.getElementById("head");  
        function scrollToNext(button){

        const currentSection = button.parentElement; 
        const nextSection = currentSection.nextElementSibling; 

        if(nextSection){
            nextSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
        }

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

        let num = 0;
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
            threshold: 1
        });

        headObserve.observe(head);



        //Menu Toggle

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


