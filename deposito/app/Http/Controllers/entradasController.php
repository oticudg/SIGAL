<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entrada;
use App\Edonacione;
use App\Edevolucione;
use App\Insumos_entrada;
use App\Insumos_edonacione;
use App\Insumos_edevolucione;

class entradasController extends Controller
{   
    private $menssage = [
        'orden.required'    =>   'Especifique un numero de orden de compra',
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

    public function allInsumos($type = NULL){
        switch($type){
            
            case 'orden':
                
                return DB::table('insumos_entradas')
                    ->join('entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
                    ->join('insumos', 'insumos.id' , '=', 'insumos_entradas.insumo')
                    ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo as entrada',
                        'entradas.orden','entradas.id as entradaId','insumos.codigo',
                        'insumos.descripcion','insumos_entradas.cantidad')
                    ->orderBy('insumos_entradas.id', 'desc')->get();
            break;
            
            case 'donacion':
                return DB::table('insumos_edonaciones')
                    ->join('edonaciones', 'edonaciones.id', '=', 'insumos_edonaciones.donacion')
                    ->join('insumos', 'insumos.id' , '=', 'insumos_edonaciones.insumo')
                    ->select(DB::raw('DATE_FORMAT(edonaciones.created_at, "%d/%m/%Y") as fecha'),
                        'edonaciones.codigo as donacion', 'edonaciones.id as donacionId','insumos.codigo',
                        'insumos.descripcion','insumos_edonaciones.cantidad')
                    ->orderBy('insumos_edonaciones.id', 'desc')->get();
            break;

            case 'devolucion':
                return DB::table('insumos_edevoluciones')
                    ->join('edevoluciones', 'edevoluciones.id', '=', 'insumos_edevoluciones.devolucion')
                    ->join('insumos', 'insumos.id' , '=', 'insumos_edevoluciones.insumo')
                    ->select(DB::raw('DATE_FORMAT(edevoluciones.created_at, "%d/%m/%Y") as fecha'),
                        'edevoluciones.codigo as devolucion', 'edevoluciones.id as devolucionId','insumos.codigo',
                        'insumos.descripcion','insumos_edevoluciones.cantidad')
                    ->orderBy('insumos_edevoluciones.id', 'desc')->get();
            break;

            default:
                echo "asdasd";
            break;
        }
    }

    public function allEntradas($type = NULL){

        switch ($type) {
        
            case 'orden':
                return DB::table('entradas')
                    ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                    ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo',
                        'entradas.orden','provedores.nombre as provedor', 'entradas.id')
                     ->orderBy('entradas.id', 'desc')->get();
            break;

            case 'donacion':
                return DB::table('edonaciones')
                    ->join('provedores', 'edonaciones.provedor', '=', 'provedores.id')
                    ->select(DB::raw('DATE_FORMAT(edonaciones.created_at, "%d/%m/%Y") as fecha'),
                        'edonaciones.codigo','provedores.nombre as provedor', 'edonaciones.id')
                     ->orderBy('edonaciones.id', 'desc')->get();
            break;

            case 'devolucion':
                return DB::table('edevoluciones')
                    ->join('departamentos', 'edevoluciones.departamento', '=', 'departamentos.id')
                    ->select(DB::raw('DATE_FORMAT(edevoluciones.created_at, "%d/%m/%Y") as fecha'),
                        'edevoluciones.codigo','departamentos.nombre as provedor', 'edevoluciones.id')
                     ->orderBy('edevoluciones.id', 'desc')->get();
            break;


            default:
                echo "asdasd";
            break;
        }
    }   

