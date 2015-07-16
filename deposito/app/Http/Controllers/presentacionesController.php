<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Presentacione;

class presentacionesController extends Controller
{
    public function index()
    {
        return view('presentaciones/indexPresentaciones');
    }

    public function viewRegistro(){

        return view('presentaciones/registrarPresentacion');
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
           
            Presentacione::create([
                'nombre'        => $data['nombre']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Presentacion registrada']);
        }
    }

    public function allPresentaciones(){

        return Presentacione::get();
    }

}
