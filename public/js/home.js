function onResponseJSON(response){
    //console.log(response);
    if(response.status == 500){
        // questo è un errore riferito al collegamento col mio server apache
        return JSON.parse('{"error":true}');
    }
    return response.json();
}

function onJSON(json){
    /* 
    questo è un errore dovuto o dal mancato collegamento tra il client e il server apache
    o dal mancato collegamento tra server apache e il server dove prendo i drink
    */
    if("error" in json){
        return;
    }
    const lista_cocktail = document.getElementById("lista-cocktail");
    lista_cocktail.innerHTML="";
    //console.log(json);
    for(drink of json){
        let div_scheda = document.createElement('div');
        //console.log(drink);
        div_scheda.classList="scheda";
        div_scheda.dataset.cardDrink=drink.strDrink.replace(" ","_");
        div_scheda.dataset.cardId=drink.idDrink;
        div_scheda.addEventListener('click', function(event){
            let idDrink = event.currentTarget.dataset.cardId;
            //console.log(idDrink);
            window.location.href=window.location.href+"cocktail/"+idDrink;
        });
        let div_like = document.createElement('div');
        div_like.classList="div-like";
        let img_like = document.createElement('img');
        img_like.classList="img-like";
        img_like.alt="img-like";
        img_like.addEventListener('click', mettiTogliLike);
        if ("like" in drink){
            if(drink.like===true){
                img_like.src="/img/like.png";
            }
        }else{
            img_like.src="/img/dislike.png";
        }
        let conteggioLike = document.createElement('h1');
       /* if(drink.like!==undefined && drink.like===true){
            
        }*/
        let div_titolo_scheda = document.createElement('div');
        div_titolo_scheda.classList="titolo-scheda";
        let h4 = document.createElement('h4');
        h4.innerText=drink.strDrink;
        let img = document.createElement('img');
        img.classList="img-scheda";
        img.src=drink.strDrinkThumb;
        img.alt="img-"+(drink.strDrink).replace(" ", "_");

        div_titolo_scheda.appendChild(h4);
        div_like.appendChild(img_like);
        if(drink.conteggioLike>0){
            conteggioLike.innerText=drink.conteggioLike;
        }
        div_like.appendChild(conteggioLike);
        div_scheda.appendChild(div_titolo_scheda);
        div_scheda.appendChild(img);
        div_scheda.appendChild(div_like);
        lista_cocktail.appendChild(div_scheda);
    }
}

/*
Struttura di ogni scheda cocktail
<div class="scheda" data-card-drink="" data-card-id="">
    <div class="titolo-scheda">
        <h4> </h4>
    </div>
    <img class="img-scheda" src="" alt="">
    <div class="div-like">
        <img class="img-like" src="" alt="">
        <h2 ></h2>
    </div>
</div>
*/

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
    let div_img_like = document.querySelector("[data-card-id='"+json.drinkId+"']");
    let img_like =div_img_like.childNodes[2].childNodes[0];
    let conteggioLike=div_img_like.childNodes[2].childNodes[1];
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
    const drink_target = event.target.parentElement.parentElement.dataset.cardId;
    fetch("mettiTogliLike/"+drink_target).then(onResponseJSON).then(onJSON_Like);
    // per evitare l'effetto del bubbling
    event.stopPropagation();
}

function onJSON_Filter(json){
    if("error" in json){
        console.log(json.errorType);
        return;
    }
    const lista_cocktail_ricercati = document.getElementById("lista-cocktail-ricercati");
    lista_cocktail_ricercati.innerHTML="";
    for(drink of json){
        let div_scheda = document.createElement('div');
        div_scheda.classList="scheda";
        div_scheda.dataset.cardDrink=drink.strDrink.replace(" ","_");
        div_scheda.dataset.cardId=drink.idDrink;
        div_scheda.addEventListener('click', function(event){
            let idDrink = event.currentTarget.dataset.cardId;
            //console.log(idDrink);
            window.location.href=window.location.href+"cocktail/"+idDrink;
        });
        let div_like = document.createElement('div');
        div_like.classList="div-like";
        let img_like = document.createElement('img');
        img_like.classList="img-like";
        img_like.alt="img-like";
        img_like.addEventListener('click', mettiTogliLike);
        if ("like" in drink){
            if(drink.like===true){
                img_like.src="/img/like.png";
            }
        }else{
            img_like.src="/img/dislike.png";
        }
        let conteggioLike = document.createElement('h1');
        let div_titolo_scheda = document.createElement('div');
        div_titolo_scheda.classList="titolo-scheda";
        let h4 = document.createElement('h4');
        h4.innerText=drink.strDrink;
        let img = document.createElement('img');
        img.classList="img-scheda";
        img.src=drink.strDrinkThumb;
        img.alt="img-"+(drink.strDrink).replace(" ", "_");

        div_titolo_scheda.appendChild(h4);
        div_like.appendChild(img_like);
        if(drink.conteggioLike>0){
            conteggioLike.innerText=drink.conteggioLike;
        }
        div_like.appendChild(conteggioLike);
        div_scheda.appendChild(div_titolo_scheda);
        div_scheda.appendChild(img);
        div_scheda.appendChild(div_like);
        lista_cocktail_ricercati.appendChild(div_scheda);
    }
}

function updateValue(event){
    //console.log(event.target.value);
    const lista_cocktail = document.getElementById("lista-cocktail");
    const lista_cocktail_ricercati = document.getElementById("lista-cocktail-ricercati");
    if(event.target.value!==""){
        lista_cocktail.classList.remove("visible-flex");
        lista_cocktail.classList.add("hidden");
        lista_cocktail_ricercati.classList.remove("hidden");
        lista_cocktail_ricercati.classList.add("visible-flex");
    }else{
        lista_cocktail.classList.remove("hidden");
        lista_cocktail.classList.add("visible-flex");
        lista_cocktail_ricercati.classList.remove("visible-flex");
        lista_cocktail_ricercati.classList.add("hidden");
    }
    fetch("filtra/"+event.target.value).then(onResponseJSON).then(onJSON_Filter);
}

var ricerca=document.getElementsByTagName("input")[0];
ricerca.addEventListener('input', updateValue);
fetch("openingCocktail").then(onResponseJSON).then(onJSON);