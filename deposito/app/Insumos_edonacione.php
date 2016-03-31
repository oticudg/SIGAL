<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumos_edonacione extends Model
{
    protected $fillable = ['donacion','insumo', 'cantidad'];
    protected $hidden   = ['created_at' , 'updated_at'];
}
