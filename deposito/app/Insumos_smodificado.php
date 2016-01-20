<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumos_smodificado extends Model
{
    protected $fillable = ['salida','insumo','Osolicitado','Msolicitado','Odespachado', 'Mdespachado', 'deposito'];
}
