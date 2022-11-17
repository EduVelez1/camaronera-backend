<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'usuario';

    protected $hidden = [
        'contrasena',
    ];

    public function usuario(){
        return $this->hasMany('App\Usuario');
    }

    public function role(){
       return $this->belongsTo('App\Models\Role', 'role_id');

    }

    public function estado(){
        return $this->belongsTo('App\Models\Estado', 'estado_id');
 
     }
    
}
