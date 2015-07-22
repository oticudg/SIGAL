<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Seccione;

class seccionesController extends Controller
{

    public function index()
    {
        return view('secciones/indexSecciones');
    }

    public function viewRegistro(){

        return view('secciones/registrarSeccion');
    }

    public function viewEditar(){

        return view('secciones/editarSeccion');
    }

     public function viewEliminar(){

        return view('secciones/eliminarSeccion');
    }

    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[
            'nombre'  =>  'required',
        ]);

        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
           
            Seccione::create([
                'nombre' => $data['nombre']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Seccion registrada']);
        }
    }

    public function allSecciones(){

        return Seccione::get();
    }

    public function getSeccion($id){

        $seccion = Seccione::where('id',$id)->first();

        if(!$seccion){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta seccion no exist']);            
        }
        else{

            return $seccion; 
        }

    }

    public function editSeccion(Request $request,$id){

        $seccion = Seccione::where('id',$id)->first();

        if(!$seccion){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta seccion no exist']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[

                'nombre'        =>  'required'
            ]);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
               
                Seccione::where('id',$id)->update([

                    'nombre'        => $data['nombre']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimSeccion(Request $request,$id){

         $seccion = Seccione::where('id',$id)->first();

        if(!$seccion){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta seccion no exist']);            
        }
        else{
            
            seccione::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Seccion Eliminada']);
            
        }
    }


}
