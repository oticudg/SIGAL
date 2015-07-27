<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidad_medida extends Model
{
    protected $fillable = ['nombre'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
