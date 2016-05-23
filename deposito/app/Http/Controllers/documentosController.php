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

  public function editar(Request $request, $id){

    $documento = Documento::where('id',$id)->first();

    if(!$documento){
        return Response()->json(['status' => 'danger', 'menssage' => 'Este departamento no existe']);
    }
    else{

      $data = $request->all();

      $validator = Validator::make($data,[
          'abreviatura' => 'min:2|max:2|unique:documentos',
          'nombre'      => 'unique:documentos',
      ]);

      if($validator->fails()){
          return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
      }
      else{

        $modificacion = [];

        if(isset($data['abreviatura']) && !empty($data['abreviatura'])){
          $modificacion['abreviatura'] = $data['abreviatura'];
        }

        if(isset($data['nombre']) && !empty($data['nombre'])){
          $modificacion['nombre'] = $data['nombre'];
        }

        if(isset($data['tipo']) && !empty($data['tipo'])){
          $modificacion['tipo'] = $data['tipo'];
        }

        if(isset($data['uso']) && !empty($data['uso'])){
          $modificacion['uso'] = $data['uso'];
        }

        if(!empty($modificacion)){

          Documento::where('id',$id)->update($modificacion);
          return Response()->json(['status' => 'success', 'menssage' => 'Documento modificado']);
        }
        else{
          return Response()->json(['status' => 'danger',
            'menssage' => 'No se han hecho modificaciones']);
        }
      }
   }
 }

 public function eliminar($id){

    $documento = Documento::where('id',$id)->first();

    if(!$documento){
      return Response()->json(['status' => 'danger', 'menssage' => 'Esta documento no exist']);
    }
    else{
      Documento::where('id',$id)->delete();
      return Response()->json(['status' => 'success', 'menssage' => 'Documento eliminado']);
    }
 }

}
