<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    protected $fillable = ['codigo', 'orden', 'tercero', 'usuario', 'type', 'deposito','documento'];
}
