function onResponseJSON(response){
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
        console.log(json);
        return;
    }
    const lista_cocktail_preferiti = document.getElementById("lista-cocktail-preferiti");
    lista_cocktail_preferiti.innerHTML="";
    for(drink of json){
        // se per qualche drink si verifica un errore lato server, vai avanti
        if ("error" in drink){
            continue;
        }
        let div_scheda = document.createElement('div');
        //console.log(drink);
        div_scheda.classList="scheda";
        div_scheda.dataset.cardDrink=drink.strDrink.replace(" ","_");
        div_scheda.dataset.cardId=drink.idDrink;
        div_scheda.addEventListener('click', function(event){
            let idDrink = event.currentTarget.dataset.cardId;
            //console.log(idDrink);
            window.location.href="/cocktail/"+idDrink;
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
        div_scheda.appendChild(div_titolo_scheda);
        div_scheda.appendChild(img);
        div_scheda.appendChild(div_like);
        lista_cocktail_preferiti.appendChild(div_scheda);
    }
}

function onJSON_Like(json){
    if("error" in json){
        console.log(json.errorType);
        return;
    }
    const div_img_like = document.querySelector("[data-card-id='"+json.drinkId+"']");
    const img_like=div_img_like.childNodes[2].childNodes[0];
    if("like" in json){
        if(json.like===false){
            // tolgo direttamente la scheda dalla pagina preferiti
            div_img_like.remove();
        }
    }
}

function mettiTogliLike(event){
    const drink_target = event.target.parentElement.parentElement.dataset.cardId;
    fetch("mettiTogliLike/"+drink_target).then(onResponseJSON).then(onJSON_Like);
}

fetch("ritornaPreferiti").then(onResponseJSON).then(onJSON);