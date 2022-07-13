<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterController extends BaseController{
    //TODO new user, checkusername, checkeail, index
    
    protected function register(){
        if(Session::get('user_id')){
            return redirect('/');
        }
        $request = request();
        if($this->countErrors($request) == 0){
            $newUser = User::create([
                'nome' => $request['nome'],
                'cognome' => $request['cognome'],
                'email' => $request['email'],
                'password' => password_hash($request['password'], PASSWORD_BCRYPT)
            ]);
            if($newUser){
                Session::put('user_id', $newUser->id);
                return redirect('/');
            }
        }
        else {
            // se va male, ritorno i dati inseriti alla view
            // e lo reindirizzo alla pagina di registrazione
            return redirect('register')->withInput();
        }
    }

    private function countErrors($data) {
        $error = array();
        # USERNAME
        // Controlla che l'username rispetti il pattern specificato
        if
        (
            strlen($data['nome'])==0 ||
            strlen($data['cognome'])==0 ||
            strlen($data['email'])==0 ||
            strlen($data['password'])==0 ||
            strlen($data['ripetiPassword'])==0
        ){
            $error[] = "Riempi tutti i campi";
        }
            
        else if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $data['nome'])) {
            $error[] = "Nome non valido";
        } 
        else if(!preg_match('/^[a-zA-Z0-9_]{1,15}$/', $data['cognome'])) {
            $error[] = "Cognome non valido";
        }
        # PASSWORD
        else if (strlen($data["password"]) < 8) {
            $error[] = "Caratteri password insufficienti";
        } 
        # CONFERMA PASSWORD
        else if (strcmp($data["password"], $data["ripetiPassword"]) != 0) {
            $error[] = "Le password non coincidono";
        }
        # EMAIL
        else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $error[] = "Email non valida";
        } else {
            $email = User::where('email', $data['email'])->first();
            if ($email !== null) {
                $error[] = "Email già utilizzata";
            }
        }
        Session::put('error', $error);
        return count($error);
    }

    public function checkEmail($emailToCheck) {
        $exist = User::where('email', $emailToCheck)->exists();
        return ['exists' => $exist];
    }

    public function index() {
        if(Session::get('user_id')){
            return redirect('/');
        }
        $error=Session::get('error');
        Session::forget('error');
        //return view('register');
        return view('register2')->with('error', $error);
    } 
}