<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piscina extends Model
{
    use HasFactory;
    protected $table = 'piscina';

    public function piscina(){
        return $this->hasMany('App\Models\Piscina');
    }

    public function camaronera(){
        return $this->belongsTo('App\Models\Camaronera', 'camaronera_id');
 
     }
    public function estado(){
        return $this->belongsTo('App\Models\Estado', 'estado_id');
 
     }
}
