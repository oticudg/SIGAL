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

    public function index(){
        return view('depositos/indexDepositos');
    }

    public function viewRegistrar(){
        return view('depositos/registrarDeposito');
    }

    public function viewEditar(){
        return view('depositos/editarDeposito');
    }

    public function viewEliminar(){
        return view('depositos/eliminarDeposito');
    }

    public function registrar(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'nombre'  =>  'required|min:3|max:60|unique:depositos',
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

    public function editarDeposito(Request $request,$id){

        $deposito = Deposito::where('id',$id)->first();

        if(!$deposito){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este deposito no existe']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[    
                'nombre' =>  'required|unique:depositos'
            ], $this->menssage);

            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                Deposito::where('id',$id)->update([
                    'nombre' => $data['nombre']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimDeposito(Request $request,$id){

         $deposito = Deposito::where('id',$id)->first();

        if(!$deposito){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta deposito no exist']);            
        }
        else{
            
            Deposito::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Deposito Eliminado']);
        }
    }


    public function getDeposito($id){

        $deposito = Deposito::where('id',$id)->first(['id', 'codigo', 'nombre']);

        if(!$deposito){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este Deposito no existe']);            
        }
        else{

            return $deposito; 
        }
    }

    public function allDepositos(){
        return Deposito::orderBy('id', 'desc')->get(['id','codigo', 'nombre']);
    }
}
