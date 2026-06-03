const userWelcome = document.querySelectorAll(".menu-Username");

if(userWelcome.length > 0){
    let email = "";
    for (let user of userWelcome){
        if (user.dataset.name) {
            email = user.dataset.name;
            break;
        }
    }
    let username1 = email.split("@")[0] || "";
    let displayName = username1.length > 6 
        ? username1.substring(0, 6) + "..." 
        : username1;
    userWelcome.forEach(user =>{
        user.textContent = "Hi, " + displayName;
        
        if(email){
            user.setAttribute('data-name', email);
        }
    });
}


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