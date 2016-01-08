<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = ['insumo','existencia','Cmed','Cmin', 'deposito'];

    public static function alert(){

    	$registros = Inventario::get(['id', 'existencia', 'Cmed', 'Cmin']);
        $cantidad = 0;

        foreach ($registros as $registro) {
            if( $registro['existencia'] <= $registro['Cmed'] || $registro['existencia'] <= $registro['Cmin'])
                $cantidad++;
        }

        return $cantidad;
    } 
}
