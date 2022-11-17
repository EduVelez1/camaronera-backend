<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datos_Biomasa extends Model
{
    use HasFactory;
    protected $table = 'datos_biomasa';

    public function datosBiomasa(){
        return $this->hasMany('App\Models\Datos_Biomasa');
    }
}
