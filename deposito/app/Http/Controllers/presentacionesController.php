<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Presentacione;

class presentacionesController extends Controller
{
    private $menssage = [

        "nombre.unique" => "Esta presentacion ya ha sido registrada"
    ];

    public function index()
    {
        return view('presentaciones/indexPresentaciones');
    }

    public function viewRegistro(){

        return view('presentaciones/registrarPresentacion');
    }

    public function viewEditar(){


        return view('presentaciones/editarPresentacion');
    }

    public function viewEliminar(){


        return view('presentaciones/eliminarPresentacion');
    }


   
    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[

            'nombre'  =>  'required|unique:presentaciones',

        ], $this->menssage);


        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
           
            Presentacione::create([
                'nombre'  => $data['nombre']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Presentacion registrada']);
        }
    }

    public function allPresentaciones(){

        return Presentacione::get();
    }

    public function getPresentacion($id){

        $presentacion = Presentacione::where('id',$id)->first();

        if(!$presentacion){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta presentacion no exist']);            
        }
        else{

            return $presentacion; 
        }

    }

    public function editPresentacion(Request $request,$id){

        $presentacion = Presentacione::where('id',$id)->first();

        if(!$presentacion){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta presentacion no exist']);            
        }
        else{
            
            $data = $request->all();

       
            $validator = Validator::make($data,[

                'nombre'  =>  'required|unique:presentaciones',

            ], $this->menssage);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
               
                Presentacione::where('id',$id)->update([

                    'nombre'  => $data['nombre']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimPresentacion(Request $request,$id){

         $presentacion = Presentacione::where('id',$id)->first();

        if(!$presentacion){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta presentacion no exist']);            
        }
        else{
            
            Presentacione::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Presentacion Eliminada']);
            
        }
    }

}
