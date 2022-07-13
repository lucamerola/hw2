<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ReviewsController extends BaseController{

    public function aggiungiRecensione(Request $request){
        if(!Session::get('user_id')){
            $response['drinkId']=$request['idDrink'];
            $response['error']=true;
            $response['errorType']="Impossibile aggiungere la recensione";
            return response()->json($response);
        }else{
            $newReview = Review::create([
                'cod_utente' => Session::get('user_id'),
                'content' => $request['testo'],
                'cod_drink' => $request['idDrink']
            ]);
            if($newReview){
                $response['drinkId']=$request['idDrink'];
                $utente=User::where('id', Session::get('user_id'))->get();
                $response['nome']=$utente[0]['nome'];
                $response['cognome']=$utente[0]['cognome'];
                $response['mine']=$newReview['_id'];
                $response['content']=$newReview['content'];
                return response()->json($response);
            }else{
                $response['drinkId']=$request['idDrink'];
                $response['error']=true;
                $response['errorType']="Non è stato possibile inserire la recensione";
                return response()->json($response);
            }
        }
    }

    public function togliRecensione(Request $request){
        $id_recensione=$request->route('idRecensione');
        if(Session::get('user_id')){
            $review=Review::where('_id', $id_recensione )
                    ->where('cod_utente', Session::get('user_id'))
                    ->delete();
            if($review==0){
                $response['value']=$id_recensione;
                $response['error']=true;
                $response['errorType']="Impossibile eliminare la recensione";
                return response()->json($response);
            }else{
                $response['value']=$id_recensione;
                $response['status']=true;
                $response['type']="Recensione eliminata";
                return response()->json($response);
            }
        }
        $response['value']=$id_recensione;
        $response['error']=true;
        $response['errorType']="È necessario fare il login";
        return response()->json($response);
        
    }

    public function giveReviews(Request $request){
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
            $response['errorType']="Http::get Error ReviewsController";
            return response()->json($response);
        }
        $drink = json_decode($json, 1);
        $drink=$drink['drinks'];
        if($drink === null){
            $response['error']=true;
            $response['errorType']="Non esiste un cocktail con quel id";
            return response()->json($response);
        }
        //print_r($drink[0]['idDrink']);
        $reviews=Review::where('cod_drink', $drink[0]['idDrink'] )
                ->orderBy('created_at', 'DESC')
                ->get();
        if(count($reviews)==0){
            return response()->json([]);
        }
        //vedo se la session è attiva
        //se è attiva faccio un incrocio tra l'utente logggato
        //e le recensioni che ha fatto
        if(Session::get('user_id')){
            foreach($reviews as $review){
                if($review['cod_utente']==Session::get('user_id')){
                    $review['mine']=$review['_id'];
                }
                $utente=User::where('id', $review['cod_utente'])->get();
                if(count($utente)==0){
                    continue;
                }
                $review['nome']=$utente[0]['nome'];
                $review['cognome']=$utente[0]['cognome'];
                //non voglio tornare anche l'id dell'utente che ha fatto la recensione
                unset($review['cod_utente']);
                unset($review['cod_utente']);
                unset($review['_id']);
                unset($review['updated_at']);
                unset($review['created_at']);
                //non serve che torni l'id del drink
                unset($review['cod_drink']);
            }
        }else{
            foreach($reviews as $review){
                $utente=User::where('id', $review['cod_utente'])->get();
                if($utente==null){
                    continue;
                }
                $review['nome']=$utente[0]['nome'];
                $review['cognome']=$utente[0]['cognome'];
                //non voglio tornare anche l'id dell'utente che ha fatto la recensione
                unset($review['cod_utente']);
                unset($review['_id']);
                unset($review['updated_at']);
                unset($review['created_at']);
                //non serve che torni l'id del drink
                unset($review['cod_drink']);
                
            }
        }
        return response()->json($reviews);
    }

}