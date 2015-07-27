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

}