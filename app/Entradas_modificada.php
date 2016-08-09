<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entradas_modificada extends Model
{
    protected $fillable = ['entrada','Oprovedor','Mprovedor','Oorden',
    					'Morden','usuario', 'deposito'];
}
