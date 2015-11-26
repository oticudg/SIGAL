<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{	
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $fillable = ['nombre'];
   	protected $hidden   = ['created_at' , 'updated_at'];
}
