<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Insumo;

class insumosController extends Controller
{   
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

            'codigo'  			=>  'required',
            'principio_activo'	=>  'required',
            'marca'				=>  'required',
            'presentacion'		=>  'required',
            'seccion'			=>  'required',
            'medida'			=>  'required',
            'cantidadM'			=>	'required',
            'cantidadX'			=>  'required',
            'ubicacion'			=>  'required',
            'deposito'			=>  'required',
            'descripcion'		=>	'required',
        	'file'				=>  'required'
        ]);

        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
	    else{

	    	$file = $data['file'];

            $fileNombre = date("d-m-y-h-i-s").'Insumo.'.$file->getClientOriginalExtension();
            $file->move(public_path().'/files/insumos',$fileNombre);
			          
            Insumo::create([

            	'codigo' 			=> $data['codigo'],
            	'id_presentacion'	=> $data['presentacion'],
            	'id_seccion'		=> $data['seccion'],
                'descripcion'       => $data['descripcion'],
            	'id_medida'			=> $data['medida'],
            	'cant_min'          => $data['cantidadM'],
            	'cant_max'			=> $data['cantidadX'],
            	'marca'				=> $data['marca'],
            	'ubicacion'			=> $data['ubicacion'],
            	'principio_act'		=> $data['principio_activo'],
            	'deposito'			=> $data['deposito'],
            	'imagen'			=> $fileNombre

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

            return Response()->json(['status' => 'danger', 'menssage' => 'Este insumo no exist']);            
        }
        else{

            return $insumo; 
        }

    }

    public function editInsumo(Request $request,$id){

        $insumo = Insumo::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este insumo no exist']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[

                'codigo'            =>  'required',
                'principio_activo'  =>  'required',
                'marca'             =>  'required',
                'presentacion'      =>  'required',
                'seccion'           =>  'required',
                'medida'            =>  'required',
                'cantidadM'         =>  'required',
                'cantidadX'         =>  'required',
                'ubicacion'         =>  'required',
                'deposito'          =>  'required',
                'descripcion'       =>  'required'
            ]);

            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                if( !empty($data['file']) ){

                    $file = $data['file'];

                    $fileNombre = date("d-m-y-h-i-s").'Insumo.'.$file->getClientOriginalExtension();
                    $file->move(public_path().'/files/insumos',$fileNombre);

                    insumo::where('id',$id)->update(['imagen' => $fileNombre]);

                }

                insumo::where('id',$id)->update([

                    'codigo'            => $data['codigo'],
                    'id_presentacion'   => $data['presentacion'],
                    'id_seccion'        => $data['seccion'],
                    'descripcion'       => $data['descripcion'],
                    'id_medida'         => $data['medida'],
                    'cant_min'          => $data['cantidadM'],
                    'cant_max'          => $data['cantidadX'],
                    'marca'             => $data['marca'],
                    'ubicacion'         => $data['ubicacion'],
                    'principio_act'     => $data['principio_activo'],
                    'deposito'          => $data['deposito'],
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimInsumo(Request $request,$id){

         $insumo = Insumo::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta insumo no exist']);            
        }
        else{
            
            Insumo::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Insumo Eliminado']);
            
        }
    }

}
