<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Salida;
use App\insumos_salida;

class salidasController extends Controller
{
	public function index(){
    	return view('salidas/indexSalidas');
    }

    public function detalles(){
        return view('salidas/detallesSalida');
    }

    public function allInsumos(){

        return DB::table('insumos_salidas')
            ->join('salidas', 'salidas.id', '=', 'insumos_salidas.salida')
            ->join('insumos', 'insumos.id' , '=', 'insumos_salidas.insumo')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo as salida',
                'insumos.codigo','insumos.descripcion','insumos_salidas.cantidad')
            ->get();
    }

    public function allSalidas(){

        return DB::table('salidas')
            ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
                'departamentos.nombre as departamento', 'salidas.id')
            ->get();
    }

}
