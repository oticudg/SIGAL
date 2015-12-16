<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumos_edevolucione extends Model
{
    protected $fillable = ['devolucion','insumo', 'cantidad'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
