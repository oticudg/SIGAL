<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventario; 
use App\Insumo;
use App\Entrada;
use App\Insumos_entrada;
use App\Inventario_operacione;
use App\Deposito;

class inventarioController extends Controller
{	
    private $menssage = [
       'insumos.required'  =>  'No se han especificado insumos para esta entrada'
    ];

    public function index(){
        return view('inventario/indexInventario');
    }

    public function viewHerramientas(){
        return view('inventario/herramientasInventario');
    }

    public function viewInsumosAlertas(){
        return view('inventario/nivelesInventario');
    }

    public function viewCargaInventario(){
        return view('inventario/herramientasCargaInventario');
    }

    public function viewDetallesCarga(){
        return view('inventario/detallesInventarioCarga');
    }

    public function allInsumos(){

        $deposito = Auth::user()->deposito; 

        return DB::table('insumos')
            ->where('deposito', $deposito)
            ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
            ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
            ->orderBy('inventarios.id', 'desc')
            ->get();
    }

    public function getInsumosAlert(Request $request){

        $deposito = Auth::user()->deposito;         
        $consulta = $request->input('insumo');

        if($consulta != ""){

            return DB::table('insumos')
                        ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                        ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                            'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
                        ->where('deposito', $deposito)
                        ->where('insumos.descripcion', 'like', $consulta.'%')
                        ->orwhere('insumos.codigo', 'like', $consulta.'%')
                        ->take(20)->get();
        }

        return "[]"; 
    }

    public function getInsumosInventario(Request $request){

        $deposito = Auth::user()->deposito;         
        $consulta = $request->input('insumo');

        if($consulta != ""){

            return DB::table('insumos')
                        ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                        ->select('insumos.id','insumos.codigo','insumos.descripcion')
                        ->where('inventarios.deposito', $deposito)
                        ->where(function($query) use ($consulta){
                            $query->where('descripcion', 'like', '%'.$consulta.'%')
                                  ->orwhere('codigo', 'like', '%'.$consulta.'%');
                        })->orderBy('inventarios.id', 'desc')->take(50)->get();
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

            $deposito = Auth::user()->deposito; 
            $insumos = $data['insumos'];

            foreach($insumos as $insumo) {

                Inventario::where('insumo' , $insumo['id'])
                            ->where('deposito', $deposito)
                            ->update([
                                'Cmin' => $insumo['min'],
                                'Cmed' => $insumo['med'] ]);                
            }

            return Response()->json(['status' => 'success', 'menssage' => 
                'Alarmas configuradas exitosamente']);
        }

    }
    
