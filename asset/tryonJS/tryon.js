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
            img.src = src;
        });
    });
}

//Upload Img
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
            img.src = URL.createObjectURL(file);
        }
    });
}

//Try on
const tryonBtn = document.querySelector(".tryonBtn");
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

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
                console.log(`AI Progress: ${task.progress}% - Status: ${task.status}`);
                if (tryonBtn) {
                    tryonBtn.innerHTML = `<span>Processing AI (${task.progress}%)...</span>`;
                }
            }
            
            else if (task.status === 'complete') {
                clearInterval(intervalId); 
                console.log("[✓] Tryon completed successfully!");

                if (mainPreview && task.result_url) {
                    mainPreview.src = `/static/${task.result_url}`; 
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