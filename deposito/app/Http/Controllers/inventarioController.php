<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventario; 
use App\Insumo;

class inventarioController extends Controller
{	
    public function index(){
        return view('inventario/indexInventario');
    }

    public function allInsumos(){

        return DB::table('insumos')
            ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
            ->where('inventarios.existencia', '>' , 0)
            ->select('insumos.codigo','insumos.descripcion','inventarios.existencia')->get();
    }

    public static function almacenaInsumo($insumo, $cantidad){

    	if( Inventario::where('insumo', $insumo)->first() ){

    		$existencia = Inventario::where('insumo', $insumo)->value('existencia');
    		$existencia += $cantidad;

    		Inventario::where('insumo' , $insumo)->update(['existencia' => $existencia]);
    	}
    	else{

    		Inventario::create([
    			'insumo' => $insumo,
    			'existencia' => $cantidad
    		]);
    	}
    }
}
