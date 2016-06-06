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
use App\Deposito;
use App\Documento;

class entradasController extends Controller
{
    private $menssage = [
        'orden.required'    =>  'Especifique un numero de orden de compra',
        'provedor.required' =>  'Seleccione un proveedor',
        'insumos.required'  =>  'No se han especificado insumos para esta entrada'
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

    public function viewSearch(){
        return view('entradas/searchEntradas');
    }


    public function allInsumos($type = NULL){

        $deposito = Auth::user()->deposito;

        return DB::table('insumos_entradas')
                  ->where('insumos_entradas.deposito', $deposito)
                  ->join('entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
                  ->join('insumos', 'insumos.id' , '=', 'insumos_entradas.insumo')
                  ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo as entrada',
                      'entradas.id as entradaId','insumos.codigo',
                      'insumos.descripcion','insumos_entradas.cantidad','insumos_entradas.lote',
                      'insumos_entradas.fechaV')
                  ->orderBy('insumos_entradas.id', 'desc')->get();
    }

    public function allEntradas(){

        $deposito = Auth::user()->deposito;

        $select = [
          DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
          'entradas.codigo',
          'entradas.id',
          'documentos.nombre as concepto',
          'documentos.abreviatura'
        ];

        $servicios = DB::table('entradas')
                      ->join('documentos', 'entradas.documento', '=', 'documentos.id')
                      ->join('departamentos','entradas.tercero', '=', 'departamentos.id')
                      ->where('entradas.deposito', $deposito)
                      ->where('documentos.tipo', 'servicio')
                      ->where('documentos.naturaleza', 'entrada')
                      ->select($select)
                      ->addSelect('departamentos.nombre as tercero');

        $provedores = DB::table('entradas')
                      ->join('documentos', 'entradas.documento', '=', 'documentos.id')
                      ->join('provedores','entradas.tercero', '=', 'provedores.id')
                      ->where('entradas.deposito', $deposito)
                      ->where('documentos.tipo', 'proveedor')
                      ->where('documentos.naturaleza', 'entrada')
                      ->select($select)
                      ->addSelect('provedores.nombre as tercero');

        $depositos = DB::table('entradas')
                      ->join('documentos', 'entradas.documento', '=', 'documentos.id')
                      ->join('depositos','entradas.tercero', '=', 'depositos.id')
                      ->where('entradas.deposito', $deposito)
                      ->where('documentos.tipo', 'deposito')
                      ->Orwhere('documentos.tipo', 'interno')
                      ->where('documentos.naturaleza', 'entrada')
                      ->select($select)
                      ->addSelect('depositos.nombre as tercero');

        $entradas = $servicios
                    ->unionAll($provedores)
                    ->unionAll($depositos);

        return $entradas->take(100)->orderBy('id', 'desc')->get();
    }

    public function getEntrada($id){

        $deposito = Auth::user()->deposito;
        $entrada = Entrada::where('id',$id)
                            ->where('deposito', $deposito)
                            ->first();

        if(!$entrada){
          return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);
        }
        else{

          //Obtiene el tipo de documento de la entrada
          $tipo = Documento::where('id', $entrada->documento)->value('tipo');

          //Campos a consultar
          $select = [
            "entradas.codigo",
            "users.email as usuario",
            "entradas.id",
            "documentos.abreviatura",
            "documentos.nombre as concepto",
            DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
            DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora')
          ];

          //Consulta base para la entrada
          $query = DB::table('entradas')->where('entradas.id',$id)
               ->join('users', 'entradas.usuario' , '=', 'users.id')
               ->join('documentos','entradas.documento', '=','documentos.id')
               ->select($select);

          /**
            *Une table para buscar el nombre del tercero, segun el
            *tipo del documento de la entrada y lo selecciona.
            */
          switch ($tipo){

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
          $entradas = $query->first();

          //Consulta los insumos de la entrada
          $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
            ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
            ->select('insumos.codigo', 'insumos.descripcion','insumos_entradas.cantidad',
                    DB::raw('DATE_FORMAT(insumos_entradas.fechaV, "%d/%m/%Y") as fecha'),
                    'insumos_entradas.lote')
            ->get();

          //Devuelve los datos de la entrada
          return Response()->json(['status' => 'success','nota' => $entradas,'insumos' => $insumos]);
        }
    }

    public function getEntradaCodigo($code){

        $deposito = Auth::user()->deposito;
        $depositoCode = Deposito::where('id', $deposito)->value('codigo');
        $realCode = $depositoCode.'-'.$code;


        $entrada = Entrada::where('codigo',$realCode)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta entrada no existe']);
        }
        else{

            if( $entrada['type'] == 'devolucion'){

               $entrada = DB::table('entradas')->where('entradas.codigo',$realCode)
                    ->join('departamentos', 'entradas.provedor', '=', 'departamentos.id')
                    ->select('entradas.codigo','entradas.orden','entradas.id',
                        'departamentos.nombre as servicio', 'entradas.type')
                    ->first();
            }
            else{

                $entrada = DB::table('entradas')->where('entradas.codigo',$realCode)
                    ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                    ->select('entradas.codigo','entradas.orden','entradas.id',
                        'provedores.nombre as provedor', 'entradas.type')
                    ->first();
            }

           $insumos = DB::table('entradas')->where('entradas.codigo', $realCode)
                ->join('insumos_entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad', 'insumos_entradas.id as id')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public function getOrden($number){

        $deposito = Auth::user()->deposito;

        $entrada = Entrada::where('orden',$number)
                            ->where('deposito', $deposito)
                            ->get();

        if($entrada->isEmpty()){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta orden no existe']);
        }
        else{

            $orden = DB::table('entradas')->where('entradas.orden', $number)
                     ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                     ->select('entradas.orden as numero', 'provedores.nombre as provedor')
                     ->first();

            $entradas = Entrada::where('entradas.orden',$number)->lists('id');

            $insumos  = DB::table('insumos_entradas')->whereIn('entrada', $entradas)
                        ->where('insumos_entradas.deposito', $deposito)
                        ->join('entradas', 'insumos_entradas.entrada', '=', 'entradas.id')
                        ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                        ->select('entradas.codigo as entrada','insumos.codigo as codigo',
                            DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                            'entradas.id as entradaId','insumos.descripcion as descripcion',
                            'insumos_entradas.cantidad as cantidad', 'insumos_entradas.lote',
                            'insumos_entradas.fechaV')
                        ->orderBy('insumos_entradas.id', 'desc')->get();

            return Response()->json(['status' => 'success', 'orden' => $orden, 'insumos' => $insumos]);

        }
    }

    public function search(Request $request){

        $deposito = Auth::user()->deposito;

        if( $request->type === 'devolucion' ){
          $query = DB::table('entradas')
             ->where('entradas.type', 'devolucion')
             ->where('entradas.deposito',$deposito)
             ->join('departamentos', 'entradas.provedor', '=', 'departamentos.id')
             ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                 'entradas.id', 'codigo', 'departamentos.nombre as provedor', 'entradas.type');
        }
        else{
         $query = DB::table('entradas')->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
            'entradas.id', 'codigo', 'provedores.nombre as provedor', 'entradas.type')
            ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
            ->where('entradas.deposito', $deposito);
        }

        //Filtro para buscar entradas por el tipo de orden o donacion
        if($request->type === 'orden' || $request->type === 'donacion'){
          $query->where('entradas.type', $request->type);
        }

        //Filtro que devuelve todas las entradas
        if($request->type === 'all'){

           $devoluciones = DB::table('entradas')
              ->where('entradas.type', 'devolucion')
              ->where('entradas.deposito',$deposito)
              ->join('departamentos', 'entradas.provedor', '=', 'departamentos.id')
              ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                  'entradas.id', 'codigo', 'departamentos.nombre as provedor', 'entradas.type');

            //Filtro para buscar entradas segun un insumo
            if($request->insumo){
              $devoluciones->join('insumos_entradas', 'insumos_entradas.entrada', '=', 'entradas.id')
              ->where('insumos_entradas.insumo', $request->insumo);

              //Filtro para buscar entradas segun rangos de cantidad del insumo
              if($request->amountrange){
                  $devoluciones->whereBetween('insumos_entradas.cantidad',
                  [$request->cantidadI,$request->cantidadF]);
              }
            }

            //Filtro para buscar entradas por rangos de fecha
            if($request->dateranger){
              $devoluciones->whereBetween(DB::raw('DATE_FORMAT(entradas.created_at, "%Y-%m-%d")'),
                [$request->fechaI,$request->fechaF]);
            }

            //Filtro para buscar entradas segun un usuario
            if($request->user){
              $devoluciones->where('usuario',$request->user);
            }

            //Filtro para buscar entradas por rangos de horas
            if($request->hourrange){
              $devoluciones->whereBetween(DB::raw('DATE_FORMAT(entradas.created_at, "%H-%i")'),
                [$request->horaI,$request->horaF]);
            }

            $query->where(function ($query) {
                $query->where('entradas.type', 'orden')
                ->orWhere('entradas.type', 'donacion');
             });

            $query->unionAll($devoluciones);
        }

