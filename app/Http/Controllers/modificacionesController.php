<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entradas_modificada;
use App\Salidas_modificada;
use App\Insumos_emodificado;
use App\Insumos_smodificado;
use App\Entrada;
use App\Salida;
use App\Insumos_entrada;
use App\Insumos_salida;

class modificacionesController extends Controller
{
    public function indexEntradas(){
        return view('modificaciones/indexEntradas');
    }

    public function indexSalidas(){
        return view('modificaciones/indexSalidas');
    }

    public function detallesEntrada(){

        return view('modificaciones/detallesEntrada');
    }

    public function detallesSalida(){

        return view('modificaciones/detallesSalida');
    }

    public function viewRegEntrada(){

        return view('modificaciones/registrarEntrada');
    }

    public function viewRegSalida(){

        return view('modificaciones/registrarSalida');

    }


    public function allEntradas(){

        $deposito = Auth::user()->deposito;

        return DB::table('entradas_modificadas')
                ->where('entradas_modificadas.deposito', $deposito)
                ->join('entradas', 'entradas_modificadas.entrada', '=', 'entradas.id')
                ->select(DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                    'entradas.codigo as codigo', 'entradas_modificadas.id as id')
                ->orderBy('entradas_modificadas.id', 'desc')->get();
    }

    public function allSalidas(){

        $deposito = Auth::user()->deposito;

        return DB::table('salidas_modificadas')
                ->where('salidas_modificadas.deposito', $deposito)
                ->join('salidas', 'salidas_modificadas.salida', '=', 'salidas.id')
                ->select(DB::raw('DATE_FORMAT(salidas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                    'salidas.codigo as codigo', 'salidas_modificadas.id as id')
                ->orderBy('salidas_modificadas.id', 'desc')->get();
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

    public function getSalida($id){

        $salida = Salidas_modificada::where('id',$id)->first();

        if(!$salida){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta entrada no existe']);
        }
        else{

            $modificacion = DB::table('salidas_modificadas')->where('salidas_modificadas.id',$id)
                            ->join('salidas', 'salidas_modificadas.salida', '=', 'salidas.id')
                            ->join('users', 'salidas_modificadas.usuario' , '=', 'users.id' )
                            ->select(DB::raw('DATE_FORMAT(salidas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                                    DB::raw('DATE_FORMAT(salidas_modificadas.created_at, "%H:%i:%s") as hora'),
                                    'users.email as usuario', 'salidas.codigo as codigo')
                            ->first();

            if(Salidas_modificada::where('id', $id)->value('Mdepartamento') != NULL){

                $salida = DB::table('salidas_modificadas')->where('salidas_modificadas.id',$id)
                              ->join('departamentos', 'salidas_modificadas.Odepartamento', '=', 'departamentos.id')
                              ->join('departamentos as Mdepartamento', 'salidas_modificadas.Mdepartamento', '=', 'Mdepartamento.id')
                              ->select('departamentos.nombre as departamento', 'Mdepartamento.nombre as Mdepartamento')
                              ->first();
            }
            else{

                $salida = DB::table('salidas_modificadas')->where('salidas_modificadas.id',$id)
                              ->join('departamentos', 'salidas_modificadas.Odepartamento', '=', 'departamentos.id')
                              ->select('departamentos.nombre as departamento')
                              ->first();
            }


            $insumos  = Insumos_smodificado::where('salida',$id)->get();

            if( $insumos->isEmpty() ){
                $insumos = NULL;
            }
            else{

                $insumos = DB::table('insumos_smodificados')->where('insumos_smodificados.salida',$id)
                          ->join('insumos', 'insumos_smodificados.insumo', '=', 'insumos.id')
                          ->select('insumos.codigo as codigo', 'insumos.descripcion as descripcion',
                            'insumos_smodificados.Osolicitado','insumos_smodificados.Msolicitado',
                            'insumos_smodificados.Odespachado', 'insumos_smodificados.Mdespachado')
                          ->get();
            }

            return Response()->json(['status' => 'success', 'salida' => $salida , 'insumos' => $insumos,
                    'modificacion' => $modificacion]);
        }
    }

    public function registrarEntrada(Request $request){

        $data = $request->all();
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'entrada'  => 'required',
            'orden'    => 'diff_orden:entrada',
            'provedor' => 'diff_provedor:entrada',
            'insumos'  => 'insumos_validate_e|one_insumo_entrada:entrada'
        ]);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            $provedor  = $data['provedor'] != " " ? $data['provedor'] : NULL;
            $orden     = $data['orden']    != " " ? $data['orden'] : NULL;
            $originalE = Entrada::where('id', $data['entrada'])->first(['id','provedor','orden']);
            $insumos   = [];
            //Obtiene el numero de insumos en la entrada original
            $insumoC   = Insumos_entrada::where('entrada', $data['entrada'])->count();

            foreach($data['insumos'] as $insumo) {
                if( isset($insumo['cantidad']) ){

                    $originalI = Insumos_entrada::where('id', $insumo['id'])->first(['insumo','cantidad']);

                    array_push($insumos, ['id' => $originalI['insumo'], 'originalC' => $originalI['cantidad'],
                        'modificarC' => $insumo['cantidad'], 'index' => $insumo['id']]);
                }
            }

            if( empty( $orden ) && empty( $insumos ) && empty($provedor) ){
                return Response()->json(['status' => 'danger', 'menssage' => 'No se han hecho modificaciones']);
            }
            else if( ($insumosInvalidos = inventarioController::validaModifiEntrada($insumos)) != [] ){
                return Response()->json(['status' => 'unexist', 'data' => $insumosInvalidos]);
            }

            //Valida que no se intente eliminar todos los insumos de la entrada
            if( count($insumos) == $insumoC){
              foreach ($insumos as $insumo) {
                if($insumo['modificarC'] != 0){
                  $status = true;
                  break;
                }
              }

              if( !isset($status) ){
                return Response()->json(['status' => 'danger', 'menssage' => 'No es posible eliminar todos los insumos de esta entrada']);
              }
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
                        'usuario'   => Auth::user()->id,
                        'deposito'  => $deposito
                    ])['id'];

            $provedor = $provedor == NULL ? $originalE->provedor : $provedor;
            $orden    = $orden    == NULL ? $originalE->orden :$orden;

            Entrada::where('id', $originalE->id)->update([
                'orden'     => $orden,
                'provedor'  => $provedor
            ]);

            foreach ($insumos as $insumo){

                if( $insumo['modificarC'] == 0){
                    inventarioController::reduceInsumo($insumo['id'], $insumo['originalC'],$deposito, 'modiEntrada', $entrada);
                    Insumos_entrada::where('entrada',$originalE->id)
                                    ->where('insumo', $insumo['id'])->delete();
                }
                else{
                    inventarioController::reduceInsumo($insumo['id'], $insumo['originalC'], $deposito, 'modiEntrada', $entrada);
                    inventarioController::almacenaInsumo($insumo['id'], $insumo['modificarC'], $deposito, 'modiEntrada', $entrada);
                    Insumos_entrada::where('entrada',$originalE->id)->
                        where('insumo', $insumo['id'])->update([
                            'cantidad' => $insumo['modificarC']
                        ]);
                }

                Insumos_emodificado::create([
                    'entrada'   => $entrada,
                    'insumo'    => $insumo['id'],
                    'Ocantidad' => $insumo['originalC'],
                    'Mcantidad' => $insumo['modificarC'],
                    'deposito'  => $deposito
                ]);
            }

            return Response()->json(['status' => 'success', 'menssage' => 'Modificacion registrada']);
        }
    }

