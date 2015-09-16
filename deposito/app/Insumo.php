<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insumo extends Model
{	
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $fillable = ['codigo','descripcion'];
   	protected $hidden   = ['created_at' , 'updated_at'];
}
