<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\entrada;

class entradasController extends Controller
{

    function index(){
        return view('entradas/indexEntradas');
    }

    function allEntradas(){

        return DB::table('entradas')
            ->join('provedores', 'entradas.provedor', '=', 'provedores.id')
            ->join('departamentos', 'entradas.departamento', '=', 'departamentos.id')
            ->select('entradas.created_at','entradas.codigo','departamentos.nombre','provedores.nombre')->get();
    }
    
}
