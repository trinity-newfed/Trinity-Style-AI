
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


