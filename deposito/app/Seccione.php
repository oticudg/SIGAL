<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seccione extends Model
{
    protected $fillable = ['id','nombre'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
