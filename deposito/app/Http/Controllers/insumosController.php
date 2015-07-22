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
            'descipcion'		=>	'required',
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
            	'id_secction'		=> $data['seccion'],
            	'un_med'			=> $data['medida'],
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

}
