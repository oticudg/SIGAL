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
use App\Insumos_salida;
use App\Inventario_operacione;
use App\Deposito;
use App\Repositories\LotesRepository;
use App\Repositories\AlertsRepository;

class inventarioController extends Controller
{
    private $menssage = [
       'insumos.required'  =>  'No se han especificado insumos para esta entrada'
    ];

    public function index(){
        return view('inventario/indexInventario');
    }

    public function viewAlerts(){
        return view('inventario/alerts');
    }

    public function viewInsumosAlertas(){
        return view('inventario/nivelesInventario');
    }

    public function searchKardex(){
      return view('inventario/searchKardex');
    }


    public function viewKardex(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'insumo'  => 'required|integer|insumo_with_daleted',
          'dateI'   => 'date_format:d/m/Y',
          'dateF'   => 'date_format:d/m/Y'
      ], $this->menssage);

      if($validator->fails()){
        abort('404');
      }

      $dateI = isset($data['dateI']) ? $data['dateI']:null;
      $dateF = isset($data['dateF']) ? $data['dateF']:null;

      //Obtiene la informacion del insumo
      $insumoData = DB::table('insumos')->where('id', $data['insumo'])->first(['codigo', 'descripcion']);

      return view('inventario/kardex',
        ['insumo' => $data['insumo'], 'dateI' => $dateI,
        'dateF' => $dateF, 'insumoData' => $insumoData]);
    }

    public function allInsumos(Request $request){

        $data = $request->all();
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'date'   => 'date_format:d/m/Y|date_limit_current',
        ]);

        if($validator->fails()){
          return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }

        /**
          *Si se pasa una fecha se transforma al formato a utilizar,
          *de lo contrario se toma la fecha del mes actual.
          */
        if(isset($data['date']) && !empty($data['date'])){
          $dateConvert = str_replace('/', '-', $data['date']);
          $date = Date('Y-m-d', strtotime($dateConvert));
        }
        else{
          $date = date('Y-m-d');
        }

        //Año inicial del rango de fecha a consultar
        $init_year_search = date('2015-01-01');

        /**
          *Define la fecha inicial en el rango de fecha a consultar
          *como la fecha de la ultima carga de inventario.
          */
        $first_date = DB::table('entradas')
                      ->where('deposito', $deposito)
                      ->where('type','cinventario')
                      ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                      ,[$init_year_search, $date])
                      ->orderBy('id', 'desc')
                      ->value(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'));

        /**
          *Si no se encontro carga de inventario, la fecha incial en el rango de
          *fecha a consultar de define como, el año inicial del rango
          *de fecha a consultar.
          */
        if(!$first_date)
          $first_date = $init_year_search;

        /**
          *Si se ha pasado el parametro move, se obtienen solo los  ids de
          *insumos que han tenido movimientos en la fecha pasado, De lo contrario
          *Obtiene los ids de todos los insumos que han entrada en el inventario
          *desde el año inicial de la fecha a consultar, hasta la fecha a consultar.
          */
        if(isset($data['move'])){
          $insumoIds = $this->insumosMove($date, $deposito);
        }
        else{

          $insumoIds = Insumos_entrada::distinct('insumo')
                 ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                 ,[$first_date, $date])->where('deposito', $deposito)
                 ->lists('insumo');
        }

        //Obtiene los datos de los insumos cuyos ids se han encontrado.
        $query = DB::table('insumos')
                       ->leftjoin('inventarios', function($join) use ($deposito){
                         $join->on('insumos.id','=','inventarios.insumo')
                         ->where('inventarios.deposito','=',$deposito);
                       })
                       ->whereIn('insumos.id', $insumoIds)
                       ->select('insumos.id as id','insumos.codigo','insumos.descripcion',
                          DB::raw('IFNULL(inventarios.cmin, 0) as min'),
                          DB::raw('IFNULL(inventarios.cmed, 0) as med'),
                          'inventarios.promedio'
                       );

        /**
          *Si el inventario consultado es el de la fecha actual, obtiene la existencia en
          *base a la tabla inventarios. de lo contrario obtiene la existencia en base al
          *ultimo movimiento del insumo en el periodo de fecha consultado.
          */
        if( date('Y-m-d') == $date ){

          $insumos = $query
                     ->addSelect(DB::raw('IFNULL(inventarios.existencia, 0) as existencia'))
                     ->get();
        }
        else{

          //Obtiene todos los insumos sin existencia.
          $insumos = $query->get();

          //Calcula la existencia de cada insumo que se ha encontrado.
          foreach($insumos as $insumo){

            //Obtien la ultima entrada del insumo a calcular existencia
            $entradas = DB::table('insumos_entradas')->where('insumo', $insumo->id)
                   ->where('deposito', $deposito)
                   ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                   [$first_date, $date])
                   ->select('existencia', 'created_at')
                   ->orderBy('created_at', 'desc')
                   ->take(1);

            //Obtiene la ultima salida del insumo a calcular existencia
            $salidas = DB::table('insumos_salidas')->where('insumo', $insumo->id)
                        ->where('deposito', $deposito)
                        ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                        ,[$first_date, $date])
                        ->select('existencia', 'created_at')
                        ->orderBy('created_at','desc')
                        ->take(1);

            /**
              *Une los resultados de la ultima entrada y la ultima salida del insumo a calcular
              *y obtiene el ultimo movimiento.
              */
            $existencia = $entradas->unionAll($salidas)
                          ->orderBy('created_at', 'desc')
                          ->first();

            //Asigna la existencia del insumo en base al ultimo movimiento.
            $insumo->existencia = round( $existencia->existencia, 2);

          }
       }

       return Response()->json([
        'status'  => "success",
        'dateI'   => Date('d/m/Y',strtotime($first_date)),
        'dateF'   => Date('d/m/Y',strtotime($date)),
        'insumos' => array_reverse($insumos)
       ]);

    }

    public function getInsumosAlert(Request $request){

        $deposito = Auth::user()->deposito;
        $consulta = $request->input('insumo');

        if($consulta != ""){

            return DB::table('insumos')
                        ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                        ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                            'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med', 'inventarios.promedio')
                        ->where('deposito', $deposito)
                        ->where(function($query) use ($consulta){
                            $query->where('descripcion', 'like', '%'.$consulta.'%')
                                  ->orwhere('codigo', 'like', '%'.$consulta.'%');
                        })->orderBy('inventarios.id', 'desc')->take(50)->get();
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
                              'Cmed' => $insumo['med'],
                              'promedio' => $insumo['promedio']
                            ]);
            }

            return Response()->json(['status' => 'success', 'menssage' =>
                'Alarmas configuradas exitosamente']);
        }

    }

    public function nivelesAlert(){

        $alerts = new AlertsRepository();

        return $alerts->insumosNivel();
    }

    public function vencimientosAlert(){

        $alerts = new AlertsRepository();

        return $alerts->insumosVencimiento();
    }

    public function kardex(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'insumo'  => 'required|integer|insumo_with_daleted',
          'dateI'   => 'date_format:d/m/Y',
          'dateF'   => 'date_format:d/m/Y'
      ], $this->menssage);

      if($validator->fails()){
        return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
      }

      //Insumo para el que se realizara el kardex
      $insumo = $data['insumo'];
      //Obtiene el deposito del usuario que realiza la consulta.
      $deposito = Auth::user()->deposito;


      /**
        *Si se ha pasado una fecha inicial a consultar
        *se convirte en el formato de fecha a utilizar,
        *de lo contrario se toma como fecha inicial
        *la fecha del primer mes del año en curso.
        */
      if(isset($data['dateI']) && !empty($data['dateI'])){
        $dateConvert = str_replace('/', '-', $data['dateI']);
        $dateI = Date("Y-m-d", strtotime($dateConvert));
      }
      else{
        $dateI = date("Y-01-01");
      }

      /**
        *Si se ha pasado una fecha final a consultar
        *se convirte en el formato de fecha a utilizar,
        *de lo contrario se toma como fecha final
        *la fecha del ultimo mes del año en curso.
        */
      if(isset($data['dateF']) && !empty($data['dateF'])){
        $dateConvert = str_replace('/', '-', $data['dateF']);
        $dateF = Date("Y-m-d", strtotime($dateConvert));
      }
      else{
        $dateF  = date("Y-12-31");
      }


      //Campos comunes a seleccionar en las salidas.
      $select_salida = [
        'insumos_salidas.id as id',
        'insumos_salidas.despachado as movido',
        'salidas.id as referencia',
        'insumos_salidas.created_at as fulldate',
        'documentos.naturaleza as type',
        'documentos.nombre as concepto',
        'documentos.abreviatura',
        DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%d/%m/%Y") as fecha'),
      ];

      //Campos comunes a seleccionar en las entradas.
      $select_entrada = [
        'insumos_entradas.id as id',
        'insumos_entradas.cantidad as movido',
        'entradas.id as referencia',
        'insumos_entradas.created_at as fulldate',
        'documentos.naturaleza as type',
        'documentos.nombre as concepto',
        'documentos.abreviatura',
        DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%d/%m/%Y") as fecha'),
      ];

      /**
        *Almacena las consultas base para obtener los datos de las salidas,
        *NOTA: debido a la imposibilidad para usar una solo query para traer
        *todos los registros en las querys espesificas, se almacena la misma
        *query un arreglo.
        */
      for($i = 0; $i < 4; $i++){
        $query_s[$i] = DB::table('insumos_salidas')->where('insumo', $insumo)
                      ->where('insumos_salidas.deposito', $deposito)
                      ->join('salidas', 'insumos_salidas.salida',  '=', 'salidas.id')
                      ->join('documentos', 'salidas.documento',    '=', 'documentos.id')
                      ->whereBetween(DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%Y-%m-%d")'), [$dateI, $dateF])
                      ->select($select_salida);
      }

      /**
        *Almacena las consultas base para obtener los datos de las entradas,
        *NOTA: debido a la imposibilidad para usar una solo query para traer
        *todos los registros en las querys espesificas, se almacena la misma
        *query un arreglo.
        */
      for($i = 0; $i < 4; $i++){
        $query_e[$i] = DB::table('insumos_entradas')->where('insumo', $insumo)
                      ->where('insumos_entradas.deposito', $deposito)
                      ->join('entradas', 'insumos_entradas.entrada',  '=', 'entradas.id')
                      ->join('documentos', 'entradas.documento',      '=', 'documentos.id')
                      ->whereBetween(DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%Y-%m-%d")'), [$dateI, $dateF])
                      ->select($select_entrada);
      }

      //Querys espesificas para cada tipo de salida.
      $salida_servicios  = $query_s[0]
                          ->join('departamentos', 'salidas.tercero', '=', 'departamentos.id')
                          ->where('documentos.tipo', 'servicio')
                          ->where('documentos.naturaleza', 'salida')
                          ->addSelect('departamentos.nombre as pod', 'insumos_salidas.existencia');

      $salida_provedores = $query_s[1]
                          ->join('provedores', 'salidas.tercero', '=', 'provedores.id')
                          ->where('documentos.tipo', 'proveedor')
                          ->where('documentos.naturaleza', 'salida')
                          ->addSelect('provedores.nombre as pod', 'insumos_salidas.existencia');


      $salida_depositos  = $query_s[2]
                          ->join('depositos', 'salidas.tercero', '=', 'depositos.id')
                          ->where('documentos.tipo', 'deposito')
                          ->where('documentos.naturaleza', 'salida')
                          ->addSelect('depositos.nombre as pod', 'insumos_salidas.existencia');

      $salida_internos   = $query_s[3]
                          ->join('depositos', 'salidas.tercero', '=', 'depositos.id')
                          ->where('documentos.tipo', 'interno')
                          ->where('documentos.naturaleza', 'salida')
                          ->addSelect('depositos.nombre as pod', 'insumos_salidas.existencia');


      //Querys espesificas para cada tipo de entrada.
      $entradas_servicios = $query_e[0]
                          ->join('departamentos', 'entradas.tercero', '=', 'departamentos.id')
                          ->where('documentos.tipo', 'servicio')
                          ->whereIn('documentos.naturaleza', ['entrada','establecer'])
                          ->addSelect('departamentos.nombre as pod', 'insumos_entradas.existencia');

      $entradas_provedores = $query_e[1]
                          ->join('provedores', 'entradas.tercero', '=', 'provedores.id')
                          ->where('documentos.tipo', 'proveedor')
                          ->whereIn('documentos.naturaleza', ['entrada','establecer'])
                          ->addSelect('provedores.nombre as pod', 'insumos_entradas.existencia');

      $entradas_depositos = $query_e[2]
                          ->join('depositos', 'entradas.tercero', '=', 'depositos.id')
                          ->where('documentos.tipo', 'deposito')
                          ->whereIn('documentos.naturaleza', ['entrada','establecer'])
                          ->addSelect('depositos.nombre as pod', 'insumos_entradas.existencia');

      $entradas_internos  = $query_e[3]
                          ->join('depositos', 'entradas.tercero', '=', 'depositos.id')
                          ->where('documentos.tipo', 'interno')
                          ->whereIn('documentos.naturaleza', ['entrada','establecer'])
                          ->addSelect('depositos.nombre as pod', 'insumos_entradas.existencia');

      //Une todas las consultas de entradas y salidas.
      $uniones = $this->filterKardex($salida_servicios, $data,1)
                 ->unionAll( $this->filterKardex($salida_provedores, $data, 1))
                 ->unionAll( $this->filterKardex($salida_depositos, $data, 1))
                 ->unionAll( $this->filterKardex($salida_internos, $data, 1))
                 ->unionAll( $this->filterKardex($entradas_servicios, $data, 2))
                 ->unionAll( $this->filterKardex($entradas_provedores, $data, 2))
                 ->unionAll( $this->filterKardex($entradas_depositos, $data, 2))
                 ->unionAll( $this->filterKardex($entradas_internos, $data, 2));

      //Realiza todas las consultas y establece el orden en los resultados.
      $movimientos =  $uniones
                      ->orderBy('fulldate', 'desc')
                      ->orderBy('id', 'desc')
                      ->get();

     return Response()->json(['status' => 'success', 'kardex' => $movimientos]);

    }

    public function insumoLotes($id){
      $insumo  = inventario::where('insumo', $id)
                           ->where('deposito', Auth::user()->deposito)
                           ->where('existencia', '>', 0)
                           ->first();
      if(!$insumo)
          abort('404');

      $loteRegister = new LotesRepository();

      $nombre = insumo::where('id',$id)->value('descripcion');
      $lotes = $loteRegister->lotes($id);

      return Response()->json(
        [
          'insumo' => ['nombre' => $nombre, 'cantidad' => count($lotes)],
          'lotes'  => $lotes
        ]
      );
    }

    public static function reduceInsumo($insumo, $cantidad, $deposito){

        $inventario = Inventario::where('insumo', $insumo)
                                  ->where('deposito', $deposito)
                                  ->first();

        if( $inventario ){

            $existencia = Inventario::where('insumo', $insumo)
                                      ->where('deposito', $deposito)
                                      ->value('existencia');

            $existencia -= $cantidad;

            Inventario::where('insumo' , $insumo)
                        ->where('deposito', $deposito)
                        ->update(['existencia' => $existencia]);

            return $existencia;
        }
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
     * prefijo.
     */
    private function generateCode($prefix,$deposito){

        //Obtiene Codigo del deposito.
        $depCode = Deposito::where('id' , $deposito)->value('codigo');

        return strtoupper( $depCode .'-'.$prefix.str_random(6) );
    }

    private function filterKardex($movimientos,$filters, $type){

      //Filtro para buscar movimiento por naturaleza.
      if(isset($filters['type'])){
        if($filters['type'] == 'entrada'){
          $movimientos->where('documentos.naturaleza', 'entrada');
        }
        elseif($filters['type'] == 'salida'){
          $movimientos->where('documentos.naturaleza', 'salida');
        }
      }

      //Filtro para buscar movimientos por usuario.
      if(isset($filters['user'])){
        $movimientos->where('usuario', $filters['user']);
      }

      //Filtro para buscar movimientos por rango de hora.
      if(isset($filters['hourrange'])){
        if($type == 1){
          $movimientos->whereBetween(DB::raw('DATE_FORMAT(salidas.created_at, "%H-%i")'),
                      [$filters['horaI'],$filters['horaF']]);
        }
        elseif($type == 2){
          $movimientos->whereBetween(DB::raw('DATE_FORMAT(entradas.created_at, "%H-%i")'),
                      [$filters['horaI'],$filters['horaF']]);
        }
      }

      //Filtro para buscar movimientos por rango de cantidad.
      if(isset($filters['moveRange'])){
        if($type == 1){
          $movimientos->whereBetween('insumos_salidas.despachado',
            [$filters['cantidadI'],$filters['cantidadF']]);
        }
        elseif($type == 2){
          $movimientos->whereBetween('insumos_entradas.cantidad',
            [$filters['cantidadI'],$filters['cantidadF']]);
        }
      }

      //Filtro para buscar movimientos por rango de existencia.
      if(isset($filters['existRange'])){
        $movimientos->whereBetween('existencia',
          [$filters['existenciaI'],$filters['existenciaF']]);
      }

      //Filtro para buscar movimientos por rango comcepto.
      if(isset($filters['concep'])){
        $movimientos->where('documentos.id', $filters['concep']);
      }

      //Filtro para buscar movimientos por tercero.
      if(isset($filters['terceroSearch'])){
        if($type == 1){
          $movimientos
            ->where('documentos.tipo', $filters['tType'])
            ->where('salidas.tercero', $filters['tercero']);
        }
        elseif($type == 2){
          $movimientos
            ->where('documentos.tipo',  $filters['tType'])
            ->where('entradas.tercero', $filters['tercero']);
        }
      }

      return $movimientos;

    }

    private function insumosMove($date, $deposito){

      $entradas = Insumos_entrada::distinct('insumo')
             ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
             ,$date)->where('deposito', $deposito)
             ->select('insumo');

      $salidas = Insumos_salida::distinct('insumo')
              ->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
              ,$date)->where('deposito', $deposito)
              ->select('insumo');

      $movimientos = $entradas->union($salidas);

      return $movimientos->lists('insumo');

    }

}
