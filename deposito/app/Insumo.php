<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = ['codigo','descripcion'];
   	protected $hidden   = ['created_at' , 'updated_at'];
}
