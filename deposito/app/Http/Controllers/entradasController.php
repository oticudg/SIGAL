<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entrada;
use App\Insumos_entrada;

class entradasController extends Controller
{   
    private $menssage = [
        'orden.required'             =>   'Especifique un numero de orden de compra',
        'provedor.required'           =>  'Seleccione un proveedor', 
        'insumos.required'            =>  'No se han especificado insumos para esta entrada',
        'insumos.insumos'             =>  'Valores de insumos no validos'
    ];

    public function index(){
        return view('entradas/indexEntradas');
    }

    public function viewRegistrar(){  
        return view('entradas/registrarEntrada');
    }

    public function detalles(){
        return view('entradas/detallesEntrada');
    }

    public function allInsumos(){

        return DB::table('insumos_entradas')
            ->join('entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
            ->join('insumos', 'insumos.id' , '=', 'insumos_entradas.insumo')
            ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo as entrada',
                'entradas.orden','entradas.id as entradaId','insumos.codigo',
                'insumos.descripcion','insumos_entradas.cantidad')
            ->orderBy('insumos_entradas.id', 'desc')->get();
    }

    public function allEntradas(){

        return DB::table('entradas')
            ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
            ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo',
                'entradas.orden','provedores.nombre as provedor', 'entradas.id')
             ->orderBy('entradas.id', 'desc')->get();
    }

    public function getEntrada($id){

        $entrada = Entrada::where('id',$id)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
        }
        else{

           $entrada = DB::table('entradas')->where('entradas.id',$id)
                ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                ->join('users', 'entradas.usuario' , '=', 'users.id' )
                ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                    DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                    'entradas.orden', 'provedores.nombre as provedor', 'users.email as usuario')
                ->first();

           $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public function getEntradaCodigo($code){

        $entrada = Entrada::where('codigo',$code)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
        }
        else{

           $entrada = DB::table('entradas')->where('entradas.codigo',$code)
                ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                ->select('entradas.codigo','entradas.orden','entradas.id', 
                    'provedores.nombre as provedor')
                ->first();

           $insumos = DB::table('entradas')->where('entradas.codigo', $code)
                ->join('insumos_entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad', 'insumos.id')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public function getOrden($number){

        $entrada = Entrada::where('orden',$number)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Orden no existe']);   
        }
        else{

            $orden = DB::table('entradas')->where('entradas.orden', $number)
                     ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                     ->select('entradas.orden as numero', 'provedores.nombre as provedor')
                     ->first();  

            $entradas = Entrada::where('entradas.orden',$number)->lists('id');
            
            $insumos  = DB::table('insumos_entradas')->whereIn('entrada', $entradas)
                        ->join('entradas', 'insumos_entradas.entrada', '=', 'entradas.id')
                        ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                        ->select('entradas.codigo as entrada','insumos.codigo as codigo',
                            DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                            'entradas.id as entradaId','insumos.descripcion as descripcion',
                            'insumos_entradas.cantidad as cantidad')
                        ->orderBy('insumos_entradas.id', 'desc')->get();

            return Response()->json(['status' => 'success', 'orden' => $orden, 'insumos' => $insumos]);
            
        }
    }

    public function registrar(Request $request){
        
        $data = $request->all();

        $validator = Validator::make($data,[
            'orden'   =>  'required|',
            'provedor' =>  'required|equal_provedor:orden',
            'insumos'  =>  'required|insumos'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
        else{

            $insumos = $data['insumos'];
            $code =  str_random(8);

            $entrada = Entrada::create([
                        'codigo'   => $code,
                        'orden'    => $data['orden'],
                        'provedor' => $data['provedor'],
                        'usuario'  => Auth::user()->id
                    ])['id'];

            foreach ($insumos as $insumo) {
                
                Insumos_entrada::create([
                    'entrada'   => $entrada,
                    'insumo'    => $insumo['id'],
                    'cantidad'  => $insumo['cantidad']
                ]);

                inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad']);
            }

            return Response()->json(['status' => 'success', 'menssage' => 
                'Entrada completada satisfactoriamente', 'codigo' => $code]);
        }
    }
}
