<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CocktailController extends BaseController{


    public function openingCocktail(){
        $url="https://www.thecocktaildb.com/api/json/v1/1/filter.php?c=Cocktail";
        $json = Http::get($url);
        if ($json->failed()){
            $response['error']=true;
            $response['errorType']="Http::get Error OpeningCocktail";
            return response()->json($response);
        } 
        $list_drinks_API = json_decode($json, 1);
        $list_drinks_API=$list_drinks_API['drinks'];
        $my_drinks_List=array();
        if(Session::get('user_id')){
            #Se c'è una sessione attiva
            #prendo i cocktail a cui l'utente ha messo il like
            $likes_drink = Likes::where('cod_utente', Session::get('user_id') )->get();
            #creo una lista che dovrà contenere gli id dei cocktail
            #a cui l'utente ha messo il like
            $drinks_code=array();
            foreach($likes_drink as $drinkLiked){
                #inserisco questi valori nella lista
                $drinks_code[]=$drinkLiked['cod_drink'];
            }
            for($i=0;$i<12;$i++){
                $maxJ=count($drinks_code);
                for($j=0;$j<$maxJ;$j++){
                    if($drinks_code[$j]==$list_drinks_API[$i]['idDrink']){
                        $list_drinks_API[$i]['like']=true;
                    }
                }
            }
        }
        /*
        * visto che voglio tornato solo 12 cocktail, inserisco
        * i primi 12 cocktail in una lista che chiamo
        * my_drink_list, e mi faccio
        * tornare questa con un json
        */
        for($i=0;$i<12;$i++){
            // aggiungo a questi cocktail il numero di like messi dagli utenti
            $numeroLike=count(Likes::where('cod_drink', $list_drinks_API[$i]['idDrink'] )->get());
            $list_drinks_API[$i]['conteggioLike']=$numeroLike;
            array_push($my_drinks_List, $list_drinks_API[$i]);
        }
    
        return response()->json($my_drinks_List);
    }

    public function filtra(Request $request){
        $filtro=$request->route('filtro');
        if(!$filtro){
            $response=array();
            $response['error']=true;
            $response['errorType']="Non è presente il nome da filtrare";
            return response()->json_encode($response);
        }
        $url="https://www.thecocktaildb.com/api/json/v1/1/search.php?f=".$filtro[0];
        $json = Http::get($url);
        if ($json->failed()){
            $response['error']=true;
            $response['errorType']="Http::get Error Search";
            return response()->json($response);
        }
        $list_cocktail_to_filter=array();
        $list_drinks_API = json_decode($json, 1);
        $list_drinks_API=$list_drinks_API['drinks'];
        $max_cocktail = count($list_drinks_API);
        if($max_cocktail>12){
            $max_cocktail=12; //voglio stamparne solo 12
        }
        for($i=0;$i<$max_cocktail;$i++){
            if(strpos(strtolower($list_drinks_API[$i]['strDrink']), $filtro)!==false){
                //filtro attraverso il nome e inserisco il conteggio dei like
                $numeroLike=count(Likes::where('cod_drink', $list_drinks_API[$i]['idDrink'] )->get());
                $list_drinks_API[$i]['conteggioLike']=$numeroLike;
                $list_cocktail_to_filter[]=$list_drinks_API[$i];
            }
        }
        if(Session::get('user_id')){
            #Se l'utente è loggato, aggiungo 
            #i like che aveva messo
            #su questi cocktail
            $likes_drink = Likes::where('cod_utente', Session::get('user_id') )->get();
            $drinks_code=array();
            foreach($likes_drink as $drinkLiked){
                $drinks_code[]=$drinkLiked['cod_drink'];
            }
            $maxI=count($list_cocktail_to_filter);
            for($i=0;$i<$maxI;$i++){
                $maxJ=count($drinks_code);
                for($j=0;$j<$maxJ;$j++){
                    if($drinks_code[$j]==$list_cocktail_to_filter[$i]['idDrink']){
                        $list_cocktail_to_filter[$i]['like']=true;
                    }
                }
            }
        }
        return response()->json($list_cocktail_to_filter);
    }

    public function paginaCocktail(Request $request){
        $id_cocktail=$request->route('idCocktail');
        #se il parametro passato non è un intero
        if(!intval($id_cocktail)){
            $response['value']=$id_cocktail;
            $response['error']=true;
            $response['errorType']="Stai inserendo qualcosa di strano";
            return response()->json($response);
        }

        $url="https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=".$id_cocktail;
        $json = Http::get($url);
        if ($json->failed()){
            $response['error']=true;
            $response['errorType']="Http::get Error paginaCocktail";
            return response()->json($response);
        }
        $drink = json_decode($json, 1);
        $drink=$drink['drinks'];
        if($drink === null){
            $response['error']=true;
            $response['errorType']="Non esiste un cocktail con quel id";
            return response()->json($response);
        }
        //se invece esiste
        //vedo il numero di like che ha ricevuto
        $numeroLike=count(Likes::where('cod_drink', $drink[0]['idDrink'] )->get());
        //controllo se ho messo io il like
        //prima però controllo se c'è una sessione attiva
        //se non c'è, torno la pagina con like=false
        if(!Session::get('user_id')){
            return view('cocktail')
            ->with("nome", $drink[0]["strDrink"])
            ->with("urlImg", $drink[0]["strDrinkThumb"])
            ->with("like", false)
            ->with("conteggioLike", $numeroLike);
        }
        //se c'è una sessione controllo lo stato del like
        $likes_drink = Likes::where('cod_utente', Session::get('user_id') )->where('cod_drink', $id_cocktail)->get();
        if(count($likes_drink)==0){
            //vuol dire che non ho mai messo like a quel drink
            return view('cocktail')
            ->with("nome", $drink[0]["strDrink"])
            ->with("urlImg", $drink[0]["strDrinkThumb"])
            ->with("like", false)
            ->with("conteggioLike", $numeroLike);
        }
        
        //print_r(json_encode($drink[0]));
        //return view('cocktail');
        //altrimenti ho messo like al cocktail 
        // e torno la pagina con like=true
        return view('cocktail')
            ->with("nome", $drink[0]["strDrink"])
            ->with("urlImg", $drink[0]["strDrinkThumb"])
            ->with("like", true)
            ->with("conteggioLike", $numeroLike);
    }
}