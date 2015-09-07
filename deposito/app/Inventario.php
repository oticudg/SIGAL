<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = ['insumo','existencia','Cmax','Cmin'];
}
