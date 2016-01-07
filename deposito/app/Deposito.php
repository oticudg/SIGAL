<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposito extends Model
{
    use SoftDeletes;

	protected $dates 	= ['deleted_at'];
    protected $fillable = ['nombre', 'codigo'];
   	protected $hidden   = ['created_at' , 'updated_at'];
}
