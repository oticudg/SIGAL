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
use App\Deposito;
use App\Departamento;

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

    public function viewSearch(){
        return view('salidas/searchSalidas');
    }

    public function detalles(){
        return view('salidas/detallesSalida');
    }

    public function allInsumos(){

        $deposito = Auth::user()->deposito;

        return DB::table('insumos_salidas')
            ->where('salidas.deposito', $deposito)
            ->join('salidas', 'salidas.id', '=', 'insumos_salidas.salida')
            ->join('insumos', 'insumos.id' , '=', 'insumos_salidas.insumo')
            ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo as salida',
                'insumos.codigo','salidas.id as salidaId','insumos.descripcion','insumos_salidas.solicitado',
                'insumos_salidas.despachado')
            ->orderBy('insumos_salidas.id', 'desc')->get();
    }

    public function allSalidas(){

        $deposito = Auth::user()->deposito;

        return DB::table('salidas')
            ->where('salidas.deposito', $deposito)
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
                    'departamentos.nombre as departamento', 'users.email as usuario', 'salidas.id')
                ->first();

           $insumos = DB::table('insumos_salidas')->where('insumos_salidas.salida', $id)
                ->join('insumos', 'insumos_salidas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_salidas.solicitado',
                	'insumos_salidas.despachado')
                ->get();

            return Response()->json(['status' => 'success', 'nota' => $salida , 'insumos' => $insumos]);
        }
    }

    public function getSalidaCodigo($code){

        $deposito = Auth::user()->deposito;
        $depositoCode = Deposito::where('id', $deposito)->value('codigo');
        $realCode = $depositoCode.'-'.$code;

        $salida = Salida::where('codigo',$realCode)->first();

        if(!$salida){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Salida no existe']);
        }
        else{

           $salida = DB::table('salidas')->where('salidas.codigo',$realCode)
                ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
                ->select('salidas.codigo','salidas.id',
                    'departamentos.nombre as departamento')
                ->first();

           $insumos = DB::table('salidas')->where('salidas.codigo', $realCode)
                ->join('insumos_salidas', 'salidas.id', '=', 'insumos_salidas.salida')
                ->join('insumos', 'insumos_salidas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_salidas.despachado',
                    'insumos_salidas.solicitado','insumos_salidas.id as id')
                ->get();

            return Response()->json(['status' => 'success', 'salida' => $salida, 'insumos' => $insumos]);
        }
    }

    public function search(Request $request){

      $deposito = Auth::user()->deposito;

      $query = DB::table('salidas')
              ->where('salidas.deposito', $deposito)
              ->join('departamentos', 'salidas.departamento', '=', 'departamentos.id')
              ->select(DB::raw('DATE_FORMAT(salidas.created_at, "%d/%m/%Y") as fecha'),'salidas.codigo',
              'departamentos.nombre as departamento', 'salidas.id');

      //Filtro para buscar salidas por rangos de fecha
      if($request->dateranger){
        $query->whereBetween(DB::raw('DATE_FORMAT(salidas.created_at, "%Y-%m-%d")'),
          [$request->fechaI,$request->fechaF]);
      }

      //Filtro para buscar salidas por rangos de horas
      if($request->hourrange){
        $query->whereBetween(DB::raw('DATE_FORMAT(salidas.created_at, "%H-%i")'),
          [$request->horaI,$request->horaF]);
      }

      //Filtro para buscar salidas segun un departamento
      if($request->depart){
        $query->where('salidas.departamento',$request->depart);
      }

      //Filtro para buscar salidas segun un usuario
      if($request->user){
        $query->where('usuario',$request->user);
      }

      //Filtro para buscar salida segun un insumo
      if($request->insumo){
        $query->join('insumos_salidas', 'insumos_salidas.salida', '=', 'salidas.id')
          ->where('insumos_salidas.insumo', $request->insumo);

         //Filtro para buscar salidas segun rangos de cantidad del insumo
         if($request->amountrange){
           $query->whereBetween('insumos_salidas.despachado',
            [$request->cantidadI,$request->cantidadF]);
         }
      }

      //Filtro para ordenar los resultados de forma decendente o acendentes
      if($request->orden){
        $query->orderBy('id',$request->orden);
      }
      else{
        $query->orderBy('id','decs');
      }

      return $query->get();
    }

    public function registrar(Request $request){

        $data = $request->all();
        $usuario  = Auth::user()->id;
        $deposito = Auth::user()->deposito;

        $validator = Validator::make($data,[
            'documento' =>  'required|numeric|documento_salida',
            'tercero'   =>  'required|numeric|tercero:documento',
            'insumos'   =>  'required|insumos_salida'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            $insumos = $data['insumos'];
            $insumosInvalidos = inventarioController::validaExist($insumos, $deposito);

            if($insumosInvalidos){
              return Response()->json(['status' => 'unexist', 'data' => $insumosInvalidos]);
            }
            else{
              //Codigo para la salida
              $code = $this->generateCode('S', $deposito);

              $salida = Salida::create([
                          'codigo'       => $code,
                          'tercero'      => $data['tercero'],
                          'documento'    => $data['documento'],
                          'usuario'      => $usuario,
                          'deposito'     => $deposito
                      ])['id'];

              foreach ($insumos as $insumo) {

                  Insumos_salida::create([
                      'salida'      => $salida,
                      'insumo'      => $insumo['id'],
                      'solicitado'  => $insumo['solicitado'],
                      'despachado'  => $insumo['despachado'],
                      'deposito'    => $deposito
                  ]);

                  inventarioController::reduceInsumo($insumo['id'], $insumo['despachado'], $deposito, 'salida',
                      $salida);

              }

              return Response()->json(['status' => 'success', 'menssage' =>
                  'Salida completada satisfactoriamente', 'codigo' => $code]);
            }
        }
    }

    /*Funcion que genera codigos para las salidas,
     *segun un prefijo y deposito que se pase
     */
    private function generateCode($prefix, $deposito){

        //Obtiene Codigo del deposito
        $depCode = Deposito::where('id' , $deposito)->value('codigo');

        return strtoupper( $depCode .'-'.$prefix.str_random(7) );
    }
}
