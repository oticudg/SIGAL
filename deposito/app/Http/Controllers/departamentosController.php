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

    public function viewEliminar(){

        return view('departamentos/eliminarDepartamento');
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

            $selloNombre = date("d-m-y-h-i-s").'Sello.'.$sello->getClientOriginalExtension();
            $firmaNombre = date("d-m-y-h-i-s").'Firma.'.$firma->getClientOriginalExtension();

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

    public function allDepartamentos(){

        return Departamento::get();
    }

    public function elimDepartamento(Request $request,$id){

         $departamento = Departamento::where('id',$id)->first();

        if(!$departamento){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta departamento no exist']);            
        }
        else{
            
            Departamento::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Departamento Eliminado']);
            
        }
    }


}