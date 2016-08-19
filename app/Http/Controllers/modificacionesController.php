<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modification;
use App\Documento;
use App\Provedore;
use App\Departamento;
use App\Entrada;
use App\Salida;
use App\Deposito;

class modificacionesController extends Controller
{
    private $menssage = [
      'update_documento.document_not_equal' => 'Seleccione otro documento para realizar la modificación'
    ];

    public function index(){
        return view('inventario.modificaciones.index');
    }

    public function viewRegistrar(){
        return view('inventario.modificaciones.registrar');
    }

    public function getMovimiento(Request $request){
      $code = $request->get('code');
      $deposito = Auth::user()->deposito;

      //Si el codigo no es espesificado devuelve un mensaje de error.
      if(empty($code)){
        return Response()->json(['status' => 'danger', 'message' => 'Ingrese un codigo']);
      }

      //Asume que el codigo pertenece a una entrada.
      $movimiento = Entrada::where('codigo', 'like', '%-'.$code)->where('deposito', $deposito)->first(['id', 'documento']);
      $type = 'entradas';

      //Si el codigo no pertenece a una entrada asume que es una salida.
      if(!$movimiento){
        $movimiento = Salida::where('codigo', 'like', '%-'.$code)->where('deposito', $deposito)->first(['id', 'documento']);
        $type = 'salidas';
      }

      //Si el movimento no es encontrado devulve un mensaje de error.
      if(!$movimiento){
        return Response()->json(['status' => 'danger', 'message' => 'Movimiento no encontrado']);
      }

      //Obtiene el documento asociado al movimiento.
      $documento = Documento::where('id', $movimiento->documento)->first(['tipo', 'id']);

      //Obtiene el movimento.
      if($type == 'entradas'){

        //Campos a consultar
        $select = [
          "entradas.codigo",
          "entradas.id",
          "documentos.abreviatura",
          "documentos.id as documentoId",
          "documentos.nombre as concepto",
          "documentos.naturaleza as type",
          DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha')
        ];

        //Consulta base para la entrada
        $query = DB::table('entradas')->where('entradas.id',$movimiento->id)
             ->join('documentos','entradas.documento', '=','documentos.id')
             ->select($select);

        /**
          *Une table para buscar el nombre del tercero, segun el
          *tipo del documento de la entrada y lo selecciona.
          */
        switch ($documento->tipo){

          case 'servicio':
            $query->join('departamentos', 'entradas.tercero', '=', 'departamentos.id')
                ->addSelect('departamentos.nombre as tercero');
          break;

          case 'proveedor':
            $query->join('provedores', 'entradas.tercero', '=', 'provedores.id')
                ->addSelect('provedores.nombre as tercero');
          break;

          case 'deposito':
            $query->join('depositos', 'entradas.tercero', '=', 'depositos.id')
                ->addSelect('depositos.nombre as tercero');
          break;

          case 'interno':
            $query->join('depositos', 'entradas.tercero', '=', 'depositos.id')
                ->addSelect('depositos.nombre as tercero');
          break;

        }

        //Realiza la consulta
        $movimiento = $query->first();
      }
      else{

        //Campos a consultar
        $select = [
          "salidas.codigo",
          "salidas.id",
          "documentos.abreviatura",
          "documentos.id as documentoId",
          "documentos.nombre as concepto",
          "documentos.naturaleza as type",
          DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha')
        ];

        //Consulta base para la salidas
        $query = DB::table('salidas')->where('salidas.id',$movimiento->id)
             ->join('documentos','salidas.documento', '=','documentos.id')
             ->select($select);

        /**
          *Une table para buscar el nombre del tercero, segun el
          *tipo del documento de la salida y lo selecciona.
          */
        switch ($documento->tipo){

          case 'servicio':
            $query->join('departamentos', 'salidas.tercero', '=', 'departamentos.id')
                ->addSelect('departamentos.nombre as tercero');
          break;

          case 'proveedor':
            $query->join('provedores', 'salidas.tercero', '=', 'provedores.id')
                ->addSelect('provedores.nombre as tercero');
          break;

          case 'deposito':
            $query->join('depositos', 'salidas.tercero', '=', 'depositos.id')
                ->addSelect('depositos.nombre as tercero');
          break;

          case 'interno':
            $query->join('depositos', 'salidas.tercero', '=', 'depositos.id')
                ->addSelect('depositos.nombre as tercero');
          break;

        }

        //Realiza la consulta
        $movimiento = $query->first();

      }

      return Response()->json(
        [ 'status' => 'success',
          'data' => [
            'type' => $type,
            'tercero' =>   $documento->tipo,
            'documento' => $documento->id,
            'movimiento' => $movimiento,
          ]
        ]);
    }

