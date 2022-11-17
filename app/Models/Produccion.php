<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produccion extends Model
{
    use HasFactory;
    protected $table = 'produccion';

    public function produccion(){
        return $this->hasMany('App\Models\Produccion');
    }
    
    public function piscina(){
        return $this->belongsTo('App\Models\Piscina', 'piscina_id');
 
     }
}
