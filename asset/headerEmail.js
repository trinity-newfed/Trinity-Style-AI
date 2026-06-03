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