<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Departamento;

class departamentosController extends Controller
{
    public function index(){

        return view('departamentos/indexDepartamentos');
    }

    public function viewRegistrar(){

        return view('departamentos/registrarDepartamento');
    }

    public function registrar(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[

            'nombre'        =>  'required',
            'division'      =>  'required',
            'sello'         =>  'required',
            'firma'         =>  'required'
        ]);

        if($validator->fails()){

            return redirect('registrarDepartamento')->withErrors($validator);
        }
        else{
            
            $sello = $data['sello'];
            $firma = $data['firma'];

            $selloNombre = $data['nombre'].'Sello.'.$sello->getClientOriginalExtension();
            $firmaNombre = $data['nombre'].'Firma.'.$firma->getClientOriginalExtension();

            $sello->move(public_path().'/files/sellos',$selloNombre);
            $firma->move(public_path().'/files/firmas',$firmaNombre);

            Departamento::create([

                'nombre'    => $data['nombre'],
                'division'  => $data['division'],
                'sello'     => $selloNombre,
                'firma'     => $firmaNombre

            ]);


            return view('departamentos/registrarDepartamento',['success' => 'Departamento registrado']);  
        }
    }

}