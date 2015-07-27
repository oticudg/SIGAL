<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Unidad_medida;

class unidadMedidasController extends Controller
{
    public function index()
    {
        return view('unidadMedidas/indexUnidadMedidas');
    }

    public function viewRegistrar(){

        return view('unidadMedidas/registrarUnidadMedidas');
    }

    public function viewEditar(){

        return view('unidadMedidas/editarUnidadMedidas');
    }

    public function viewEliminar(){

        return view('unidadMedidas/eliminarUnidadMedidas');
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
           
            Unidad_medida::create([
                'nombre'        => $data['nombre']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Presentacion registrada']);
        }
    }

    public function allUnidades(){

        return Unidad_medida::get();
    }

    public function getUnidad($id){

        $medida = Unidad_medida::where('id',$id)->first();

        if(!$medida){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta unidad de medida no exist']);            
        }
        else{

            return $medida; 
        }

    }

    public function editUnidad(Request $request,$id){

        $medida = Unidad_medida::where('id',$id)->first();

        if(!$medida){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta unidad de medida no exist']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[

                'nombre'  =>  'required'
            ]);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
               
                Unidad_medida::where('id',$id)->update([

                    'nombre'  => $data['nombre']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimUnidad(Request $request,$id){

         $medida = Unidad_medida::where('id',$id)->first();

        if(!$medida){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta medida no exist']);            
        }
        else{
            
            Unidad_medida::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Unidad de medida Eliminada']);
            
        }
    }




}
