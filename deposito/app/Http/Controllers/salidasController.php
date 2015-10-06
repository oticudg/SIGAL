<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Salida;
use App\Insumos_salida;

class salidasController extends Controller
{   
    private $menssage = [
        'departamento.required'   =>  'Seleccione un Servicio', 
        'insumos.required'        =>  'No se han especificado insumos para esta salida',
        'insumos.insumos_salida'  =>  'Valores de insumos no validos',
    ];

	public function index(){
    	return view('salidas/indexSalidas');
    }

    public function viewRegistrar(){
        return view('salidas/registrarSalida');
    }

    public function detalles(){
        return view('salidas/detallesSalida');
    }

    public function allInsumos(){

        return DB::table('insumos_salidas')
            ->join('salidas', 'salidas.id', '=', 'insumos_salidas.salida')
            ->join('insumos', 'insumos.id' , '=', 'insumos_salidas.insumo')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo as salida',
                'insumos.codigo','insumos.descripcion','insumos_salidas.solicitado', 
                'insumos_salidas.despachado')
            ->orderBy('insumos_salidas.id', 'desc')->get();
    }

    public function allSalidas(){

        return DB::table('salidas')
            ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
                'departamentos.nombre as departamento', 'salidas.id')
            ->orderBy('salidas.id', 'desc')->get();
    }

    public function getSalida($id){

        $salida = Salida::where('id',$id)->first();

        if(!$salida){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Salida no existe']);            
        }
        else{

           $salida = DB::table('salidas')->where('salidas.id',$id)
                ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
                ->join('users', 'salidas.usuario' , '=', 'users.id' )
                ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),
                    DB::raw('DATE_FORMAT(salidas.created_at, "%H:%i:%s") as hora'), 'salidas.codigo',
                    'departamentos.nombre as departamento', 'users.email as usuario')
                ->first();

           $insumos = DB::table('insumos_salidas')->where('insumos_salidas.salida', $id)
                ->join('insumos', 'insumos_salidas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_salidas.solicitado',
                	'insumos_salidas.despachado')
                ->get();

            return Response()->json(['status' => 'success', 'salida' => $salida , 'insumos' => $insumos]);
        }
    }

    public function registrar(Request $request){
        
        $data = $request->all();

        $validator = Validator::make($data,[
            'departamento' =>  'required',
            'insumos'      =>  'required|insumos_salida'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
        else{
        
            $insumos = $data['insumos'];
            $insumosInvalidos = inventarioController::validaExist($insumos);

            if($insumosInvalidos){

                return Response()->json(['status' => 'unexist', 'data' => $insumosInvalidos]);
            }
            else{
                
                $code =  str_random(8);

                $salida = Salida::create([
                            'codigo'       => $code,
                            'departamento' => $data['departamento'],
                            'usuario'      => Auth::user()->id
                        ])['id'];
                
                foreach ($insumos as $insumo) {

                    Insumos_salida::create([
                        'salida'      => $salida,
                        'insumo'      => $insumo['id'],
                        'solicitado'  => $insumo['solicitado'],
                        'despachado'  => $insumo['despachado']
                    ]);

                    inventarioController::reduceInsumo($insumo['id'], $insumo['despachado']);
    
                }

                return Response()->json(['status' => 'success', 'menssage' => 
                    'Salida completada satisfactoriamente', 'codigo' => $code]);
            }
        }
    }
}