        //Filtro para buscar entradas por rangos de fecha
        if($request->dateranger){
          $query->whereBetween(DB::raw('DATE_FORMAT(entradas.created_at, "%Y-%m-%d")'),
            [$request->fechaI,$request->fechaF]);
        }

        //Filtro para buscar entradas segun un proveedor
        if($request->prove){
          $query->where('entradas.provedor',$request->prove);
        }

        //Filtro para buscar entradas segun un insumo
        if($request->insumo){
          $query->join('insumos_entradas', 'insumos_entradas.entrada', '=', 'entradas.id')
            ->where('insumos_entradas.insumo', $request->insumo);

            //Filtro para buscar entradas segun rangos de cantidad del insumo
           if($request->amountrange){
             $query->whereBetween('insumos_entradas.cantidad',
              [$request->cantidadI,$request->cantidadF]);
           }
        }

        //Filtro para buscar entradas segun un usuario
        if($request->user){
          $query->where('usuario',$request->user);
        }

        //Filtro para buscar entradas por rangos de horas
        if($request->hourrange){
          $query->whereBetween(DB::raw('DATE_FORMAT(entradas.created_at, "%H-%i")'),
            [$request->horaI,$request->horaF]);
        }

        //Filtro para ordenar los resultados de forma decendente o acendentes
        if($request->orden){
          $query->orderBy('id',$request->orden);
        }
        else{
          $query->orderBy('id','decs');
        }

