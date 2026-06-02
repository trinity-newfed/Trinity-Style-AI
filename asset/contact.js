const contactBtn = document.querySelectorAll(".contact-submitBtn");

contactBtn.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault(); 

        const formContainer = this.closest(".contact-form") || this.parentElement;

        if (!formContainer) {
            console.error("Không tìm thấy form container!");
            return;
        }

        let nameElement = formContainer.querySelector(".name");
        let emailElement = formContainer.querySelector(".email");
        let moreElement = formContainer.querySelector(".more");

        let name = nameElement ? encodeURIComponent(nameElement.value.trim()) : '';
        let email = emailElement ? encodeURIComponent(emailElement.value.trim()) : '';
        let more = moreElement ? encodeURIComponent(moreElement.value.trim()) : '';

        if (!email) {
            alert("Please fill in your contact email.");
            return;
        }

        fetch('../Database/contact.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `email=${email}&name=${name}&more=${more}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(message => {
            if (message.status === "success") {
                alert("Email sent successfully" + message.message);
                if(nameElement) nameElement.value = '';
                if(emailElement) emailElement.value = '';
                if(moreElement) moreElement.value = '';
            } else {
                alert(message.message || "Something went wrong.");
            }
        })
        .catch(error => {
            console.error('Error submitting contact form:', error); 
        });
    });
});