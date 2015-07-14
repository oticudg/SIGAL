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

    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[

            'rif'           =>  'required',
            'nombre'        =>  'required',
            'telefono'      =>  'required',
            'direccion'     =>  'required',
            'contacto'      =>  'required',
            'email'         =>  'required'
        ]);


        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
           
            Provedore::create([

                'rif'           => $data['rif'],
                'nombre'        => $data['nombre'],
                'telefono'      => $data['telefono'],
                'direccion'   => $data['direccion'],
                'contacto'      => $data['contacto'],
                'email'         => $data['email']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Provedor registrado']);
        }
    }

    public function allProvedores(){

        return Provedore::get();
    }
    
}
