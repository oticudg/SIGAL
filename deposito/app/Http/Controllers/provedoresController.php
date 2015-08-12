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

    private $menssage = [

        'telefono.numeric' => 'Por favor ingrese un telefono valido'

    ];

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
            'nombre'        =>  'required|alpha_spaces',
            'telefono'      =>  'required|numeric',
            'contacto'      =>  'required|alpha_spaces',
            'email'         =>  'required|email',
            'direccion'     =>  'required'

        ], $this->menssage);


        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
           
            Provedore::create([

                'rif'           => $data['rif'],
                'nombre'        => $data['nombre'],
                'telefono'      => $data['telefono'],
                'direccion'     => $data['direccion'],
                'contacto'      => $data['contacto'],
                'email'         => $data['email']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Provedor registrado']);
        }
    }

    public function allProvedores(){

        return Provedore::get();
    }

    public function getProvedor($id){

        $provedor = Provedore::where('id',$id)->first();

        if(!$provedor){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este provedor no exist']);            
        }
        else{

            return $provedor; 
        }

    }

    public function editProvedor(Request $request,$id){

        $provedor = Provedore::where('id',$id)->first();

        if(!$provedor){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este provedor no exist']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[

                'nombre'        =>  'required|alpha_spaces',
                'telefono'      =>  'required|numeric',
                'contacto'      =>  'required|alpha_spaces',
                'email'         =>  'required|email',
                'direccion'     =>  'required'
            
            ], $this->menssage);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
               
                Provedore::where('id',$id)->update([

                    'nombre'        => $data['nombre'],
                    'telefono'      => $data['telefono'],
                    'direccion'     => $data['direccion'],
                    'contacto'      => $data['contacto'],
                    'email'         => $data['email']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);

            }
        }
    }

    public function elimProvedor(Request $request,$id){

         $provedor = Provedore::where('id',$id)->first();

        if(!$provedor){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este provedor no exist']);            
        }
        else{
            
            Provedore::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Provedor Eliminado']);
            
        }
    }
    
}
