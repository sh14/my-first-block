document.querySelectorAll(".js-carousel-bullet").forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault();const t=e.target,l=t.getAttribute("data-slide"),s=t.closest(".js-carousel");s.querySelectorAll(".js-carousel-bullet").forEach((e=>{e.getAttribute("data-slide")===l?e.classList.add("carousel__bullet_active"):e.classList.remove("carousel__bullet_active")}));const o=s.querySelector(".js-carousel-container");o&&(o.style.transform="translateX("+-100*l+"%)")}))})),document.querySelectorAll(".js-offer-preview").forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault();const t=e.target.getAttribute("data-src");console.log(t);const l=document.querySelector(".js-offers-modal-image");if(l){l.src=t,document.body.classList.add("modal-lock");const e=l.closest(".js-offers-modal");e&&e.classList.add("offers-modal_active")}}))})),document.querySelectorAll(".js-offers-modal").forEach((e=>{e.addEventListener("click",(e=>{e.preventDefault(),e.target.classList.remove("offers-modal_active"),document.body.classList.remove("modal-lock")}))}));