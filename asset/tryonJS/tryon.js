function changeProduct(id, color) {
    const productID = id;
    const productColor = color;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'tryon.php';

    const inputID = document.createElement('input');
    inputID.type = 'hidden';
    inputID.name = 'productID';
    inputID.value = productID;
    form.appendChild(inputID);

    const inputColor = document.createElement('input');
    inputColor.type = 'hidden';
    inputColor.name = 'productColor';
    inputColor.value = productColor;
    form.appendChild(inputColor);

    document.body.appendChild(form);
    form.submit();
}

//Change Product
const otherProduct = document.querySelectorAll(".otherProducts");

otherProduct.forEach(product => {
    product.addEventListener('click', function () {
        const id = this.dataset.id;
        const color = this.dataset.color;
        
        changeProduct(id, color);
    });
});


//Img Main
const img = document.getElementById("main-preview");

img.src = document.querySelector(".activeColor").dataset.path;

const variantBtn = document.querySelectorAll(".variantBtn");

variantBtn.forEach(btn =>{
    btn.addEventListener('click', function(){
        variantBtn.forEach(btn => {
            btn.classList.remove("activeColor");
            btn.classList.add("border-zinc-800");
        });
        const src = this.dataset.path;

        this.classList.add("activeColor");
        this.classList.remove("border-zinc-800");
        img.src = src;
    });
});