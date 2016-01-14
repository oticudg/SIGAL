<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventiario_operacione extends Model
{
    protected $fillable = ['referencia', 'type', 'existencia', 'insumo'];
}
