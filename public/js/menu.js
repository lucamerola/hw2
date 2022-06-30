var menu_user = document.querySelector("#div-user");
menu_user.addEventListener('click', mostra_tendina);
var hamburger = document.querySelector("#div-open-menu");
hamburger.addEventListener('click', mostra_lateral_menu);
var div_close = document.querySelector("#div-close");
div_close.addEventListener('click', nascondi_lateral_menu);


function nascondi_lateral_menu(event){
    var nav_bar = document.querySelector("#nav-bar");
    nav_bar.classList.remove("flex");
    nav_bar.classList.add("hidden");
    var article = document.getElementsByTagName("article")[0];
    article.style.opacity = "1";
}

function mostra_lateral_menu(event){
    var nav_bar = document.querySelector("#nav-bar");
    nav_bar.classList.remove("hidden");
    nav_bar.classList.add("flex");
    var article = document.getElementsByTagName("article")[0];
    article.style.opacity = "0.5";
    
}

function mostra_tendina(event){
    var tendina = document.querySelector("#menu-tendina");
    tendina.classList.remove("hidden");
    tendina.classList.add("flex");
    var menu = document.querySelector("#div-user");
    menu.removeEventListener('click', mostra_tendina);
    menu.addEventListener('click', nascondi_tendina);
}

function nascondi_tendina(event){
    var tendina = document.querySelector("#menu-tendina");
    tendina.classList.remove("flex");
    tendina.classList.add("hidden");
    var menu = document.querySelector("#div-user");
    menu.removeEventListener('click', nascondi_tendina);
    menu.addEventListener('click', mostra_tendina);
}