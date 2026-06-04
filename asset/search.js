        //Fast Menu
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
        
        //Search Bar
        const searchBar = document.getElementById("searchBar");
        const searchItems = document.getElementById("search-Items");
        const searchResult = document.getElementById("searchResult");
        const searchBtn = document.getElementById("searchBtn");
        const itemsContainer = document.getElementById("items-Container");

        searchBar.addEventListener('keyup', () => {
            const items = document.querySelectorAll(".item");
            const searchKey = searchBar.value.toLowerCase().trim();

            //Reset
            if(searchKey.length === 0){
                searchItems.classList.remove("active");
                searchResult.textContent = "";
                itemsContainer.innerHTML = "";
                searchBtn.style.display = "none";
                return;
            }

            //Fetch
            fetch('../component/base.php?keyword=' + searchKey)
            .then(response => response.text())
            .then(htmlResult => {
                itemsContainer.innerHTML = htmlResult;
                const itemsFound = itemsContainer.querySelectorAll(".item").length;

                if(itemsFound > 0){
                    searchBtn.style.display = "";
                    if(searchKey.length >= 3) searchResult.textContent = "Result for: " + searchKey;

                }else{
                    searchBtn.style.display = "none";
                    if(searchKey.length >= 3) searchResult.textContent = "No result for: " + searchKey; 
                    else searchResult.textContent = "";
                }
            })

            if(searchKey.length > 0) searchItems.classList.add("active");
            else{
                searchItems.classList.remove("active");
                searchResult.textContent = "";
                return;
            }

            //Search name
            
        });

        searchBar.addEventListener('keydown', function(event){
            if(event.key == 'Enter'){
                if(searchBar.value.length >= 3){
                    window.location.href = `../Pages/search.php?content=${searchBar.value}`;
                }
            }
        });