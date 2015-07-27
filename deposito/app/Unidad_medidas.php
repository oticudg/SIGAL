<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad_medidas extends Model
{
    protected $fillable = ['nombre'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
