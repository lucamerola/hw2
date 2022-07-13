<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Review extends Eloquent{

    protected $connection = 'mongodb';
    protected $collection = 'reviews';

    //protected $table = 'like_drink';
    //public $timestamps = false;

    protected $fillable = [
        'cod_utente',
        'cod_drink',
        'content',
    ];

    public function user(){
        return $this->belongsTo("App/Models/User");
    }
}