    public function registrar(Request $request){
      $data  = $request->all();

      $validator = Validator::make($data,[
          'documento'        => 'required|numeric|documento',
          'movimiento'       => 'required|numeric|movimiento:documento',
          'update_documento' => 'numeric|documento|document_not_equal:documento|documento_same_nature:documento',
          'update_tercero'   => 'numeric|'
      ], $this->menssage);

      if($validator->fails()){
          return Response()->json(['status' => 'danger', 'message' => $validator->errors()->first()]);
      }
      else{

        $deposito = Auth::user()->deposito;

        //Obtiene la documento actual asignado al movimiento.
        $ori_documento = Documento::where('id', $data['documento'])->first();
        //Obtiene el docuemnto a modificar.
        $up_documento  = Documento::where('id', $data['update_documento'])->first();

        //Valida si no se han realizado modificaciones.
        if( empty($data['update_documento']) && empty($data['update_tercero']) ){
            return Response()->json(['status' => 'danger', 'message' => 'No se han hecho modificaciones']);
        }

        //Valida si el docuemnto original y el documento a modificar tiene el mismo tipo.
        if( !empty($data['update_documento']) && empty($data['update_tercero'])){

          if( ($up_documento['tipo'] != 'interno') && ($ori_documento['tipo'] != $up_documento['tipo']) ){
            return Response()->json(['status' => 'danger', 'message' => 'Seleccione un tercero para realizar la modificacio.']);
          }
        }

        //Valida que el tercero a modificar, existe en el tipo del documento.
        if( !empty($data['update_tercero'] ) ){

          if(!empty($data['update_documento']) ){
            $tipo = $up_documento['tipo'];
          }
          else{
            $tipo = $ori_documento['tipo'];
          }

          switch ($tipo){
            case 'interno':
              $data['update_tercero'] = $deposito;
              break;

            case 'proveedor':
              if(!Provedore::where('id', $data['update_tercero'])->first()){
                return Response()->json(['status' => 'danger', 'message' => 'Tercero no existe']);
              }
              break;

            case 'servicio':
              if(!Departamento::where('id', $data['update_tercero'])->first()){
                return Response()->json(['status' => 'danger', 'message' => 'Tercero no existe']);
              }
              break;

            case 'deposito':
              if(!Deposito::where('id', $data['update_tercero'])->where('id','!=', $deposito)->first()){
                return Response()->json(['status' => 'danger', 'message' => 'Tercero no existe']);
              }
              break;
          }
        }

        //Localiza el movimiento.
        if($ori_documento['naturaleza'] == 'entrada'){
          $movimiento = Entrada::where('id', $data['movimiento'])->where('documento', $data['documento'])->where('deposito', $deposito)->first();
        }
        else{
          $movimiento = Salida::where('id', $data['movimiento'])->where('documento', $data['documento'])->where('deposito', $deposito)->first();
        }

        //Si el tercero a modificar es el mismo del movimiento original se regresa un mensaje de error.
        if(!empty($data['update_tercero'])){

          if(!empty($data['update_documento'])){
            if($ori_documento['tipo'] == $up_documento['tipo'] && $movimiento['tercero'] == $data['update_tercero'] )
              return Response()->json(['status' => 'danger', 'message' => 'Seleccione un tercero diferente para realizar la modificacion']);
          }
          else if($movimiento['tercero'] == $data['update_tercero']){
            return Response()->json(['status' => 'danger', 'message' => 'Seleccione un tercero diferente para realizar la modificacion']);
          }
       }

        //Se preparan los datos que se llenaran por defecto en cada registro.
        $register = [
          'movimiento' => $data['movimiento'],
          'naturaleza' => $ori_documento['naturaleza'],
          'original_documento' => $data['documento'],
          'original_tercero'   => $movimiento['tercero'],
          'deposito' => $deposito
        ];


        //Si el documento asignado sera modificado se prepara en los datos a registrar.
        if(!empty($data['update_documento'])){
          $register['updated_documento'] = $data['update_documento'];

        }

        //Si el tercero asignado sera modificado se prepara en los datos a registar
        if(!empty($data['update_tercero'])){
          $register['updated_tercero'] = $data['update_tercero'];
        }

        //Almacena el registro.
        Modification::create($register);

        //Se realiza la operacion de modificacion del movimiento.

        $register = [];

        //Modifica el documento si se a establecido modificacion
        if($data['update_documento']){
          $register['documento'] = $up_documento['id'];
        }

        //Modifica el tercero si se a establecido modificacion
        if($data['update_tercero']){
          $register['tercero'] = $data['update_tercero'];
        }

        if($ori_documento['naturaleza'] == 'entrada'){
          Entrada::where('id', $movimiento['id'])->update($register);
        }
        else{
          Salida::where('id', $movimiento['id'])->update($register);
        }

        return Response()->json(['status' => 'success', 'message' => 'Modificacion registrada']);

      }
    }
}
