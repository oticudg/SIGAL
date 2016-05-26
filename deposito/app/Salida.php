<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    protected $fillable = ['codigo','tercero', 'usuario', 'deposito','documento'];
}
