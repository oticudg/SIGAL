<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entrada;

class reportesController extends Controller
{   
    public function cargaInventario($id){
        
        $deposito = Auth::user()->deposito;  
        $entrada = Entrada::where('id',$id)
                            ->where('deposito', $deposito)
                            ->where('type', 'cinventario')
                            ->first();
        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta carga de inventario no existe']);            
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
}
