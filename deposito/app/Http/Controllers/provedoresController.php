<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Provedore;

class provedoresController extends Controller
{   
    public function index(){

        return view('provedores/indexProvedores');
    }

    public function viewRegistro(){

        return view('provedores/registraProvedor');
    }

    public function viewEditar(){


        return view('provedores/editarProvedor');
    }


    public function viewEliminar(){

        return view('provedores/eliminarProvedor');

    }


    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[

            'rif'           =>  'required|rif|unique:provedores',
            'nombre'        =>  'required|',

        ]);


        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
           
            Provedore::create([
                'rif'           => $data['rif'],
                'nombre'        => $data['nombre']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Proveedor registrado']);
        }
    }

    public function allProvedores(){

        return Provedore::orderBy('id', 'desc')->get();
    }

    public function getProvedor($id){

        $provedor = Provedore::where('id',$id)->first();

        if(!$provedor){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este proveedor no exist']);            
        }
        else{

            return $provedor; 
        }

    }

    public function editProvedor(Request $request,$id){

        $provedor = Provedore::where('id',$id)->first();

        if(!$provedor){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este proveedor no exist']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[
                'nombre'  =>  'required',
            ]);


            if($validator->fails()){
                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
               
                Provedore::where('id',$id)->update([
                    'nombre'  => $data['nombre']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);

            }
        }
    }

    public function elimProvedor(Request $request,$id){

         $provedor = Provedore::where('id',$id)->first();

        if(!$provedor){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este proveedor no exist']);            
        }
        else{
            
            Provedore::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Proveedor Eliminado']);
            
        }
    }
    
}
