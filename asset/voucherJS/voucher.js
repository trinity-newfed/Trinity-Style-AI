    const cards = document.querySelectorAll(".voucher-list");
    const buttons = document.querySelectorAll(".active");

    document.getElementById("all").style.display = "";
    const active = document.querySelector(".voucher-filter");
    const activate = active.querySelectorAll("button");

    activate.forEach(act => act.classList.remove("active"));
    active.querySelector("button").classList.add("active");
    buttons.forEach(button =>{
        const type = button.textContent.toLowerCase();
        button.addEventListener('click', ()=>{
            buttons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            cards.forEach(card => {
                const cardType = card.id ? card.id.toLowerCase() : "all";
                if(cardType === type){
                    card.style.display = "";
                }else{
                    card.style.display = "none";
                }
            });
        });
    });