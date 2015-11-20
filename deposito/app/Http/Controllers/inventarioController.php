<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventario; 
use App\Insumo;

class inventarioController extends Controller
{	
    public function index(){
        return view('inventario/indexInventario');
    }

    public function viewHerramientas(){
        return view('inventario/herramientasInventario');
    }

    public function viewInsumosAlertas(){
        return view('inventario/nivelesInventario');
    }

    public function allInsumos(){

        return DB::table('insumos')
            ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
            ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
            ->get();
    }

    public function getInsumosConsulta(Request $request){

        $consulta = $request->input('insumo');

        if($consulta != ""){

            return DB::table('insumos')
                        ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                        ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                            'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
                        ->where('insumos.descripcion', 'like', $consulta.'%')
                        ->orwhere('insumos.codigo', 'like', $consulta.'%')
                        ->take(20)->get();
        }

        return "[]"; 
    }

    public function configuraAlarmas(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'insumos' =>  'required|insumos_alarmas',
        ]);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            $insumos = $data['insumos'];

            foreach($insumos as $insumo) {

                Inventario::where('insumo' , $insumo['id'])->update([
                    'Cmin' => $insumo['min'],
                    'Cmed' => $insumo['med']
                ]);                
            }

            return Response()->json(['status' => 'success', 'menssage' => 
                'Alarmas configuradas exitosamente']);
        }

    }
    
    public function insumosAlert(){
        
        $registros = Inventario::get(['id', 'existencia', 'Cmed', 'Cmin']);
        $ids = [];

        foreach ($registros as $registro) {
            if( $registro['existencia'] <= $registro['Cmed'] || $registro['existencia'] <= $registro['Cmin'])
                array_push($ids, $registro['id']);
        }

        $insumos = DB::table('insumos')
                   ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                   ->whereIn('inventarios.id', $ids)
                   ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                    'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
                   ->get();

        return $insumos;        

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

    public static function reduceInsumo($insumo, $cantidad){

        if( Inventario::where('insumo', $insumo)->first() ){

            $existencia = Inventario::where('insumo', $insumo)->value('existencia');
            $existencia -= $cantidad;

            Inventario::where('insumo' , $insumo)->update(['existencia' => $existencia]);
        }
    }

    public static function validaExist($insumos){

        $invalidos = [];

        foreach ($insumos as $insumo) {
            if( !Inventario::where('insumo' , $insumo['id'])->first() ||
                Inventario::where('insumo' , $insumo['id'])->value('existencia') < $insumo['despachado'])
                array_push($invalidos, $insumo['id']);
        }

        return $invalidos;
    }

    public static function validaModificacion($insumos){

        $invalidos = [];

        foreach ($insumos as $insumo){            
            
            $existencia = Inventario::where('insumo' , $insumo['id'])->value('existencia');
                
            if( ($existencia - $insumo['originalC'] + $insumo['modificarC'] ) < 0 )
                array_push($invalidos, $insumo['index']);
            
        }

        return $invalidos;
    }
}
