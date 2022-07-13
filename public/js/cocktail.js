function onResponseJSON(response){
    //console.log(response);
    if(response.status == 500){
        // questo è un errore riferito al collegamento col mio server apache
        return JSON.parse('{"error":true}');
    }
    return response.json();
}

function onJSON_Like(json){
    // questo in genere avviene quando non sono loggato
    if(json.length===0 || !("drinkId" in json)){
        return;
    }
    // se ci sono stati errori dal server mostro cos'è successo
    if("error" in json){
        console.log(json.errorType);
        return;
    }
    //se tutto è andato bene e sono loggato
    let img_like = document.getElementsByClassName("img-like")[0];
    let conteggioLike=document.getElementsByClassName("conteggio")[0];
    if("like" in json){
        if(json.like===true){
            img_like.src="/img/like.png";
            // primo like che riceve la scheda
            if(conteggioLike.textContent===""){
                conteggioLike.innerText=1;
            }else{
                // altrimenti incremento il conteggio dei like
                conteggioLike.innerText=parseInt(conteggioLike.textContent)+1;
            }
        }
        else{
            img_like.src="/img/dislike.png";
            if(conteggioLike.textContent==="1"){
                conteggioLike.innerText="";
            }
            else{
                conteggioLike.innerText=parseInt(conteggioLike.textContent)-1;
            }
        }
    }
}

function mettiTogliLike(event){
    // prendo l'id inserito nel link
    let idCocktail = window.location.pathname.split("/")[2];
    if(isNaN(parseInt(idCocktail))){
        //se l'id del cocktail non è un numero allora non fare niente
        //ma non accadrà mai perchè questa cosa è già controllata dal server
        return;
    }
    const drink_target = parseInt(idCocktail);
    fetch(BASE_URL+"mettiTogliLike/"+drink_target).then(onResponseJSON).then(onJSON_Like);
}

function onJSON_Reviews(json){
    let reviews = document.getElementsByClassName("reviews")[0];

    for(recensione of json){
        let review_card = document.createElement("div");
        review_card.classList="review-card";
        let img_user = document.createElement("div");
        img_user.classList="img-user";
        let img_avatar = document.createElement("img");
        img_avatar.src="/img/avatar.png";
        img_avatar.alt="img-avatar";
        let information = document.createElement("div");
        information.classList="information";
        let dati_utente=document.createElement("div");
        dati_utente.classList="dati-utente";
        dati_utente.innerText=recensione.nome+" "+recensione.cognome;
        let review_user = document.createElement("div");
        review_user.classList="review-user";
        let p_review_user = document.createElement("p");
        p_review_user.innerText=recensione.content;
        let trash = document.createElement("div");
        trash.classList="trash";
        let img_trash = document.createElement("img");
        if("mine" in recensione){
            img_trash.addEventListener("click",rimuoviRecensione);
            img_trash.dataset.id=recensione.mine;
        }
        img_trash.src="/img/delete.png";
        img_trash.alt="delete";


        trash.appendChild(img_trash);
        review_user.appendChild(p_review_user);
        information.appendChild(dati_utente);
        information.appendChild(review_user);
        img_user.appendChild(img_avatar);
        review_card.appendChild(img_user);
        review_card.appendChild(information);
        if("mine" in recensione){
            review_card.appendChild(trash);
        }
        reviews.appendChild(review_card);
    }

}

function onJSON_TogliRecensione(json){
    if("error" in json){
        alert(json.errorType);
        return;
    }
    if("status" in json){
        if(json.status===true){
            let img_trash = document.querySelector('[data-id="'+json.value+'"]');
            img_trash.parentNode.parentNode.remove();
        }
    }
}

function rimuoviRecensione(event){
    let idReview = event.target.dataset.id;
    //console.log(idReview);
    fetch(BASE_URL+"togliRecensione/"+idReview).then(onResponseJSON).then(onJSON_TogliRecensione);
}

function onJSON_AggiungiRecensione(json){
    let reviews = document.getElementsByClassName("reviews")[0];

    let review_card = document.createElement("div");
    review_card.classList="review-card";
    let img_user = document.createElement("div");
    img_user.classList="img-user";
    let img_avatar = document.createElement("img");
    img_avatar.src="/img/avatar.png";
    img_avatar.alt="img-avatar";
    let information = document.createElement("div");
    information.classList="information";
    let dati_utente=document.createElement("div");
    dati_utente.classList="dati-utente";
    dati_utente.innerText=json.nome+" "+json.cognome;
    let review_user = document.createElement("div");
    review_user.classList="review-user";
    let p_review_user = document.createElement("p");
    p_review_user.innerText=json.content;
    let trash = document.createElement("div");
    trash.classList="trash";
    let img_trash = document.createElement("img");
    if("mine" in json){
        img_trash.addEventListener("click",rimuoviRecensione);
        img_trash.dataset.id=json.mine;
    }
    img_trash.src="/img/delete.png";
    img_trash.alt="delete";


    trash.appendChild(img_trash);
    review_user.appendChild(p_review_user);
    information.appendChild(dati_utente);
    information.appendChild(review_user);
    img_user.appendChild(img_avatar);
    review_card.appendChild(img_user);
    review_card.appendChild(information);
    if("mine" in json){
        review_card.appendChild(trash);
    }

    // lo metto come primo elemento anzichè come ultimo, cioè come fa l'appendChild
    reviews.prepend(review_card);
    
}

function inviaRecensione(event){
    let idCocktail = window.location.pathname.split("/")[2];
    if(isNaN(parseInt(idCocktail))){
        //se l'id del cocktail non è un numero non fare niente
        //non accade perchè questa cosa è già controllata dal server
        return;
    }
    const token = document.querySelector('meta[name="csrf-token"]').content;
    //impedisco che venga ricaricata la pagina
    event.preventDefault();
    let contenuto = event.target.childNodes[3].childNodes[1].value;
    fetch(BASE_URL+"aggiungiRecensione", {
        method: "post",
        body: JSON.stringify({
            testo: contenuto,
            idDrink: idCocktail
        }),
        headers:{
            "Content-Type": "application/json; charset=utf-8",
            "X-CSRF-Token": token
        }
    }).then(onResponseJSON).then(onJSON_AggiungiRecensione);
}

let idCocktail = window.location.pathname.split("/")[2];
if(isNaN(parseInt(idCocktail))){
    //se l'id del cocktail non è un numero non fare niente
    //non accade perchè questa cosa è già controllata dal server
    exit;
}
fetch(BASE_URL+"reviewsCocktail/"+idCocktail).then(onResponseJSON).then(onJSON_Reviews);
let img_like = document.getElementsByClassName("img-like")[0];
img_like.addEventListener('click', mettiTogliLike);
let form = document.querySelector("form");
form.addEventListener("submit",inviaRecensione);