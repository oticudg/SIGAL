<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Deposito;

class depositosController extends Controller
{   
    private $menssage = [
        'nombre.unique' => 'Ya fue registrado un deposito con este nombre',
    ];

    public function index()
    {
        return view('depositos/indexDepositos');
    }

    public function viewRegistrar(){

        return view('depositos/registrarDeposito');
    }

    public function registrar(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'nombre'  =>  'required|min:3|max:30|unique:depositos',
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
            
            $code =  strtoupper( str_random(10) );

            Deposito::create([
                'nombre' => $data['nombre'],
                'codigo' => $code  
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Deposito registrado']);  
        }
    }

}
