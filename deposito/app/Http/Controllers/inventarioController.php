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

class inventarioController extends Controller
{
    private $menssage = [
       'insumos.required'  =>  'No se han especificado insumos para esta entrada'
    ];

    public function index(){
        return view('inventario/indexInventario');
    }

    public function viewHerramientas(){
        return view('inventario/herramientasInventario');
    }

    public function viewInsumosAlertas(){
        return view('inventario/nivelesInventario');
    }

    public function viewCargaInventario(){
        return view('inventario/herramientasCargaInventario');
    }

    public function viewDetallesCarga(){
        return view('inventario/detallesInventarioCarga');
    }


    public function viewKardek(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'insumo'  => 'required|insumo',
          'dateI'   => 'date',
          'dateF'   => 'date'
      ], $this->menssage);

      if($validator->fails()){
        abort('404');
      }

      return view('inventario/kardek', ['insumo' => $data['insumo']]);
    }

    public function allInsumos(){

        $deposito = Auth::user()->deposito;

        return Inventario::select(
            "inventarios.insumo as id",
            "insumos.codigo",
            "insumos.descripcion",
            "inventarios.existencia",
            "inventarios.Cmin as min",
            "inventarios.Cmed as med",
            "total_entradas",
            DB::raw('IFNULL(total_salidas, 0) as total_salidas')
          )->where('inventarios.deposito', $deposito)
           ->join('insumos', 'insumos.id', '=', 'inventarios.insumo')
           ->leftjoin( DB::raw("(select sum(entradas.cantidad) total_entradas, entradas.insumo
                  from insumos_entradas as entradas
                  where entradas.deposito = ".$deposito."
                  group by entradas.insumo
                ) entradas
            "), 'inventarios.insumo', '=', 'entradas.insumo')
            ->leftjoin( DB::raw("(select sum(salidas.despachado) total_salidas, salidas.insumo
                   from insumos_salidas as salidas
                   where salidas.deposito = ".$deposito."
                   group by salidas.insumo
                 ) salidas
             "), 'inventarios.insumo', '=', 'salidas.insumo')
            ->orderBy('inventarios.id', 'desc')
            ->get();
    }
    public function getInsumosAlert(Request $request){

        $deposito = Auth::user()->deposito;
        $consulta = $request->input('insumo');

        if($consulta != ""){

            return DB::table('insumos')
                        ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                        ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                            'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
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
                                'Cmed' => $insumo['med'] ]);
            }

            return Response()->json(['status' => 'success', 'menssage' =>
                'Alarmas configuradas exitosamente']);
        }

    }

    public function insumosAlert(){

        $deposito = Auth::user()->deposito;

        $registros = Inventario::where('deposito', $deposito)
                                 ->get(['id', 'existencia', 'Cmed', 'Cmin']);
        $ids = [];

        foreach ($registros as $registro) {
            if( $registro['existencia'] <= $registro['Cmed'] || $registro['existencia'] <= $registro['Cmin'])
                array_push($ids, $registro['id']);
        }

        $insumos = DB::table('insumos')
                   ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                   ->whereIn('inventarios.id', $ids)
                   ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                    'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
                   ->get();

        return $insumos;

    }

    public function carga(Request $request){

        $data     = $request->all();
        $usuario  = Auth::user()->id;
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'insumos'  =>  'required|insumos'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            $insumos = $data['insumos'];
            //Codigo para la entrada
            $code = $this->generateCode('CI', $deposito);

            Inventario::where('deposito', $deposito)->delete();

            $entrada = Entrada::create([
                        'codigo'   => $code,
                        'type'     => 'cinventario',
                        'usuario'  => $usuario,
                        'deposito' => $deposito
                    ])['id'];

            foreach ($insumos as $insumo){

                Insumos_entrada::create([
                    'entrada'   => $entrada,
                    'insumo'    => $insumo['id'],
                    'cantidad'  => $insumo['cantidad'],
                    'type'      => 'cinventario',
                    'deposito'  => $deposito
                ]);

                inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad'], $deposito, 'carga-inventario', $entrada);
            }

            return Response()->json(['status' => 'success', 'menssage' =>
            'Inventario cargado satisfactoriamente', 'codigo' => $code]);

        }
    }

    public function allInventarioCargas(){

        $deposito = Auth::user()->deposito;

        return DB::table('entradas')
                    ->where('type', 'cinventario')
                    ->where('deposito', $deposito)
                    ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo',
                        'entradas.id')
                     ->orderBy('entradas.id', 'desc')->get();
    }

    public function getCarga($id){

        $deposito = Auth::user()->deposito;

        $entrada = Entrada::where('id',$id)
                            ->where('deposito', $deposito)
                            ->where('type', 'cinventario')
                            ->first();
        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta carga de inventario no existe']);
        }
        else{

            $entrada = DB::table('entradas')->where('entradas.id',$id)
                ->join('users', 'entradas.usuario' , '=', 'users.id' )
                ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                    DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                    'users.email as usuario',  'entradas.id')
                ->first();

            $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public function kardek(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'insumo'  => 'required|insumo',
          'dateI'   => 'date',
          'dateF'   => 'date'
      ], $this->menssage);

      if($validator->fails()){
        return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
      }

      //Fecha inicial del año en curso
      $iniY  = date("Y-01-01");
      //Fecha final del año en curso
      $finY  = date("Y-12-31");
      //Fecha inicial a consultar
      $dateI = !empty($data['dateI']) ? $data['dateI']:$iniY;
      //Fecha final a consultar
      $dateF = !empty($data['dateF']) ? $data['dateF']:$finY;
      //Insumo para el que se realizara el kardek
      $insumo = $data['insumo'];
      //Obtiene el deposito del usuario que realiza la consulta.
      $deposito = Auth::user()->deposito;

      //Obtiene todas las entradas que han entrado por devolucion.
      $devoluciones =  DB::table('insumos_entradas')->where('insumo', $insumo)
                  ->where('insumos_entradas.deposito', $deposito)
                  ->where('insumos_entradas.type', 'devolucion')
                  ->join('entradas', 'insumos_entradas.entrada' , '=', 'entradas.id')
                  ->join('departamentos', 'entradas.provedor' , '=', 'departamentos.id')
                  ->whereBetween(DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%Y-%m-%d")'), [$dateI, $dateF])
                  ->select('cantidad as movido', 'entrada as referencia', 'insumos_entradas.created_at as fulldate',
                  DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%d/%m/%Y") as fecha'), DB::raw('("entrada") as type'),
                  'departamentos.nombre as pod');

      //Obtiene todas las entradas que han entrado por todos los conceptos excluye devolucion.
      $entradas   =  DB::table('insumos_entradas')->where('insumo', $insumo)
                  ->where('insumos_entradas.deposito', $deposito)
                  ->where('insumos_entradas.type', '!=', 'devolucion')
                  ->join('entradas', 'insumos_entradas.entrada' , '=', 'entradas.id')
                  ->leftjoin('provedores', 'entradas.provedor' , '=', 'provedores.id')
                  ->whereBetween(DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%Y-%m-%d")'), [$dateI, $dateF])
                  ->select('cantidad as movido', 'entrada as referencia', 'insumos_entradas.created_at as fulldate',
                  DB::raw('DATE_FORMAT(insumos_entradas.created_at, "%d/%m/%Y") as fecha'), DB::raw('("entrada") as type'),
                  'provedores.nombre as pod');

      //Obtiene todas las salidas y une los resultados con las entradas y las devoluciones.
      $movimientos = DB::table('insumos_salidas')->where('insumo',$insumo)
                     ->where('insumos_salidas.deposito', $deposito)
                     ->join('salidas', 'insumos_salidas.salida' , '=', 'salidas.id')
                     ->join('departamentos', 'salidas.departamento' , '=', 'departamentos.id')
                     ->whereBetween(DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%Y-%m-%d")'),[$dateI, $dateF])
                     ->select('despachado as movido', 'salida as referencia', 'insumos_salidas.created_at as fulldate',
                     DB::raw('DATE_FORMAT(insumos_salidas.created_at, "%d/%m/%Y") as fecha'), DB::raw('("salida") as type'),
                     'departamentos.nombre as pod')
                     ->unionAll($entradas)
                     ->unionAll($devoluciones)
                     ->orderBy('fulldate','desc')
                     ->get();

      /**
       *Si ha hay movimientos del insumo consultado
       *se calcula la existencia en la que se encontraba
       *despues de cada movimiento.
       */
      if(!empty($movimientos)){

        //Año inicial del rango de fecha a consultar
        $init_year_search = date('Y-01-01 00:00:00',strtotime($dateI));

        //Obtiene la fecha de la ultima carga de inventario realizada en el primer año del rango de fecha a consultar
        $last_cinve = DB::table('insumos_entradas')->where('insumo', $insumo)
                      ->where('deposito', $deposito)
                      ->where('type','cinventario')
                      ->whereBetween('created_at', [$init_year_search, $movimientos[0]->fulldate])
                      ->orderBy('id', 'desc')
                      ->value('created_at');

        //Reversa el orden de los movimentos encontrados.
        $movimientosR = array_reverse($movimientos);

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
                 ->whereBetween('created_at',[$last_cinve, $movimientosR[0]->fulldate]);

          $queryS = Insumos_salida::where('insumo', $insumo)
                 ->where('deposito', $deposito)
                 ->whereBetween('created_at',[$last_cinve, $movimientosR[0]->fulldate]);
        }
        else{

          $queryE = Insumos_entrada::where('insumo', $insumo)
                 ->where('deposito', $deposito)
                 ->whereBetween('created_at',[$init_year_search, $movimientosR[0]->fulldate]);

          $queryS = Insumos_salida::where('insumo', $insumo)
                 ->where('deposito', $deposito)
                 ->whereBetween('created_at',[$init_year_search, $movimientosR[0]->fulldate]);
        }

        /**
         *Excluye el primer movimento encontrado en el rango de fecha a consultar
         *sea salida o entrada.
         */
        if($movimientosR[0]->type == "entrada")
          $queryE->where('entrada','!=',$movimientosR[0]->referencia);
        else
          $queryS->where('salida','!=',$movimientosR[0]->referencia);

        /**
         *Realiza la consulta que Obtiene la cantidad de salidas y entradas
         *de los movimientos de la consultas que se almacenan en $queryE, $queryS.
         */
        $entradaM = $queryE->sum('cantidad');
        $salidaM  = $queryS->sum('despachado');

        //Calcula la existencia inicial del insumo antes de los movimientos consultados.
        $existencia = $entradaM - $salidaM;

        //Calcula y asigna la existencia del insumo despues de cada movimento
        foreach($movimientosR as $movimiento){

          /**
           *Si el movimento es una carga de inventario la existencia se establece
           *como la cantidad dal movimiento.
           */
          if($movimiento->pod == null){
            $existencia = $movimiento->movido;
            $movimiento->existencia = $existencia;
            $movimiento->pod = "CARGA DE INVENTARIO";
          }
          else{

            if($movimiento->type == 'entrada')
              $existencia += $movimiento->movido;
            else
              $existencia -= $movimiento->movido;

            $movimiento->existencia = $existencia;
          }
        }

        //Obtiene la informacion del insumo
        $insumoData = Insumo::where('id', $insumo)->first(['codigo', 'descripcion']);

        return Response()->json(['status' => 'success', 'kardek' => $movimientos, 'insumo' => $insumoData]);
     }

    }

    public static function almacenaInsumo($insumo, $cantidad, $deposito, $type, $referencia){

    	$inventario = Inventario::where('insumo',$insumo)
                      ->where('deposito', $deposito)
                      ->first();

        if( $inventario ){

    		$existencia = Inventario::where('insumo', $insumo)
                                      ->where('deposito', $deposito)
                                      ->value('existencia');

            Inventario_operacione::create([
                    'insumo'     => $insumo,
                    'type'       => $type,
                    'referencia' => $referencia,
                    'existencia' => $existencia
            ]);

    		$existencia += $cantidad;

    		Inventario::where('insumo' , $insumo)
                        ->where('deposito', $deposito)
                        ->update(['existencia' => $existencia]);
    	}
    	else{

    		Inventario::create([
    			'insumo'     => $insumo,
    			'existencia' => $cantidad,
                'deposito'   => $deposito
    		]);
    	}
    }

    public static function reduceInsumo($insumo, $cantidad, $deposito, $type, $referencia){

        $inventario = Inventario::where('insumo', $insumo)
                                  ->where('deposito', $deposito)
                                  ->first();

        if( $inventario ){

            $existencia = Inventario::where('insumo', $insumo)
                                      ->where('deposito', $deposito)
                                      ->value('existencia');

            Inventario_operacione::create([
                    'insumo'     => $insumo,
                    'type'       => $type,
                    'referencia' => $referencia,
                    'existencia' => $existencia
            ]);

            $existencia -= $cantidad;

            Inventario::where('insumo' , $insumo)
                        ->where('deposito', $deposito)
                        ->update(['existencia' => $existencia]);
        }
    }

    public static function validaExist($insumos, $deposito){

        $invalidos = [];

        foreach ($insumos as $insumo){

            $inventario = Inventario::where('insumo' , $insumo['id'])
                          ->where('deposito', $deposito)
                          ->first();

            $existencia = Inventario::where('insumo' , $insumo['id'])
                          ->where('deposito', $deposito)
                          ->value('existencia');

            if( !$inventario || $existencia < $insumo['despachado'])
                array_push($invalidos, $insumo['id']);
        }

        return $invalidos;
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
     * prefijo
     */
    private function generateCode($prefix,$deposito){

        //Obtiene Codigo del deposito
        $depCode = Deposito::where('id' , $deposito)->value('codigo');

        return strtoupper( $depCode .'-'.$prefix.str_random(6) );
    }

}
