<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entrada;
use App\Salida;
use App\Deposito;
use Validator;
use App\Insumos_entrada;
use App\Insumos_salida;
use App\Insumo;
use App\Documento;

class reportesController extends Controller
{
    public function cargaInventario($id){

        $deposito = Auth::user()->deposito;
        $entrada = Entrada::where('id',$id)
                            ->where('deposito', $deposito)
                            ->where('type', 'cinventario')
                            ->first();
        if(!$entrada){
            abort('404');
        }
        else{

            $carga = DB::table('entradas')->where('entradas.id',$id)
                        ->join('users', 'entradas.usuario' , '=', 'users.id' )
                        ->join('depositos', 'users.deposito', '=', 'depositos.id')
                        ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                            DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                            'users.email as usuario', 'depositos.nombre as deposito')
                        ->first();

            $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                ->get();

            $view =  \View::make('reportes.pdfs.cargaInventario', compact('carga' , 'insumos'))->render();
            $pdf  =  \App::make('dompdf.wrapper');
            $pdf->loadHTML($view);
            return $pdf->stream('Carga inventario');
        }
    }

    public function allInventario(Request $request){

        $data = $request->all();
        $deposito   = Auth::user()->deposito;
        $usuario    = Auth::user()->email;
        $depositoN  = Deposito::where('id', $deposito)->value('nombre');
        $fecha      = date("d/m/Y");
        $hora       = date("H:i:s");
        $title      = "INVENTARIO TOTAL";

        $validator = Validator::make($data,[
            'date'   => 'date_format:d/m/Y|date_limit_current',
        ]);

        if($validator->fails()){
          abort('404');
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
        $init_year_search = date('Y-01-01',strtotime($date));

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
          *fecha a consultar se define como el año inicial del rango
          *de fecha a consultar.
          */
        if(!$first_date)
          $first_date = $init_year_search;

        /**
          *Obtiene los ids de todos los insumos que han entrada en el inventario
          *desde el año inicial de la fecha a consultar, hasta la fecha a consultar.
          */
        $insumoIds = Insumos_entrada::distinct('insumo')
               ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
               ,[$first_date, $date])->where('deposito', $deposito)
               ->lists('insumo');


        //Obtiene los datos de los insumos cuyos ids se han encontrado.
        $query = DB::table('insumos')
                       ->leftjoin('inventarios', function($join) use ($deposito){
                         $join->on('insumos.id','=','inventarios.insumo')
                         ->where('inventarios.deposito','=',$deposito);
                       })
                       ->whereIn('insumos.id', $insumoIds)
                       ->select('insumos.id as id','insumos.codigo','insumos.descripcion')
                       ->orderBy('insumos.codigo', 'desc');

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
            $insumo->existencia = $existencia->existencia;

          }
        }

        //Filtro para filtrar registor en base a la existencia.
        if(isset($data['filter'])){
          if($data['filter'] == 'true'){
            foreach ($insumos as $key => $insumo){
              if($insumo->existencia == 0)
                unset($insumos[$key]);
            }

            $title = "INVENTARIO SIN FALLAS";
          }
          else if($data['filter'] == 'false'){
            foreach ($insumos as $key => $insumo){
              if($insumo->existencia > 0)
                unset($insumos[$key]);
            }
          }
        }

        $view =  \View::make('reportes.pdfs.allInventario',
                     compact('insumos', 'usuario', 'depositoN', 'fecha', 'hora','title'),
                     ['date' => $data['date']]
                     )->render();

        $pdf  =  \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Inventario total');

    }

    public function getInventario(Request $request){

        $data       = $request->all();
        $deposito   = Auth::user()->deposito;
        $usuario    = Auth::user()->email;
        $depositoN  = Deposito::where('id', $deposito)->value('nombre');
        $fecha      = date("d/m/Y");
        $hora       = date("H:i:s");

        $validator = Validator::make($data,[
            'date'    => 'required|date_format:d/m/Y|date_limit_current',
            'insumos' => 'required|insumos_ids_array'
        ]);

        if($validator->fails()){
          abort('404');
        }

        //Transforma al fecha al formato a utilizar.
        $dateConvert = str_replace('/', '-', $data['date']);
        $date = Date('Y-m-d', strtotime($dateConvert));


        //Año inicial del rango de fecha a consultar
        $init_year_search = date('Y-01-01',strtotime($date));

        //Obtien la ultima carga de inventario
        $last_cinve = DB::table('entradas')
                      ->where('deposito', $deposito)
                      ->where('type','cinventario')
                      ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                      ,[$init_year_search, $date])
                      ->orderBy('id', 'desc')
                      ->value(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'));

        //Obtiene los datos de los insumos cuyos ids se han encontrado.
        $insumos = DB::table('insumos')
                       ->leftjoin('inventarios', function($join) use ($deposito){
                         $join->on('insumos.id','=','inventarios.insumo')
                         ->where('inventarios.deposito','=',$deposito);
                       })
                       ->whereIn('insumos.id', $data['insumos'])
                       ->select('insumos.id as id','insumos.codigo','insumos.descripcion',
                          DB::raw('IFNULL(inventarios.cmin, 0) as min'),
                          DB::raw('IFNULL(inventarios.cmed, 0) as med'))
                       ->orderBy('insumos.codigo', 'desc')
                       ->get();

        //Calcula la existencia de cada insumo que se ha encontrado.
        foreach($insumos as $key => $insumo){

          //Obtiene la suma de todas las entradas del insumo que se consulta.
          $entradas = Insumos_entrada::where('insumo', $insumo->id)
                 ->where('deposito', $deposito)
                 ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'),
                 [$last_cinve, $date])
                 ->sum('cantidad');

          //Obtine la suma de todas las salidas del insumo que se consulta.
          $salidas = Insumos_salida::where('insumo', $insumo->id)
                     ->where('deposito', $deposito)
                     ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                     ,[$last_cinve, $date])
                     ->sum('despachado');

          //Calcula la existencia
          $existencia = $entradas - $salidas;
          //Asigna existencia
          $insumo->existencia = $existencia;

        }

        $view =  \View::make('reportes.pdfs.parcialInventario',
                     compact('insumos', 'usuario', 'depositoN', 'fecha', 'hora'),
                     ['date' => $data['date']]
                     )->render();

        $pdf  =  \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Inventario total');
    }

    public function getEntrada($id){

      $deposito = Auth::user()->deposito;
      $entrada = Entrada::where('id',$id)
                          ->where('deposito', $deposito)
                          ->first();
      if(!$entrada){
        abort('404');
      }

      //Obtiene el tipo de documento de la entrada
      $tipo = Documento::where('id', $entrada->documento)->value('tipo');

      //Campos a consultar
      $select = [
        "entradas.codigo",
        "users.email as usuario",
        "entradas.id",
        "depositos.nombre as deposito",
        "documentos.nombre as concepto",
        DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
        DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora')
      ];

      //Consulta base para la entrada
      $query = DB::table('entradas')->where('entradas.id',$id)
           ->join('users', 'entradas.usuario' , '=', 'users.id')
           ->join('documentos','entradas.documento', '=','documentos.id')
           ->join('depositos', 'entradas.deposito',  '=', 'depositos.id')
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
          $query->join('depositos as depositoTercero', 'entradas.tercero', '=', 'depositoTercero.id')
                ->addSelect('depositoTercero.nombre as tercero');
        break;

        case 'interno':
          $query->join('depositos as depositoTercero', 'entradas.tercero', '=', 'depositoTercero.id')
                ->addSelect('depositoTercero.nombre as tercero');
        break;

      }

      //Realiza la consulta
      $entrada = $query->first();

      //Consulta los insumos de la entrada
      $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
        ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
        ->select('insumos.codigo', 'insumos.descripcion','insumos_entradas.cantidad',
          DB::raw('DATE_FORMAT(insumos_entradas.fechaV, "%d/%m/%Y") as fecha'), 'insumos_entradas.lote')
        ->get();


      //return response()->json(['entrada' => $entrada]);

      $view =  \View::make('reportes.pdfs.entrada', compact('entrada' , 'insumos'))->render();
      $pdf  =  \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('Pro-Forma de entrada');

    }

    public function getSalida($id){

      $deposito = Auth::user()->deposito;
      $salida   = Salida::where('id',$id)
                          ->where('deposito', $deposito)
                          ->firstOrFail();

      //Obtiene el tipo de documento de la salida
      $tipo = Documento::where('id', $salida->documento)->value('tipo');

      //Campos a consultar
      $select = [
        "salidas.codigo",
        "users.email as usuario",
        "salidas.id",
        "documentos.nombre as concepto",
        "depositos.nombre as deposito",
        DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),
        DB::raw('DATE_FORMAT(salidas.created_at, "%H:%i:%s") as hora')
      ];

      //Consulta base para la salidas
      $query = DB::table('salidas')->where('salidas.id',$id)
           ->join('users', 'salidas.usuario' , '=', 'users.id')
           ->join('depositos', 'salidas.deposito', '=', 'depositos.id')
           ->join('documentos','salidas.documento', '=','documentos.id')
           ->select($select);

      /**
        *Une table para buscar el nombre del tercero, segun el
        *tipo del documento de la salida y lo selecciona.
        */
      switch ($tipo){

        case 'servicio':
          $query->join('departamentos', 'salidas.tercero', '=', 'departamentos.id')
              ->addSelect('departamentos.nombre as tercero');
        break;

        case 'proveedor':
          $query->join('provedores', 'salidas.tercero', '=', 'provedores.id')
              ->addSelect('provedores.nombre as tercero');
        break;

        case 'deposito':
          $query->join('depositos as depositoTercero', 'salidas.tercero', '=', 'depositoTercero.id')
                ->addSelect('depositoTercero.nombre as tercero');
        break;

        case 'interno':
          $query->join('depositos as depositoTercero', 'salidas.tercero', '=', 'depositoTercero.id')
                ->addSelect('depositoTercero.nombre as tercero');
        break;

      }

      //Realiza la consulta
      $salida = $query->first();

      $insumos = DB::table('insumos_salidas')->where('insumos_salidas.salida', $id)
           ->join('insumos', 'insumos_salidas.insumo', '=', 'insumos.id')
           ->select('insumos.codigo', 'insumos.descripcion', 'insumos_salidas.solicitado',
             'insumos_salidas.despachado')
           ->get();

      $view =  \View::make('reportes.pdfs.salida', compact('salida' , 'insumos'))->render();
      $pdf  =  \App::make('dompdf.wrapper');
      $pdf->loadHTML($view);
      return $pdf->stream('Pro-Forma de pedido');

    }

    public function getKardex(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'insumo'  => 'required|insumo',
          'dateI'   => 'required|date_format:d/m/Y',
          'dateF'   => 'required|date_format:d/m/Y'
      ]);

      if($validator->fails()){
        abort("404");
      }

      $deposito = Auth::user()->deposito;
      $insumo   = $data['insumo'];

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
        'insumos_salidas.despachado as movido',
        'insumos_salidas.created_at as fulldate',
        'documentos.naturaleza as type',
        'documentos.nombre as concepto',
        DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%d/%m/%Y") as fecha'),
      ];

      //Campos comunes a seleccionar en las entradas.
      $select_entrada = [
        'insumos_entradas.cantidad as movido',
        'insumos_entradas.created_at as fulldate',
        'documentos.naturaleza as type',
        'documentos.nombre as concepto',
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
                          ->where('documentos.naturaleza', 'entrada')
                          ->addSelect('departamentos.nombre as pod', 'insumos_entradas.existencia');

      $entradas_provedores = $query_e[1]
                          ->join('provedores', 'entradas.tercero', '=', 'provedores.id')
                          ->where('documentos.tipo', 'proveedor')
                          ->where('documentos.naturaleza', 'entrada')
                          ->addSelect('provedores.nombre as pod', 'insumos_entradas.existencia');

      $entradas_depositos = $query_e[2]
                          ->join('depositos', 'entradas.tercero', '=', 'depositos.id')
                          ->where('documentos.tipo', 'deposito')
                          ->where('documentos.naturaleza', 'entrada')
                          ->addSelect('depositos.nombre as pod', 'insumos_entradas.existencia');

      $entradas_internos  = $query_e[3]
                          ->join('depositos', 'entradas.tercero', '=', 'depositos.id')
                          ->where('documentos.tipo', 'interno')
                          ->where('documentos.naturaleza', 'entrada')
                          ->addSelect('depositos.nombre as pod', 'insumos_entradas.existencia');

      //Une todas las consultas de entradas y salidas.
      $uniones = $salida_servicios
                 ->unionAll($salida_provedores)
                 ->unionAll($salida_depositos)
                 ->unionAll($salida_internos)
                 ->unionAll($entradas_servicios)
                 ->unionAll($entradas_provedores)
                 ->unionAll($entradas_depositos)
                 ->unionAll($entradas_internos);

      //Realiza todas las consultas y establece el orden en los resultados.
      $movimientos =  $uniones
                      ->orderBy('fulldate', 'asc')
                      ->get();

       //Obtiene la informacion del insumo
       $insumoData = Insumo::where('id', $data['insumo'])->first(['codigo', 'descripcion']);
       //Obtiene nombre del deposito
       $deposito   =  Deposito::where('id', $deposito)->value('nombre');
       //Obtiene usuario
       $usuario    = Auth::user()->email;

       $view =  \View::make('reportes.pdfs.kardex',
        compact('insumoData' , 'deposito', 'usuario', 'movimientos'),
        ['dateI' => $data['dateI'],'dateF' => $data['dateF']]
       )->render();

       $pdf  =  \App::make('dompdf.wrapper');
       $pdf->loadHTML($view);
       return $pdf->stream('Kardex');
    }
}
