      const prices = document.querySelectorAll(".ItemPrice");
      prices.forEach(price =>{
        price.textContent = "$" + parseFloat(price.dataset.price).toFixed(0);
      });

      const shortPrices = document.querySelectorAll(".short");
      shortPrices.forEach(prices =>{
        prices.textContent = "$" + parseFloat(prices.dataset.price).toFixed(0);
      });


      //Order & Profile toggle
        document.querySelector(".orderBlock").classList.add("active");

        document.querySelectorAll(".profileBlock").forEach(profile =>{
            profile.addEventListener('click', function(){
                action = "profile";
                OrderProfileToggle(action);
                this.classList.add("active");
            })
        });

        document.querySelectorAll(".orderBlock").forEach(order =>{
            order.addEventListener('click', function(){
                action = "order";
                OrderProfileToggle(action);
                this.classList.add("active");
            })
        });