<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gramaje extends Model
{
    use HasFactory;
    protected $table = 'gramaje';

    public function gramaje(){
        return $this->hasMany('App\Models\Gramaje');
    }
}
