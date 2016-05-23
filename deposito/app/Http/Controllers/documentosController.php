<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Documento;


class documentosController extends Controller
{
  public function index(){
    return view('documentos/index');
  }

  public function registrar(Request $request){
    $data = $request->all();

    $validator = Validator::make($data,[
        'abreviatura' => 'required|min:2|max:2|unique:documentos',
        'nombre'      => 'required|unique:documentos',
        'tipo'        => 'required',
        'naturaleza'  => 'required',
        'uso'         => 'required'
    ]);

    if($validator->fails()){
        return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
    }
    else{

        Documento::create([
          'abreviatura' => $data['abreviatura'],
          'nombre'      => $data['nombre'],
          'tipo'        => $data['tipo'],
          'naturaleza'  => $data['naturaleza'],
          'uso'         => $data['uso']
        ]);

        return Response()->json(['status' => 'success', 'menssage' => 'Documento registrado']);
    }
  }
}
