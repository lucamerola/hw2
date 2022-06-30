<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends BaseController{

    public function logout(){
        //Elimina la sessione
        Session::flush();
        return redirect('home');
    }

    public function index(){
        if(Session::get('user_id')){
            return redirect('home');
        }
        $error=Session::get('error');
        Session::forget('error');
        return view('login')->with('error', $error);
    }

    public function login(){
        if(Session::get('user_id')){
            return redirect('home');
        }
        $request = request();
        if($this->countErrors($request) == 0){
            $user = User::where('email', request('email') )->first();
            Session::put('user_id', $user->id);
            return redirect('home');
        }else{
            return redirect('login')->withInput();
        }
    }

    private function countErrors($data) {
        $error = array();
        # USERNAME
        // Controlla che l'username rispetti il pattern specificato
        if
        (
            strlen($data['email'])==0 ||
            strlen($data['password'])==0
        ){
            $error[] = "Riempi tutti i campi";
        }
        # PASSWORD
        else if (strlen($data["password"]) < 8) {
            $error[] = "Caratteri password insufficienti";
        }
        # EMAIL
        else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $user = User::where('email', $data['email'])->first();
            if ($user === null) {
                $error[] = "Utente non registrato";
            }else if(!password_verify(request('password'), $user->password)){
                $error[] = "I dati non corrispondono";
            }
        }
        Session::put('error', $error);
        return count($error);
    }

}