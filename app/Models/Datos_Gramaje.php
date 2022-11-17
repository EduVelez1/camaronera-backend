<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datos_Gramaje extends Model
{
    use HasFactory;

    protected $table = 'datos_gramaje';

    public function datosGramaje(){
        return $this->hasMany('App\Models\Datos_Gramaje');
    }
}
