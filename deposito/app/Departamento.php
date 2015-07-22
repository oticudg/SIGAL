<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['nombre','division','sello','firma'];
   	protected $hidden   = ['created_at' , 'updated_at'];
}
