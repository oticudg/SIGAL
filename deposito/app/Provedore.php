<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provedore extends Model
{
   protected $fillable = ['rif','nombre','telefono','direccion','contacto','email'];
   protected $hidden   = ['created_at' , 'updated_at'];
}
