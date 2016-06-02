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

        //Si se pasa una fecha se transforma al formato a utilizar.
        if(isset($data['date']) && !empty($data['date'])){
          $dateConvert = str_replace('/', '-', $data['date']);
          $date = Date('Y-m-d', strtotime($dateConvert));
        }
        //Si no se pasa una fecha se toma la fecha del mes actual
        else{
          $date = date('Y-m-d');
        }


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

        /**
          *Obtiene los ids de todos los insumos que han entrado en el inventario
          *desde el año inicial de la fecha a consultar, hasta la fecha a consultar.
          */
        $insumoIds = Insumos_entrada::distinct('insumo')
                    ->whereBetween(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")')
                    ,[$last_cinve, $date])->where('deposito', $deposito)
                    ->lists('insumo');

        //Obtiene los datos de los insumos cuyos ids se han encontrado.
        $insumos = DB::table('insumos')
                       ->leftjoin('inventarios', function($join) use ($deposito){
                         $join->on('insumos.id','=','inventarios.insumo')
                         ->where('inventarios.deposito','=',$deposito);
                       })
                       ->whereIn('insumos.id', $insumoIds)
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
                          ->whereIn('type', ['orden', 'devolucion', 'donacion'])
                          ->firstOrFail();

      if($entrada['type'] == 'devolucion'){

          $entrada = DB::table('entradas')->where('entradas.id',$id)
              ->join('departamentos', 'entradas.provedor', '=', 'departamentos.id')
              ->join('depositos', 'depositos.id', '=', 'entradas.deposito')
              ->join('users', 'entradas.usuario' , '=', 'users.id' )
              ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                  DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                  'entradas.orden', 'departamentos.nombre as provedor', 'users.email as usuario', 'entradas.type',
                  'depositos.nombre as deposito')
              ->first();

          $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
              ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
              ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad', 'insumos_entradas.lote',
                      'insumos_entradas.fechaV as fecha')
              ->get();
      }
      else{

          $entrada = DB::table('entradas')->where('entradas.id',$id)
              ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
              ->join('depositos', 'depositos.id', '=', 'entradas.deposito')
              ->join('users', 'entradas.usuario' , '=', 'users.id' )
              ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                  DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                  'entradas.orden', 'provedores.nombre as provedor', 'users.email as usuario', 'entradas.type',
                  'depositos.nombre as deposito')
              ->first();

          $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
              ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
              ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad','insumos_entradas.lote',
                      'insumos_entradas.fechaV as fecha')
              ->get();
      }

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

      $salida = DB::table('salidas')->where('salidas.id',$id)
           ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
           ->join('depositos', 'depositos.id', '=', 'salidas.deposito')
           ->join('users', 'salidas.usuario' , '=', 'users.id' )
           ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),
               DB::raw('DATE_FORMAT(salidas.created_at, "%H:%i:%s") as hora'), 'salidas.codigo',
               'departamentos.nombre as departamento', 'users.email as usuario', 'salidas.id',
               'depositos.nombre as deposito')
           ->first();

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
          'insumo'  => 'required|integer|insumo_with_daleted',
          'dateI'   => 'required|date_format:d/m/Y',
          'dateF'   => 'required|date_format:d/m/Y'
      ]);

      if($validator->fails()){
        abort("404");
      }

      $deposito = Auth::user()->deposito;
      $insumo   = $data['insumo'];

      //Fecha inicial a consultar
      $dateConvert = str_replace('/', '-', $data['dateI']);
      $dateI = Date("Y-m-d", strtotime($dateConvert));

      //Fecha final a consultar
      $dateConvert = str_replace('/', '-', $data['dateF']);
      $dateF = Date("Y-m-d", strtotime($dateConvert));

      //Obtiene todas las entradas que han entrado por devolucion.
      $devoluciones =  DB::table('insumos_entradas')->where('insumo', $insumo)
                  ->where('insumos_entradas.deposito', $deposito)
                  ->where('insumos_entradas.type', 'devolucion')
                  ->join('entradas', 'insumos_entradas.entrada' , '=', 'entradas.id')
                  ->join('departamentos', 'entradas.provedor' , '=', 'departamentos.id')
                  ->whereBetween(DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%Y-%m-%d")'), [$dateI, $dateF])
                  ->select('cantidad as movido', 'insumos_entradas.created_at as fulldate',
                  DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%d/%m/%Y") as fecha'), DB::raw('("entrada") as type'),
                  'departamentos.nombre as pod');

      //Obtiene todas las entradas que han entrado por todos los conceptos excluye devolucion.
      $entradas   =  DB::table('insumos_entradas')->where('insumo', $insumo)
                  ->where('insumos_entradas.deposito', $deposito)
                  ->where('insumos_entradas.type', '!=', 'devolucion')
                  ->join('entradas', 'insumos_entradas.entrada' , '=', 'entradas.id')
                  ->leftjoin('provedores', 'entradas.provedor' , '=', 'provedores.id')
                  ->whereBetween(DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%Y-%m-%d")'), [$dateI, $dateF])
                  ->select('cantidad as movido', 'insumos_entradas.created_at as fulldate',
                  DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%d/%m/%Y") as fecha'), DB::raw('("entrada") as type'),
                  'provedores.nombre as pod');

      //Obtiene todas las salidas.
      $salidas   = DB::table('insumos_salidas')->where('insumo',$insumo)
                     ->where('insumos_salidas.deposito', $deposito)
                     ->join('salidas', 'insumos_salidas.salida' , '=', 'salidas.id')
                     ->join('departamentos', 'salidas.departamento' , '=', 'departamentos.id')
                     ->whereBetween(DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%Y-%m-%d")'),[$dateI, $dateF])
                     ->select('despachado as movido', 'insumos_salidas.created_at as fulldate',
                     DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%d/%m/%Y") as fecha'), DB::raw('("salida") as type'),
                     'departamentos.nombre as pod');

       //Une y realiza las consultas de todos los registros.
       $movimientos =  $salidas->unionAll($entradas)
                       ->unionAll($devoluciones)
                       ->orderBy('fulldate','asc')
                       ->get();

        /**
         *Si ha hay movimientos del insumo consultado
         *se calcula la existencia en la que se encontraba
         *despues de cada movimiento.
         */
        if(!empty($movimientos)){

          //Año inicial del rango de fecha a consultar
          $init_year_search = date('Y-01-01 00:00:00',strtotime($dateI));

          foreach ($movimientos as $movimiento){
            //Obtiene la fecha de la ultima carga de inventario realizada en el primer año del rango de fecha a consultar
            $last_cinve = DB::table('insumos_entradas')->where('insumo', $insumo)
                          ->where('deposito', $deposito)
                          ->where('type','cinventario')
                          ->whereBetween('created_at', [$init_year_search, $movimiento->fulldate])
                          ->orderBy('id', 'desc')
                          ->value('created_at');
            /**
             *Si se ha encontrado una carga de inventario en el primer año del rango de fecha a consultar,
             *construye consulta que obtienen todas las entradas y salidas desde la fecha de dicha carga de
             *inventario hasta la fecha del primer movimiento encontrado, de lo contrario construye consulta
             *que obtiene todas las entradas y salidas desde el primer año del rango de fecha a consultar
             *hasta la fecha del primer movimiento encontrado.
             */
            if(!empty($last_cinve) ){

              $queryE = Insumos_entrada::where('insumo', $insumo)
                     ->where('deposito', $deposito)
                     ->whereBetween('created_at',[$last_cinve, $movimiento->fulldate]);

              $queryS = Insumos_salida::where('insumo', $insumo)
                     ->where('deposito', $deposito)
                     ->whereBetween('created_at',[$last_cinve, $movimiento->fulldate]);
            }
            else{

              $queryE = Insumos_entrada::where('insumo', $insumo)
                     ->where('deposito', $deposito)
                     ->whereBetween('created_at',[$init_year_search, $movimiento->fulldate]);

              $queryS = Insumos_salida::where('insumo', $insumo)
                     ->where('deposito', $deposito)
                     ->whereBetween('created_at',[$init_year_search, $movimiento->fulldate]);
            }

            /**
             *Realiza la consulta que Obtiene la cantidad de salidas y entradas
             *de los movimientos de la consultas que se almacenan en $queryE, $queryS.
             */
            $entradaM = $queryE->sum('cantidad');
            $salidaM  = $queryS->sum('despachado');

            //Calcula la existencia inicial del insumo antes de los movimientos consultados.
            $existencia = $entradaM - $salidaM;
            $movimiento->existencia = $existencia;

            if($movimiento->pod == null){
              $movimiento->pod = "CARGA DE INVENTARIO";
            }
         }
       }

       //Obtiene la informacion del insumo
       $insumoData = DB::table('insumos')->where('id', $data['insumo'])->first(['codigo', 'descripcion']);
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
