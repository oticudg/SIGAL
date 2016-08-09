<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privilegio extends Model
{
    protected $fillable = ['usuario','usuarios', 'usuarioN', 'usuarioM', 
    	'usuarioD', 'provedores','provedoreN', 'provedoreM', 'provedoreD',
    	'departamentos', 'departamentoN', 'departamentoD', 'insumos',
    	'insumoN', 'insumoM', 'insumoD', 'inventarios', 'inventarioH',
    	'entradas', 'entradaR', 'salidas', 'salidaR', 'modificaciones',
    	'estadisticas','departamentoM','depositos', 'depositoN', 'depositoM', 'depositoD'];
}
