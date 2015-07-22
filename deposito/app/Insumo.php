<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = ['codigo','descripcion','id_presentacion','id_secction','un_med','cant_min',
    					  'cant_max','marca','imagen','ubicacion','principio_act','deposito'];
   	protected $hidden   = ['created_at' , 'updated_at'];
}
