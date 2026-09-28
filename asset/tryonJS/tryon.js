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
const subImg = document.getElementById("sub-preview");

img.src = document.querySelector(".activeColor").dataset.path;

//Upload Img
let subImgStatus = false;
const uploadInput = document.getElementById('uploadInput');

function handleImageUpload(inputSelector, imgSelector) {
    const fileInput = document.querySelector(inputSelector);
    const imgElement = document.querySelector(imgSelector);

    if (!fileInput || !imgElement) {
        console.warn("No Input File Found.");
        return;
    }

    fileInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (!file) return;

        if (!file.type.startsWith('image/')) {
            alert("Only Accept (.jpg, .png, .webp,...)");
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            imgElement.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
}

if (uploadInput && img) {
    uploadInput.addEventListener('change', function (event) {
        const file = event.target.files[0];

        if (file && file.type.startsWith('image/')) {
            if(img.src !== null && !subImgStatus) subImg.src = img.src;
            subImg.classList.remove("opacity-0");
            img.src = URL.createObjectURL(file);

            document.getElementById("slider-line").classList.remove("right-0");
            document.getElementById("slider-line").classList.add("left-0");

            subImgStatus = true;
        }
    });
}

//Variant Img Update
const variantBtn = document.querySelectorAll(".variantBtn");

if (variantBtn) {

    variantBtn.forEach(btn => {
        btn.addEventListener('click', function () {
            variantBtn.forEach(btn => {
                btn.classList.remove("activeColor");
                btn.classList.add("border-zinc-800");
            });
            const src = this.dataset.path;

            this.classList.add("activeColor");
            this.classList.remove("border-zinc-800");

            if(!subImgStatus) img.src = src;
            subImg.src = src;
        });
    });
}

//Slider
const slider = document.getElementById('slider');
const mainPreview = document.getElementById('main-preview');
const sliderLine = document.getElementById('slider-line');

if(uploadInput) {
    slider.addEventListener('input', (e) => {
        const value = e.target.value;
    
        mainPreview.style.clipPath = `inset(0 ${100 - value}% 0 0)`;
    
        sliderLine.style.left = `${value}%`;
    });
}

//Try on
const tryonBtn = document.querySelector(".tryonBtn");
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

const progressContainer = document.getElementById("progressContainer");
const circle = document.getElementById('progress-circle');
const text = document.getElementById('progress-text');
const CIRCUMFERENCE = 125.66;

function resetProgress() {
    img.classList.replace("blur-[0]", "blur-[5px]");
    text.innerText = "0%";
    circle.style.strokeDashoffset = 360;
}

function setProgress(percent) {  
  const clampedPercent = Math.min(100, Math.max(0, percent));
  
  const offset = CIRCUMFERENCE - (clampedPercent / 100) * CIRCUMFERENCE;

  circle.style.strokeDashoffset = offset;
  text.innerText = `${Math.round(clampedPercent)}%`;
}

function listenTaskProgress(taskId, onComplete) {
    const mainPreview = document.getElementById('main-preview');
    const tryonBtn = document.getElementById('tryonBtn');

    const intervalId = setInterval(async () => {
        try {
            const response = await fetch(`../network/check_progress.php?task_id=${taskId}`);
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || "Cannot fetch task status.");
            }

            const task = result.data || result; 

            if (!task || !task.status) {
                throw new Error("Invalid data format received from server.");
            }

            if (task.status === 'pending' || task.status === 'processing') {
                resetProgress();
                setProgress(task.progress);
            }
            
            else if (task.status === 'complete') {
                clearInterval(intervalId); 
                console.log("[✓] Tryon completed successfully!");

                if (mainPreview && task.result_url) {
                    mainPreview.src = `../AI/static/${task.result_url}`; 
                    mainPreview.style.display = 'block';
                    progressContainer.classList.add("hidden");
                    img.classList.replace("blur-[5px]", "blur-[0]");
                }

                if (onComplete) onComplete();
            }
            
            else if (task.status === 'failed' || task.status === 'db_error') {
                clearInterval(intervalId);
                alert(`AI Generation Failed: ${task.status}`);
                if (onComplete) onComplete();
            }

        } catch (err) {
            console.error("Polling Error:", err);
            clearInterval(intervalId);
            alert("Lost connection to progress tracker."); 
            if (onComplete) onComplete();
        }
    }, 1500); 
}

if (tryonBtn) {
    tryonBtn.addEventListener('click', async function () {
        const uploadInput = document.getElementById('uploadInput');
        const file = uploadInput?.files[0];

        progressContainer.classList.remove("hidden");
        img.classList.add("blur-[5px]");
        resetProgress();

        if (!file) {
            alert("Please upload your image before proceed!");
            return;
        }

        tryonBtn.disabled = true;
        tryonBtn.classList.add('opacity-50', 'cursor-not-allowed');
        const originalText = tryonBtn.innerHTML;
        tryonBtn.innerHTML = `<span>Uploading Assets...</span>`;

        try {
            const formData = new FormData();
            formData.append('image', file);

            const activeColorEl = document.querySelector(".activeColor");
            if (activeColorEl) {
                formData.append('color', activeColorEl.dataset.color || '');
            }

            const activeID = document.getElementById("productID")?.dataset.id;
            if (activeID) {
                formData.append('product_id', activeID || '');
            }

            const response = await fetch('../network/generative-proxy.php', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': csrfToken || ''},
                body: formData
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Error when trying to connect to AI service');
            }

            const taskId = result.task_id;
            console.log("New Task Id accepted. Task ID:", taskId);

            listenTaskProgress(taskId, () => {
                tryonBtn.disabled = false;
                tryonBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                tryonBtn.innerHTML = originalText;
            });

        } catch (error) {
            console.error("Tryon Error:", error);
            alert(error.message || "Something went wrong, please try again later!");
            tryonBtn.disabled = false;
            tryonBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            tryonBtn.innerHTML = originalText;
        }
    });
}