<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LikeController extends BaseController{

    public function mettiTogliLike(Request $request){
        if(!Session::get('user_id')){
            return json_encode([]);
        }
        //echo Session::get('user_id');
        $id_cocktail=$request->route('idCocktail');
        $response=array();
        #se il parametro passato non è un intero
        if(!intval($id_cocktail)){
            $response['value']=$id_cocktail;
            $response['error']=true;
            $response['errorType']="Stai inserendo qualcosa di strano";
            return response()->json($response);
        }
        $likes_drink = Likes::where('cod_utente', Session::get('user_id') )->get();
        #controllo se già avevo messo il like
        #vedendo se è presente l'id del drink
        #e se avevo già messo il like, lo tolgo
        foreach($likes_drink as $drinkLiked){
            if($id_cocktail == $drinkLiked['cod_drink']){
                #se avevo messo il like, lo tolgo
                $result=Likes::where('cod_utente',Session::get('user_id'))->where('cod_drink',$id_cocktail)->delete();
                if($result){
                    $response['drinkId']=$id_cocktail;
                    $response['like']=false;
                    return response()->json($response);
                }
                else{
                    $response['drinkId']=$id_cocktail;
                    $response['error']=true;
                    $response['errorType']="Non è stato possibile togliere il like";
                    return response()->json($response);
                }
            }
        }
        #altrimenti se non è presente il drink
        #gli metto il like
        /*
            ma prima controllo che esista il drink
            perchè un utente può inserire il drink dal url, e mettere numeri a caso
        */
        $url="https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=".$id_cocktail;
        $json = Http::get($url);
        if ($json->failed()){
            $response['error']=true;
            $response['errorType']="Http::get Error mettiTogliLike";
            return response()->json($response);
        }
        $drink = json_decode($json, 1);
        $drink=$drink['drinks'];
        if($drink === null){
            $response['error']=true;
            $response['errorType']="Non esiste un cocktail con quel id";
            return response()->json($response);
        }
        #se invece non è null, cioè se esiste il cocktail, allora lo inserisco nel db
        $newLike = Likes::create([
            'cod_utente' => Session::get('user_id'),
            'cod_drink' => $id_cocktail
        ]);
        if($newLike){
            $response['drinkId']=$id_cocktail;
            $response['like']=true;
            return response()->json($response);
        }else{
            $response['drinkId']=$id_cocktail;
            $response['error']=true;
            $response['errorType']="Non è stato possibile inserire il like";
            return response()->json($response);
        }
    }

    public function ritornaPreferiti(){
        if(!Session::get('user_id')){
            redirect("home");
        }
        $likes_drink = Likes::where('cod_utente', Session::get('user_id') )->get();
        $my_drink_list=array();
        foreach($likes_drink as $drinkLiked){
            $url="https://www.thecocktaildb.com/api/json/v1/1/lookup.php?i=".$drinkLiked['cod_drink'];
            $json = Http::get($url);
            if ($json->failed()){
                $list_drinks_API['idDrink']=$drinkLiked['cod_drink'];
                $list_drinks_API['error']=true;
                $list_drinks_API['errorType']="Http::get Error";
            }else{
                $list_drinks_API = json_decode($json, 1);
                $list_drinks_API=$list_drinks_API['drinks'][0];
                $list_drinks_API['like']=true;
            }
            $my_drink_list[]=$list_drinks_API;
        }
        return response()->json($my_drink_list);
    }

    public function index(){
        if(!Session::get('user_id')){
            return redirect('home');
        }
        return view('preferiti');
    }
    
}