    public function getEntrada($type, $id){
        
        switch ($type) {

            case 'orden':
                $entrada = Entrada::where('id',$id)->first();

                if(!$entrada){
                    return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
                }
                else{

                   $entrada = DB::table('entradas')->where('entradas.id',$id)
                        ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                        ->join('users', 'entradas.usuario' , '=', 'users.id' )
                        ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                            DB::raw('DATE_FORMAT(entradas.created_at, "%H:%i:%s") as hora'), 'entradas.codigo',
                            'entradas.orden', 'provedores.nombre as provedor', 'users.email as usuario')
                        ->first();

                   $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                        ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                        ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                        ->get();

                    return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
                }
            break;

            case 'donacion':
                $entrada = Edonacione::where('id',$id)->first();

                if(!$entrada){
                    return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
                }
                else{

                   $entrada = DB::table('edonaciones')->where('edonaciones.id',$id)
                        ->join('provedores', 'edonaciones.provedor', '=', 'provedores.id')
                        ->join('users', 'edonaciones.usuario' , '=', 'users.id' )
                        ->select(DB::raw('DATE_FORMAT(edonaciones.created_at, "%d/%m/%Y") as fecha'),
                            DB::raw('DATE_FORMAT(edonaciones.created_at, "%H:%i:%s") as hora'), 'edonaciones.codigo',
                           'provedores.nombre as provedor', 'users.email as usuario')
                        ->first();

                   $insumos = DB::table('insumos_edonaciones')->where('insumos_edonaciones.donacion', $id)
                        ->join('insumos', 'insumos_edonaciones.insumo', '=', 'insumos.id')
                        ->select('insumos.codigo', 'insumos.descripcion', 'insumos_edonaciones.cantidad')
                        ->get();

                    return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
                }
            break;

            case 'devolucion':
                $entrada = Edevolucione::where('id',$id)->first();

                if(!$entrada){
                    return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
                }
                else{

                   $entrada = DB::table('edevoluciones')->where('edevoluciones.id',$id)
                        ->join('departamentos', 'edevoluciones.departamento', '=', 'departamentos.id')
                        ->join('users', 'edevoluciones.usuario' , '=', 'users.id' )
                        ->select(DB::raw('DATE_FORMAT(edevoluciones.created_at, "%d/%m/%Y") as fecha'),
                            DB::raw('DATE_FORMAT(edevoluciones.created_at, "%H:%i:%s") as hora'), 'edevoluciones.codigo',
                           'departamentos.nombre as provedor', 'users.email as usuario')
                        ->first();

                   $insumos = DB::table('insumos_edevoluciones')->where('insumos_edevoluciones.devolucion', $id)
                        ->join('insumos', 'insumos_edevoluciones.insumo', '=', 'insumos.id')
                        ->select('insumos.codigo', 'insumos.descripcion', 'insumos_edevoluciones.cantidad')
                        ->get();

                    return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
                }
            break;

            default:
                 return Response()->json(['status' => 'danger', 'menssage' => 'Tipo de entrada no valido']);
            break; 
        }
    }

    public function getEntradaCodigo($code){

        $entrada = Entrada::where('codigo',$code)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta entrada no existe']);            
        }
        else{

           $entrada = DB::table('entradas')->where('entradas.codigo',$code)
                ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                ->select('entradas.codigo','entradas.orden','entradas.id', 
                    'provedores.nombre as provedor')
                ->first();

           $insumos = DB::table('entradas')->where('entradas.codigo', $code)
                ->join('insumos_entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad', 'insumos_entradas.id as id')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }

    public function getOrden($number){

        $entrada = Entrada::where('orden',$number)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Orden no existe']);   
        }
        else{

            $orden = DB::table('entradas')->where('entradas.orden', $number)
                     ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
                     ->select('entradas.orden as numero', 'provedores.nombre as provedor')
                     ->first();  

            $entradas = Entrada::where('entradas.orden',$number)->lists('id');
            
            $insumos  = DB::table('insumos_entradas')->whereIn('entrada', $entradas)
                        ->join('entradas', 'insumos_entradas.entrada', '=', 'entradas.id')
                        ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                        ->select('entradas.codigo as entrada','insumos.codigo as codigo',
                            DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),
                            'entradas.id as entradaId','insumos.descripcion as descripcion',
                            'insumos_entradas.cantidad as cantidad')
                        ->orderBy('insumos_entradas.id', 'desc')->get();

            return Response()->json(['status' => 'success', 'orden' => $orden, 'insumos' => $insumos]);
            
        }
    }

    public function registrar($type, Request $request){
        
        $data = $request->all();

        switch ($type){

            case 'orden':

                $validator = Validator::make($data,[
                    'orden'   =>  'required|',
                    'provedor' =>  'required|equal_provedor:orden',
                    'insumos'  =>  'required|insumos'
                ], $this->menssage);

                if($validator->fails()){
                    return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
                }
                else{

                    $insumos = $data['insumos'];
                    $code =  'EO'.strtoupper( str_random(6) );

                    $entrada = Entrada::create([
                                'codigo'   => $code,
                                'orden'    => $data['orden'],
                                'provedor' => $data['provedor'],
                                'usuario'  => Auth::user()->id
                            ])['id'];

                    foreach ($insumos as $insumo) {
                        
                        Insumos_entrada::create([
                            'entrada'   => $entrada,
                            'insumo'    => $insumo['id'],
                            'cantidad'  => $insumo['cantidad']
                        ]);

                        inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad']);
                    }

                    return Response()->json(['status' => 'success', 'menssage' => 
                        'Entrada completada satisfactoriamente', 'codigo' => $code]);
                }
            break;

            case 'donacion':

                $validator = Validator::make($data,[
                    'provedor' =>  'required',
                    'insumos'  =>  'required|insumos'
                ], $this->menssage);

                if($validator->fails()){
                    return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
                }
                else{

                    $insumos = $data['insumos'];
                    $code =  'ED'.strtoupper( str_random(6) );

                    $donacion = Edonacione::create([
                                'codigo'   => $code,
                                'provedor' => $data['provedor'],
                                'usuario'  => Auth::user()->id
                              ])['id'];

                    foreach ($insumos as $insumo) {
                        
                        Insumos_edonacione::create([
                            'donacion'  => $donacion,
                            'insumo'    => $insumo['id'],
                            'cantidad'  => $insumo['cantidad']
                        ]);

                        inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad']);
                    }

                    return Response()->json(['status' => 'success', 'menssage' => 
                        'Entrada completada satisfactoriamente', 'codigo' => $code]);
                }
            break;

            case 'devolucion':

                $validator = Validator::make($data,[
                    'departamento' =>  'required',
                    'insumos'  =>  'required|insumos'
                ], $this->menssage);

                if($validator->fails()){
                    return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);   
                }
                else{

                    $insumos = $data['insumos'];
                    $code =  'EV'.strtoupper( str_random(6) );

                    $devolucion = Edevolucione::create([
                                'codigo'   => $code,
                                'departamento' => $data['departamento'],
                                'usuario'  => Auth::user()->id
                              ])['id'];

                    foreach ($insumos as $insumo) {
                        
                        Insumos_edevolucione::create([
                            'devolucion'  => $devolucion,
                            'insumo'      => $insumo['id'],
                            'cantidad'    => $insumo['cantidad']
                        ]);

                        inventarioController::almacenaInsumo($insumo['id'], $insumo['cantidad']);
                    }

                    return Response()->json(['status' => 'success', 'menssage' => 
                        'Entrada completada satisfactoriamente', 'codigo' => $code]);
                }
            break;
        }
    }
}
