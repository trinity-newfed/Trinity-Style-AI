//URL
document.addEventListener('DOMContentLoaded', function () {
    function ProfileClick() {
        if (!window.location.hash) return;

        const tabName = window.location.hash.replace("#", "").toLowerCase();
        const targetBtns = document.querySelectorAll("." + tabName + "Block");

        if (targetBtns.length > 0) {
            targetBtns.forEach(btn => {
                if (!btn.classList.contains('active')) {
                    btn.click();
                }
            });
        }
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
const cancelBtn = document.getElementById("cancelBtn");
const saveBtn = document.getElementById("saveBtn");

//Select order state
const select = document.getElementById("order-state-option");
select.addEventListener("click", function () {
    this.querySelector(".select-animate").classList.toggle("active");
    this.querySelector(".svg").classList.toggle("active");
    const currentState = this.querySelector("span").textContent;

    this.querySelector(".select-animate").querySelectorAll("span").forEach(span => {
        span.classList.remove("active");
        if (span.textContent == currentState) span.classList.add("active");
        span.addEventListener('click', function () {
            select.querySelector("span").textContent = this.textContent;
        });
    });

    const orderBlocks = document.querySelectorAll(".order-block");
    orderBlocks.forEach(block => {
        const state = select.querySelector("span").textContent.toLowerCase();
        const blockState = block.querySelector(".state").textContent.toLowerCase();
        if (blockState.includes(state) || state == "all") {
            block.style.display = "";
        } else {
            block.style.display = "none";
        }
    });
});

const Blocks = document.querySelectorAll(".order-block");
Blocks.forEach(blocks => {
    const blockStates = blocks.querySelector(".state").textContent.toLowerCase();
    if (blockStates == "success") {
        blocks.style.display = "";
    } else {
        blocks.style.display = "none";
    }
});

//Select order layout
const layout = document.getElementById("order-state-layout");
layout.addEventListener('click', function () {
    this.querySelector(".select-animate").classList.toggle("active");
    this.querySelector(".svg").classList.toggle("active");
    const currentState = this.querySelector("span").textContent;

    const div = this.querySelector(".select-animate");
    div.querySelectorAll("span").forEach(span => {

        if (span.textContent == currentState) span.classList.add("active");
        span.addEventListener('click', function () {
            div.querySelectorAll("span").forEach(s => s.classList.remove("active"));
            layout.querySelector("span").textContent = this.textContent;

            setTimeout(() => {
                if (layout.querySelector("span").textContent.includes("List")) {
                    Blocks.forEach(block => block.classList.add("list"));
                    document.getElementById("order-history").classList.add("list");
                }

                else {
                    Blocks.forEach(block => block.classList.remove("list"));
                    document.getElementById("order-history").classList.remove("list");
                    document.querySelector(".re-order").style.display = "";
                    document.getElementById("blockForm").style.display = "";
                }
            }, 100);
        });
    });
});

//Close Select state
document.addEventListener('click', function (e) {
    if (!select.contains(e.target)) {
        select.querySelector(".select-animate.select").classList.remove("active");
        select.querySelector(".svg.select").classList.remove("active");
    }
});

document.addEventListener('click', function (e) {
    if (!layout.contains(e.target)) {
        layout.querySelector(".select-animate.layout").classList.remove("active");
        layout.querySelector(".svg.layout").classList.remove("active");
    }
});

//Rebuy - list layout toggle
document.querySelectorAll(".reBuy-toggle").forEach(reBuy => {
    reBuy.addEventListener('click', function () {
        const form = this.closest(".order-block").querySelector("#blockForm");
        if (form.style.display === "none" || form.style.display === "") form.style.display = "flex";
        else form.style.display = "none";
    });
});


//Search Order
const toggleBtn = document.getElementById('toggle-search-btn');
const closeBtn = document.getElementById('close-search-btn');
const searchWrapper = document.getElementById('search-wrapper');
const searchInput = document.getElementById('mobile-search-input');

toggleBtn.addEventListener('click', () => {
    console.log("A")
    searchWrapper.classList.add('active');
});

closeBtn.addEventListener('click', () => {
    searchWrapper.classList.remove('active');
    searchInput.value = '';
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('mobile-search-input');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();

            const orderCards = document.querySelectorAll('.order-block');

            orderCards.forEach(card => {
                const orderCode = (card.getAttribute('data-code') || '');
                const productName = (card.getAttribute('data-name') || '');

                if (orderCode.includes(keyword) || productName.includes(keyword)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
});

//Menu close

const fastMenuContainer = document.getElementById("fast-menu-container");
const menuToggle = document.getElementById("menu-toggle");
const hamburger = document.querySelector(".hamburger");

document.addEventListener('click', function (e) {
    if (menuToggle.checked && !hamburger.contains(e.target) && menuToggle !== e.target && !fastMenuContainer.contains(e.target)) {
        menuToggle.checked = false;
    }
});

//Order & Profile toggle

document.querySelector(".orderBlock").classList.add("active");
let action = "";

function OrderProfileToggle() {
    if (action == "order") {
        document.getElementById("order-history").style.display = "grid";
        document.getElementById("profile").style.display = "none";
        document.getElementById("order-state-option").style.display = "";
        document.getElementById("order-state-layout").style.display = "";
        document.querySelector(".title p").textContent = "Your Orders";
        document.querySelectorAll("#text span").forEach(span => span.classList.remove("active"));
        document.querySelectorAll(".orderBlock").forEach(order => order.classList.add("active"));
        menuToggle.checked = false;
    } else {
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

document.querySelectorAll(".profileBlock").forEach(profile => {
    profile.addEventListener('click', function () {
        action = "profile";
        OrderProfileToggle(action);
        this.classList.add("active");
    })
});

document.querySelectorAll(".orderBlock").forEach(order => {
    order.addEventListener('click', function () {
        action = "order";
        OrderProfileToggle(action);
        this.classList.add("active");
    })
});

//Validation Hotline 
const hotline = document.getElementById('hotline');
if (hotline) {
    hotline.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 10);
        if (this.value.length > 0 && this.value[0] !== '0') this.value = '0' + this.value;
        this.parentElement.style.borderBottomColor = (this.value.length === 10) ? "#000" : "#e5e7eb";
    });
}


//Roadmap API
let coords = {
    from: [106.5775, 10.8908],
    to: null
};

const addressInput = document.getElementById("address");
const suggestBox = document.getElementById("toList");

async function searchAddress(input, listId) {
    if (input.value.length < 3) { suggestBox.classList.add('hidden'); return; }
    const res = await fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(input.value)}&limit=5`);
    const data = await res.json();
    suggestBox.innerHTML = "";
    suggestBox.classList.remove('hidden');

    data.features.forEach(place => {
        const name = place.properties.name || place.properties.city || place.properties.country;
        const div = document.createElement("div");
        div.className = "p-3 hover:bg-zinc-50 cursor-pointer border-b border-zinc-50 tracking-widest text-zinc-500 uppercase";
        div.innerText = name;
        div.onclick = () => {
            input.value = name;
            suggestBox.classList.add('hidden');
        };
        suggestBox.appendChild(div);
    });
}

//Close toList
modal.addEventListener('click', function (e) {
    if (!suggestBox.contains(e.target)) suggestBox.classList.add('hidden');
})

//Open Modal
const editBtn = document.querySelectorAll(".edit");

editBtn.forEach(btn => {
    btn.addEventListener('click', function () {
        modal.classList.add('active');
    });
});

//Close Modal
modal.addEventListener('click', function (e) {
    if (!conModal.contains(e.target)) modal.classList.remove('active');
});

closeModal.addEventListener('click', function () {
    modal.classList.remove('active');
});

cancelBtn.addEventListener('click', function () {
    modal.classList.remove('active');
});




//Form fetch
const selectGender = document.getElementById("selected-value");
const selectAddress = document.getElementById("address");
const selectImg = document.getElementById("uploadImg");


saveBtn.addEventListener('click', function (e) {
    e.preventDefault()

    const formData = new FormData();
    formData.append('user_sex', selectGender.textContent);
    formData.append('user_hotline', hotline.value);
    formData.append('user_address', selectAddress.value);

    if (selectImg.files.length > 0) {
        formData.append('img', selectImg.files[0]);
    }

    fetch('../Database/user_info_update.php', {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {

            if (data.status == "success") {
                setTimeout(() => {
                    window.location.hash = "Profile";
                    window.location.reload();
                }, 500);
            }
        })
        .catch(error => { console.error(error) })
});