        //Regresa los resultados.
        return $query->get();
    }

    public function registrar(Request $request){

        $data     = $request->all();
        $usuario  = Auth::user()->id;
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'documento' =>  'required|numeric|documento_entrada',
            'tercero'   =>  'numeric|tercero:documento',
            'insumos'   =>  'required|insumos'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

          if(!isset($data['tercero']) || empty($data['tercero'])){
            $tipo = Documento::where('id', $data['documento'])->value('tipo');
            if($tipo == 'interno'){
              $data['tercero'] = $deposito;
            }
            else{
              return Response()->json(['status' => 'danger', 'menssage' => 'Seleccione un tercero']);
            }
          }

          $insumos = $data['insumos'];

          //Codigo para la entrada
          $code = $this->generateCode('E', $deposito);

          $entrada = Entrada::create([
                      'codigo'   => $code,
                      'tercero'  => $data['tercero'],
                      'documento'=> $data['documento'],
                      'usuario'  => $usuario,
                      'deposito' => $deposito
                  ])['id'];

          foreach ($insumos as $insumo){

              $existencia = inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad'], $deposito,
                  'entrada', $entrada);

              $lote  = isset($insumo['lote'])  && $insumo['lote'] ? $insumo['lote']  : NULL;
              $fecha = isset($insumo['fecha']) && $insumo['lote'] ? $insumo['fecha'] : NULL;

              Insumos_entrada::create([
                  'entrada'    => $entrada,
                  'insumo'     => $insumo['id'],
                  'cantidad'   => $insumo['cantidad'],
                  'lote'       => $lote,
                  'fechaV'     => $fecha,
                  'deposito'   => $deposito,
                  'existencia' => $existencia 
              ]);

          }

          return Response()->json(['status' => 'success', 'menssage' =>
              'Entrada completada satisfactoriamente', 'codigo' => $code]);
        }
    }

    /*Funcion que genera codigos para las entradas,
     *segun un prefijo y deposito que se pase
     */
    private function generateCode($prefix, $deposito){

        //Obtiene Codigo del deposito
        $depCode = Deposito::where('id' , $deposito)->value('codigo');

        return strtoupper( $depCode .'-'.$prefix.str_random(7) );
    }
}
