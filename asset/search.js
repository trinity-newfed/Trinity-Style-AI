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

        searchBar.addEventListener('keydown', function(event){
            if(event.key == 'Enter'){
                if(searchBar.value.length >= 3){
                    window.location.href = `../Pages/search.php?content=${searchBar.value}`;
                }
            }
        });