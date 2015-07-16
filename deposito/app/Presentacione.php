<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Presentacione extends Model
{
    protected $fillable = ['nombre'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
