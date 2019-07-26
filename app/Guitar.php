<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guitar extends Model
{
    protected $table = 'guitars';

    //Relación
    public function user(){
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function brand(){    	
    	return $this->belongsTo('App\Brand', 'brand_id');
    }
}
