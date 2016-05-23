<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['nombre','abreviatura', 'tipo','naturaleza','uso'];
    protected $hidden   = ['created_at' , 'updated_at', 'deleted_at'];
}