    public function registrarSalida(Request $request){

        $data = $request->all();
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'salida'        => 'required',
            'departamento'  => 'diff_departamento:salida',
            'insumos'       => 'insumos_validate_s|one_insumo_salida:salida'
        ]);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            $departamento  = $data['departamento'] != " " ? $data['departamento'] : NULL;
            $originalS = Salida::where('id', $data['salida'])->first(['id','departamento']);
            $insumos   = [];
            //Obtiene el numero de insumos en la salida original
            $insumosC  = Insumos_salida::where('salida', $data['salida'])->count();

            foreach($data['insumos'] as $insumo) {
                if( isset($insumo['despachado']) ){

                    $originalI = Insumos_salida::where('id', $insumo['id'])->first(['insumo', 'solicitado', 'despachado']);

                    $insumo['solicitado'] = isset($insumo['solicitado']) ? $insumo['solicitado'] : NULL;

                    array_push($insumos, ['id' => $originalI['insumo'], 'originalS' => $originalI['solicitado'],
                        'originalD' => $originalI['despachado'], 'modificarS' => $insumo['solicitado'],
                        'modificarD' => $insumo['despachado'],'index' => $insumo['id']]);
                }
            }

            if( empty( $departamento ) && empty( $insumos ) ){
                return Response()->json(['status' => 'danger', 'menssage' => 'No se han hecho modificaciones']);
            }
            else if( ($insumosInvalidos = inventarioController::validaModifiSalida($insumos)) != [] ){
                return Response()->json(['status' => 'unexist', 'data' => $insumosInvalidos]);
            }

            //Valida que no se intente eliminar todos los insumos de la salida
            if( count($insumos) == $insumosC ){
              foreach ($insumos as $insumo) {
                if($insumo['modificarD'] != 0){
                  $status = true;
                  break;
                }
              }

              if( !isset($status) ){
                return Response()->json(['status' => 'danger', 'menssage' => 'No es posible eliminar todos los insumos de esta salida']);
              }
            }

            $salida = Salidas_modificada::create([
                        'salida'        => $originalS->id,
                        'Odepartamento' => $originalS->departamento,
                        'Mdepartamento' => $departamento,
                        'usuario'       => Auth::user()->id,
                        'deposito'      => $deposito
                    ])['id'];

            if( $departamento != NULL ){

                Salida::where('id', $originalS->id)->update([
                    'departamento' => $departamento,
                ]);
            }

            foreach ($insumos as $insumo){

                if( $insumo['modificarD'] == 0){
                    inventarioController::almacenaInsumo($insumo['id'], $insumo['originalD'], $deposito, 'modiSalida', $salida);
                    Insumos_salida::where('salida',$originalS->id)->
                        where('insumo', $insumo['id'])->delete();
                }
                else{

                    inventarioController::almacenaInsumo($insumo['id'], $insumo['originalD'],$deposito, 'modiSalida', $salida);
                    inventarioController::reduceInsumo($insumo['id'], $insumo['modificarD'], $deposito, 'modiSalida', $salida);

                    $solicitado = $insumo['modificarS'] == NULL ? $insumo['originalS'] : $insumo['modificarS'];

                    Insumos_salida::where('salida',$originalS->id)->where('insumo', $insumo['id'])
                        ->update([
                            'despachado' => $insumo['modificarD'],
                            'solicitado' => $solicitado
                        ]);
                }

                Insumos_smodificado::create([
                    'salida'      => $salida,
                    'insumo'      => $insumo['id'],
                    'Odespachado' => $insumo['originalD'],
                    'Mdespachado' => $insumo['modificarD'],
                    'Osolicitado' => $insumo['originalS'],
                    'Msolicitado' => $insumo['modificarS'],
                    'deposito'    => $deposito
                ]);
            }

            return Response()->json(['status' => 'success', 'menssage' => 'Modificacion registrada']);
        }
    }
}
