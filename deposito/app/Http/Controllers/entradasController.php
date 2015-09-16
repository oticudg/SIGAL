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
        'codigo.required'    =>  'Especifique un numero de orden de compra',
        'provedor.required'  =>  'Seleccione un proveedor', 
        'insumos.required'   =>  'No se han especificado insumos para esta entrada',
        'insumos.insumos'    =>  'Valores de insumos no validos',
        'codigo.unique'     =>  'Este numero de orden de compra ya ha sido registrado'
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
                'insumos.codigo','insumos.descripcion','insumos_entradas.cantidad')
            ->get();
    }

    public function allEntradas(){

        return DB::table('entradas')
            ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
            ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo',
                'provedores.nombre as provedor', 'entradas.id')
            ->get();
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
                    'provedores.nombre as provedor', 'users.email as usuario')
                ->first();

           $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public function registrar(Request $request){
        
        $data = $request->all();

        $validator = Validator::make($data,[
            'codigo'   =>  'required|unique:entradas',
            'provedor' =>  'required',
            'insumos'  =>  'required|insumos'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
        else{

            $insumos = $data['insumos'];

            $entrada = Entrada::create([
                        'codigo'   => $data['codigo'],
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
                'Registro completado satisfactoriamente']);
        }
    }
}
