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
        if ($json->failed()) abort(500);

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
        for($i=0;$i<12;$i++){
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
        if ($json->failed()) abort(500);
        $list_cocktail_to_filter=array();
        $list_drinks_API = json_decode($json, 1);
        $list_drinks_API=$list_drinks_API['drinks'];
        $max_cocktail = count($list_drinks_API);
        if($max_cocktail>12){
            $max_cocktail=12; //voglio stamparne solo 12
        }
        for($i=0;$i<$max_cocktail;$i++){
            if(strpos(strtolower($list_drinks_API[$i]['strDrink']), $filtro)!==false){
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
}