<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumos_entrada extends Model
{	
	protected $fillable = ['entrada','insumo', 'cantidad', 'type', 'deposito'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
