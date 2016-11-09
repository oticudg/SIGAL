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
use App\Insumo;
use App\Departamento;

class estadisticasController extends Controller
{ 
    private $menssage = [
        'insumo.required'      =>  'Seleccione un insumo a consultar',
        'fechaI.required'      =>  'Seleccione una fecha inicial a consultar', 
        'fechaF.required'      =>  'Seleccione una fecha final a consultar',
        'insumo'               =>  'El insumo es invalido',
        'servicio.required'    =>  'Seleccione un servicio a consultar'
    ];

    public function index(){

        return view('estadisticas/indexEstadisticas');
    }

    public function getServicios(){

        $fecha = date("Y-m");
        $datos = ['Sdata' => [], 'Ddata' => [], "title" => ''];
        $deposito = Auth::user()->deposito; 

        $salidas  = DB::table('salidas')->where( DB::raw('DATE_FORMAT(salidas.created_at,"%Y-%m")'), $fecha)
                   ->where('salidas.deposito', $deposito)
                   ->join('departamentos', 'salidas.tercero', '=', 'departamentos.id')
                   ->select('departamentos.nombre as name', 
                      DB::raw('count(*) as total'), 'salidas.tercero as id')
                   ->groupBy('salidas.tercero')
                   ->orderBy('total', 'desc')
                   ->get();

        foreach($salidas as $salida){
            
            $insumos = DB::table('salidas')->where(DB::raw('DATE_FORMAT(salidas.created_at,"%Y-%m")'), $fecha)
                      ->where('salidas.tercero', $salida->id)
                      ->join('insumos_salidas', 'insumos_salidas.salida', '=', 'salidas.id')
                      ->join('insumos', 'insumos.id', '=', 'insumos_salidas.insumo')
                      ->select('insumos.descripcion as name', DB::raw('sum(insumos_salidas.despachado) as total'))
                      ->groupBy('insumos_salidas.insumo')
                      ->orderBy('total','desc')
                      ->get();

            $datosInsumos = [];

            foreach ($insumos as $insumo){
                
                array_push($datosInsumos,[$insumo->name, $insumo->total]);
            }


            array_push($datos['Sdata'], 
                      ['name' => $salida->name, 'y' => $salida->total, 
                      'drilldown' => $salida->name]);

            array_push($datos['Ddata'], 
              ['name' => $salida->name, 'id' => $salida->name, 
              'data' => $datosInsumos]);
        }

        $datos['title'] = 'Salidas de '.date("F").' del '.date("Y");

        return $datos;
    }

    public function getInsumo(Request $request){

      $data = $request->all(); 

      $validator = Validator::make($data,[
          'insumo'   =>  'required|insumo',
          'fechaI'   =>  'required',
          'fechaF'   =>  'required'
      ], $this->menssage);

      if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
      else{

          $deposito = Auth::user()->deposito; 
          $insumo = Insumo::where('id', $data['insumo'])->value('descripcion');

          $insumos = DB::table('insumos_salidas')->whereBetween(DB::raw('DATE_FORMAT(insumos_salidas.created_at,"%Y-%m-%d")'), 
                      [$data['fechaI'], $data['fechaF'] ])
                      ->where('insumos_salidas.insumo', $data['insumo'])
                      ->where('insumos_salidas.deposito', $deposito)
                      ->join('salidas', 'insumos_salidas.salida', '=', 'salidas.id')
                      ->join('departamentos', 'salidas.tercero', '=', 'departamentos.id')
                      ->select('departamentos.nombre as name', DB::raw('sum(insumos_salidas.despachado) as y'))
                      ->groupBy('departamentos.nombre')
                      ->orderBy('y','desc')
                      ->get();

         $title = 'Salidas de '.$insumo.', del ('.$data['fechaI'].' a '.$data['fechaF'].')'; 

         return ['status' => 'success' , 'data' => $insumos, 'title' => $title];
      }
    }

    public function getServicio(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'servicio'   =>  'required',
          'fechaI'   =>  'required',
          'fechaF'   =>  'required'
      ], $this->menssage);

      if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
        }
      else{

          $deposito = Auth::user()->deposito;
          $servicio =  Departamento::where('id', $data['servicio'])->value('nombre');
          
          $insumos = DB::table('salidas')->whereBetween(DB::raw('DATE_FORMAT(insumos_salidas.created_at,"%Y-%m-%d")'), 
                      [$data['fechaI'], $data['fechaF'] ])
                      ->where('salidas.tercero', $data['servicio'])
                      ->where('salidas.deposito', $deposito)
                      ->join('insumos_salidas', 'insumos_salidas.salida', '=', 'salidas.id')
                      ->join('insumos', 'insumos.id', '=', 'insumos_salidas.insumo')
                      ->select('insumos.descripcion as name', DB::raw('sum(insumos_salidas.despachado) as y'))
                      ->groupBy('insumos.descripcion')
                      ->orderBy('y','desc')
                      ->get();
        
         $title = 'Salidas de '.$servicio.', del ('.$data['fechaI'].' a '.$data['fechaF'].')'; 

         return ['status' => 'success' , 'data' => $insumos, 'title' => $title];
      }

    }

}
