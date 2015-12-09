<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumos_entrada extends Model
{	
	protected $fillable = ['entrada','insumo', 'cantidad'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
