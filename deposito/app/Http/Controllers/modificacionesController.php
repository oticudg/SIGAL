<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entradas_modificada;
use App\Insumos_emodificado;
use App\Entrada;
use App\Insumos_entrada;

class modificacionesController extends Controller
{
    public function index(){
        return view('modificaciones/indexModificaciones');
    }

    public function detallesEntrada(){

        return view('modificaciones/detallesEntradaModificada');
    }

    public function viewRegistrar(){

        return view('modificaciones/registrarModificacion');
    }


    public function allEntradas(){

        return DB::table('entradas_modificadas')
                ->join('entradas', 'entradas_modificadas.entrada', '=', 'entradas.id')
                ->select(DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                    'entradas.codigo as codigo', 'entradas_modificadas.id as id')
                ->orderBy('entradas_modificadas.id', 'desc')->get();
    }

    public function getEntrada($id){

        $entrada = Entradas_modificada::where('id',$id)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta entrada no existe']);            
        }
        else{ 

            $modificacion = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                            ->join('entradas', 'entradas_modificadas.entrada', '=', 'entradas.id')
                            ->join('users', 'entradas_modificadas.usuario' , '=', 'users.id' )
                            ->select(DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                                    DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%H:%i:%s") as hora'),  
                                    'users.email as usuario', 'entradas.codigo as codigo')
                            ->first();

            if(Entradas_modificada::where('id', $id)->value('Mprovedor') != NULL){

                $entrada = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                              ->join('provedores', 'entradas_modificadas.Oprovedor', '=', 'provedores.id')
                              ->join('provedores as Mprovedores', 'entradas_modificadas.Mprovedor', '=', 'Mprovedores.id')
                              ->select('provedores.nombre as provedor', 'Mprovedores.nombre as Mprovedor', 
                                'entradas_modificadas.Oorden as orden', 'entradas_modificadas.Morden as Morden')   
                              ->first();
            }
            else{

                $entrada = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                              ->join('provedores', 'entradas_modificadas.Oprovedor', '=', 'provedores.id')
                              ->select('provedores.nombre as provedor','entradas_modificadas.Oorden as orden', 
                                'entradas_modificadas.Morden as Morden')   
                              ->first();
            }


            $insumos  = Insumos_emodificado::where('entrada',$id)->get();

            if( $insumos->isEmpty() ){
                $insumos = NULL;
            }
            else{

                $insumos = DB::table('insumos_emodificados')->where('insumos_emodificados.entrada',$id)
                          ->join('insumos', 'insumos_emodificados.insumo', '=', 'insumos.id')
                          ->select('insumos.codigo as codigo', 'insumos.descripcion as descripcion', 
                            'insumos_emodificados.Ocantidad as cantidad', 
                            'insumos_emodificados.Mcantidad as modificacion')   
                          ->get();
            }   

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos, 
                    'modificacion' => $modificacion]);
        }
    }

    public function registrar(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'entrada'  => 'required',
            'orden'    => 'diff_orden:entrada',
            'provedor' => 'diff_provedor:entrada',
            'insumos'  => 'insumos_validate|one_insumo:entrada'
        ]);
        
        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
        else{

            $provedor  = $data['provedor'] != " " ? $data['provedor'] : NULL;
            $orden     = $data['orden']    != " " ? $data['orden'] : NULL;
            $originalE = Entrada::where('id', $data['entrada'])->first(['id','provedor','orden']);
            $insumos   = [];

            foreach($data['insumos'] as $insumo) {
                if( isset($insumo['cantidad']) ){

                    $originalI = Insumos_entrada::where('id', $insumo['id'])->first(['insumo','cantidad']);

                    array_push($insumos, ['id' => $originalI['insumo'], 'originalC' => $originalI['cantidad'], 
                        'modificarC' => $insumo['cantidad'], 'index' => $insumo['id']]);
                }
            }
            
            if( empty( $orden ) && empty( $insumos ) ){
                return Response()->json(['status' => 'danger', 'menssage' => 'No se han hecho modificaciones']);       
            }  
            else if( ($insumosInvalidos = inventarioController::validaModificacion($insumos)) != [] ){
                return Response()->json(['status' => 'unexist', 'data' => $insumosInvalidos]);
            }

            if( empty($provedor) ){ 

                $provedorModificar  = Entrada::where('orden', $orden)->value('provedor');
                    
                if( !empty($provedorModificar) && $originalE->provedor != $provedorModificar)
                    return Response()->json(['status' => 'danger', 'menssage' => 
                        'El proveedor de esta orden de compra no coincide']);               
            }
            else{
                
                $provedorModificar = Entrada::where('orden', $orden)->value('provedor');

                if( !empty($provedorModificar) && $provedor != $provedorModificar)
                     return Response()->json(['status' => 'danger', 'menssage' => 
                        'El proveedor de esta orden de compra no coincide']);
            }

            $entrada = Entradas_modificada::create([
                        'entrada'   => $originalE->id,
                        'Oprovedor' => $originalE->provedor,
                        'Mprovedor' => $provedor,
                        'Oorden'    => $originalE->orden,
                        'Morden'    => $orden,
                        'usuario'   => Auth::user()->id    
                    ])['id'];

            $provedor = $provedor == NULL ? $originalE->provedor : $provedor;
            $orden    = $orden    == NULL ? $originalE->orden :$orden;

            Entrada::where('id', $originalE->id)->update([
                'orden'     => $orden,
                'provedor'  => $provedor
            ]);
            
            foreach ($insumos as $insumo){           

                if( $insumo['modificarC'] == 0){
                    inventarioController::reduceInsumo($insumo['id'], $insumo['originalC']);
                    Insumos_entrada::where('entrada',$originalE->id)->
                        where('insumo', $insumo['id'])->delete();
                }
                else{
                    inventarioController::reduceInsumo($insumo['id'], $insumo['originalC']);
                    inventarioController::almacenaInsumo($insumo['id'], $insumo['modificarC']);
                    Insumos_entrada::where('entrada',$originalE->id)->
                        where('insumo', $insumo['id'])->update([
                            'cantidad' => $insumo['modificarC']
                        ]);
                }

                Insumos_emodificado::create([
                    'entrada'   => $entrada,
                    'insumo'    => $insumo['id'],
                    'Ocantidad' => $insumo['originalC'],
                    'Mcantidad' => $insumo['modificarC']
                ]);
            }
            
            return Response()->json(['status' => 'success', 'menssage' => 'Modificacion registrada']);   
        }
    }
    
}