    public function insumosAlert(){

        $deposito = Auth::user()->deposito;

        $registros = Inventario::where('deposito', $deposito)
                                 ->get(['id', 'existencia', 'Cmed', 'Cmin']);
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

    public function carga(Request $request){

        $data     = $request->all(); 
        $usuario  = Auth::user()->id;
        $deposito = Auth::user()->deposito;   

        $validator = Validator::make($data,[
            'insumos'  =>  'required|insumos'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
        else{
            
            $insumos = $data['insumos'];
            //Codigo para la entrada
            $code = $this->generateCode('CI', $deposito);

            Inventario::where('deposito', $deposito)->delete();

            $entrada = Entrada::create([
                        'codigo'   => $code,
                        'type'     => 'cinventario',
                        'usuario'  => $usuario,
                        'deposito' => $deposito
                    ])['id'];

            foreach ($insumos as $insumo){
                
                Insumos_entrada::create([
                    'entrada'   => $entrada,
                    'insumo'    => $insumo['id'],
                    'cantidad'  => $insumo['cantidad'],
                    'type'      => 'cinventario',
                    'deposito'  => $deposito
                ]);

                inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad'], $deposito, 'carga-inventario', $entrada);
            }

            return Response()->json(['status' => 'success', 'menssage' => 
            'Inventario cargado satisfactoriamente', 'codigo' => $code]);
            
        }
    }

    public function allInventarioCargas(){

        $deposito = Auth::user()->deposito;  
        
        return DB::table('entradas')
                    ->where('type', 'cinventario')
                    ->where('deposito', $deposito)
                    ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo',
                        'entradas.id')
                     ->orderBy('entradas.id', 'desc')->get();
    }

    public function getCarga($id){

        $deposito = Auth::user()->deposito;  
        
        $entrada = Entrada::where('id',$id)
                            ->where('deposito', $deposito)
                            ->where('type', 'cinventario')
                            ->first();
        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta carga de inventario no existe']);            
        }
        else{

            $entrada = DB::table('entradas')->where('entradas.id',$id)
                ->join('users', 'entradas.usuario' , '=', 'users.id' )
                ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                    DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                    'users.email as usuario',  'entradas.id')
                ->first();

            $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                ->get(); 

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public static function almacenaInsumo($insumo, $cantidad, $deposito, $type, $referencia){

    	$inventario = Inventario::where('insumo',$insumo) 
                      ->where('deposito', $deposito)
                      ->first();

        if( $inventario ){

    		$existencia = Inventario::where('insumo', $insumo)
                                      ->where('deposito', $deposito)
                                      ->value('existencia');

            Inventario_operacione::create([
                    'insumo'     => $insumo,
                    'type'       => $type,
                    'referencia' => $referencia,
                    'existencia' => $existencia
            ]);
                
    		$existencia += $cantidad;

    		Inventario::where('insumo' , $insumo)
                        ->where('deposito', $deposito)
                        ->update(['existencia' => $existencia]);
    	}
    	else{

    		Inventario::create([
    			'insumo'     => $insumo,
    			'existencia' => $cantidad,
                'deposito'   => $deposito
    		]);
    	}
    }

    public static function reduceInsumo($insumo, $cantidad, $deposito, $type, $referencia){

        $inventario = Inventario::where('insumo', $insumo)
                                  ->where('deposito', $deposito)
                                  ->first();

        if( $inventario ){

            $existencia = Inventario::where('insumo', $insumo)
                                      ->where('deposito', $deposito)
                                      ->value('existencia');

            Inventario_operacione::create([
                    'insumo'     => $insumo,
                    'type'       => $type,
                    'referencia' => $referencia,
                    'existencia' => $existencia
            ]);
                
            $existencia -= $cantidad;

            Inventario::where('insumo' , $insumo)
                        ->where('deposito', $deposito)
                        ->update(['existencia' => $existencia]);
        }
    }

    public static function validaExist($insumos, $deposito){

        $invalidos = [];

        foreach ($insumos as $insumo){

            $inventario = Inventario::where('insumo' , $insumo['id'])
                          ->where('deposito', $deposito)
                          ->first();

            $existencia = Inventario::where('insumo' , $insumo['id'])
                          ->where('deposito', $deposito)
                          ->value('existencia'); 

            if( !$inventario || $existencia < $insumo['despachado'])
                array_push($invalidos, $insumo['id']);
        }

        return $invalidos;
    }

    public static function validaModifiEntrada($insumos){

        $deposito = Auth::user()->deposito;
        $invalidos = [];

        foreach ($insumos as $insumo){            
            
            $existencia = Inventario::where('insumo' , $insumo['id'])
                                    ->where('deposito', $deposito)
                                    ->value('existencia');
                
            if( ($existencia - $insumo['originalC'] + $insumo['modificarC'] ) < 0 )
                array_push($invalidos, $insumo['index']);
            
        }

        return $invalidos;
    }

    public static function validaModifiSalida($insumos){

        $deposito = Auth::user()->deposito;
        $invalidos = [];

        foreach ($insumos as $insumo){            
            
            $existencia = Inventario::where('insumo' , $insumo['id'])
                                    ->where('deposito', $deposito)
                                    ->value('existencia');
                
            if( ($existencia + $insumo['originalD'] - $insumo['modificarD']) < 0 )
                array_push($invalidos, $insumo['index']);
            
        }

        return $invalidos;
    }

    /*Funcion que genera codigos para las carga de
     * inventario segun un deposito que se pase y un 
     * prefijo
     */  
    private function generateCode($prefix,$deposito){

        //Obtiene Codigo del deposito
        $depCode = Deposito::where('id' , $deposito)->value('codigo');
        
        return strtoupper( $depCode .'-'.$prefix.str_random(6) );
    }

}
