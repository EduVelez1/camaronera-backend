<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biomasa extends Model
{
    use HasFactory;
    protected $table = 'biomasa';

    public function biomasa(){
        return $this->hasMany('App\Models\Biomasa');
    }
}
