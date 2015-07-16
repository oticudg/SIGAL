<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class seccione extends Model
{
    protected $fillable = ['id','nombre'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
