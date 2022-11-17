<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Larva extends Model
{
    use HasFactory;
    protected $table = 'larva';

    public function larva(){
        return $this->hasMany('App\Models\Larva');
    }
    public function proveedor(){
        return $this->belongsTo('App\Models\Usuario', 'id_proveedor');
 
     }
}
