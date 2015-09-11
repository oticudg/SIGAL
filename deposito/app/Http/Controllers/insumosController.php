<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Insumo;

class insumosController extends Controller
{   
    private $menssage = [
        'codigo.unique' => 'Este codigo ya ha sido registrado',
        'descripcion.unique' => 'Esta descripción ya se encuantra en uso' 
    ];

    public function index(){
        return view('insumos/indexInsumos');
    }

    public function viewRegistrar(){
    	return view('insumos/registrarInsumo');
    }

    public function viewEditar(){
        return view('insumos/editarInsumo');
    }

    public function viewEliminar(){
        return view('insumos/eliminarInsumo');
    }


    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[

            'codigo'  			=>  'required|unique:insumos',
            'descripcion'		=>	'required|unique:insumos'

        ], $this->menssage);

        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
	    else{
          
            Insumo::create([
            	'codigo' 			=> $data['codigo'],
                'descripcion'       => $data['descripcion']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Insumo registrado']);
        }
    }

    public function allInsumos(){

        return Insumo::get();
    }
    
    public function getInsumo($id){

        $insumo = Insumo::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este insumo no existe']);            
        }
        else{

            return $insumo; 
        }
    }
    
    public function codeInsumo($code){

        $insumo = Insumo::where('codigo',$code)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este insumo no existe']);            
        }
        else{

            return $insumo; 
        }
    }

    public function editInsumo(Request $request,$id){

        $insumo = Insumo::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este insumo no existe']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[    
                'descripcion' =>  'required|unique:insumos'
            ], $this->menssage);

            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                insumo::where('id',$id)->update([
                    'descripcion'       => $data['descripcion']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimInsumo(Request $request,$id){

         $insumo = Insumo::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta insumo no existe']);            
        }
        else{
            
            Insumo::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Insumo Eliminado']);
            
        }
    }

}
