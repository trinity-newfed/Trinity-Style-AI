document.addEventListener("DOMContentLoaded", () => {
    const targets = document.querySelectorAll('.reveal-target');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {

                entry.target.classList.add('active');


                entry.target.querySelectorAll('.reveal-curtain, .reveal-fade').forEach(child => {
                    child.classList.add('active');
                });


                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: "0px 0px -40px 0px"
    });

    targets.forEach(target => observer.observe(target));
});