<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provedore extends Model
{	
   use SoftDeletes;

   protected $dates = ['deleted_at'];	
   protected $fillable = ['rif','nombre','telefono','direccion','contacto','email'];
   protected $hidden   = ['created_at' , 'updated_at'];
}
