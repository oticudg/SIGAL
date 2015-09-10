<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\entrada;
use App\insumos_entrada;

class entradasController extends Controller
{

    function index(){
        return view('entradas/indexEntradas');
    }

    function viewRegistrar(){  
        return view('entradas/registrarEntrada');
    }

    function detalles(){
        return view('entradas/detallesEntrada');
    }

    function allInsumos(){

        return DB::table('insumos_entradas')
            ->join('entradas', 'entradas.id', '=', 'insumos_entradas.entrada')
            ->join('insumos', 'insumos.id' , '=', 'insumos_entradas.insumo')
            ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo as entrada',
                'insumos.codigo','insumos.descripcion','insumos_entradas.cantidad')
            ->get();
    }

    function allEntradas(){

        return DB::table('entradas')
            ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
            ->select(DB::raw('DATE_FORMAT(entradas.created_at, "%d/%m/%Y") as fecha'),'entradas.codigo',
                'provedores.nombre as provedor', 'entradas.id')
            ->get();
    }

    function getEntrada($id){

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
                    'provedores.nombre as provedor', 'users.email as usuario')
                ->first();

           $insumos = DB::table('insumos_entradas')->where('insumos_entradas.entrada', $id)
                ->join('insumos', 'insumos_entradas.insumo', '=', 'insumos.id')
                ->select('insumos.codigo', 'insumos.descripcion', 'insumos_entradas.cantidad')
                ->get();

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos]);
        }
    }
}
