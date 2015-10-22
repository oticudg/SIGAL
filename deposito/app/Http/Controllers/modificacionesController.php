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
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
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
            'insumos'  => 'insumos_validate'
        ]);
        
        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
        else{

            $provedor  = $data['provedor'] != '' ? $data['provedor'] : NULL;
            $orden     = $data['orden']    != '' ? $data['orden'] : NULL;
            $idEntrada = $data['entrada'];
            $insumos   = [];

            foreach ($data['insumos'] as $insumo) {
                if( isset($insumo['cantidad']) ){

                    $originalC = Insumos_entrada::where('entrada', $idEntrada)->
                        where('insumo', $insumo['id'])->value('cantidad');

                    array_push($insumos, ['id' => $insumo['id'], 'originalC' => $originalC, 
                        'modificarC' => $insumo['cantidad']]);
                }
            }

            if( $orden == NULL && empty( $insumos )){
                return Response()->json(['status' => 'danger', 'menssage' => 'No se han hecho modificaciones']);       
            }
                
            if( empty($provedor) ){ 

                $entradaActual      = Entrada::where('id',$idEntrada)->value('provedor');
                $entradaAmodificar  = Entrada::where('orden', $orden)->value('provedor');
                    
                if( !empty($entradaAmodificar) && $entradaActual != $entradaAmodificar)
                    return Response()->json(['status' => 'danger', 'menssage' => 
                        'El proveedor de esta orden de compra no coincide']);               
            }
            else{
                
                $entradaActual  = Entrada::where('orden', $orden)->value('provedor');

                if( !empty($entradaActual) && $provedor != $entradaActual)
                     return Response()->json(['status' => 'danger', 'menssage' => 
                        'El proveedor de esta orden de compra no coincide']);
            }


            $originalP = Entrada::where('id', $idEntrada)->value('provedor');
            $originalO = Entrada::where('id', $idEntrada)->value('orden');

            Entradas_modificada::create([
                'entrada'   => $idEntrada,
                'Oprovedor' => $originalP,
                'Mprovedor' => $provedor,
                'Oorden'    => $originalO,
                'Morden'    => $orden,
                'usuario'   => Auth::user()->id    
            ]);
            
            $provedor = $provedor == NULL ? $originalP:$provedor;
            $orden    = $orden == NULL ? $originalO:$orden;

            Entrada::where('id', $idEntrada)->update([
                'orden'     => $orden,
                'provedor'  => $provedor
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Modificacion registrada']);   
        }
    }
    
}
