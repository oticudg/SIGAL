<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entrada;
use App\Deposito;

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

    public function allInventario(){
        
        $deposito   = Auth::user()->deposito;
        $usuario    = Auth::user()->email;
        $depositoN  = Deposito::where('id', $deposito)->value('nombre');
        $fecha      = date("Y-m-d");
        $hora       = date("H:i:s");


        $insumos = DB::table('insumos')
            ->where('deposito', $deposito)
            ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
            ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                'inventarios.existencia')
            ->orderBy('inventarios.id', 'desc')
            ->get();

        $view =  \View::make('reportes.pdfs.allInventario', 
                     compact('insumos', 'usuario', 'depositoN', 'fecha', 'hora'))->render();

        $pdf  =  \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Inventario total');    
    }

    public function getInventario(Request $request){
        
        $data       = $request->all();
        $deposito   = Auth::user()->deposito;
        $usuario    = Auth::user()->email;
        $depositoN  = Deposito::where('id', $deposito)->value('nombre');
        $fecha      = date("Y-m-d");
        $hora       = date("H:i:s");

        $insumos = DB::table('inventarios')
            ->where('deposito', $deposito)
            ->whereIn('inventarios.insumo', $data['insumos'])
            ->join('insumos', 'insumos.id', '=', 'inventarios.insumo')
            ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                'inventarios.existencia')
            ->orderBy('inventarios.id', 'desc')
            ->get();


        $view =  \View::make('reportes.pdfs.parcialInventario', 
                     compact('insumos', 'usuario', 'depositoN', 'fecha', 'hora'))->render();

        $pdf  =  \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        return $pdf->stream('Inventario total');    
    }

}
