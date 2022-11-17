<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camaronera extends Model
{
    use HasFactory;
    protected $table = 'camaronera';

    public function camaronera(){
        return $this->hasMany('App\Models\Camaronera');
    }

    public function estado(){
        return $this->belongsTo('App\Models\Estado', 'estado_id');
 
     }

     public function propietario(){
        return $this->belongsTo('App\Models\Usuario', 'propietario_id');
 
     }
}
