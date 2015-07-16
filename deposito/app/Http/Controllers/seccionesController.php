<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class seccionesController extends Controller
{

    public function index()
    {
        return view('secciones/indexSecciones');
    }

    public function viewRegistro(){

        //return view('secciones/registrarPresentacion');
    }

    public function viewEditar(){

        //return view('secciones/editarPresentacion');
    }

     public function viewEliminar(){

        //return view('secciones/eliminarPresentacion');
    }

}
