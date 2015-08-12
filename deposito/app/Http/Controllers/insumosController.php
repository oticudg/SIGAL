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

        "cantidadM.required" => "Indique una cantidad minima",
        "cantidadM.numeric"  => "Indique una cantidad minima valida",
        "cantidadX.required" => "Indique una cantidad maxima",
        "cantidadX.numeric"  => "Indique una cantidad maxima valida",
        "file.required"      => "Seleccione una imagen de presentacion",
        "file.image"         => "Seleccione una imagen valida"   
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
            'principio_activo'	=>  'required|alpha_spaces',
            'marca'				=>  'required|alpha_spaces',
            'presentacion'		=>  'required|exists:presentaciones,id',
            'seccion'			=>  'required|exists:secciones,id',
            'medida'			=>  'required|exists:unidad_medidas,id',
            'cantidadM'			=>	'required|numeric',
            'cantidadX'			=>  'required|numeric',
            'ubicacion'			=>  'required|alpha_spaces',
            'deposito'			=>  'required|in:farmacia,alimentacion',
            'descripcion'		=>	'required',
        	'file'				=>  'required|image'

        ], $this->menssage);

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

                'principio_activo'  =>  'required|alpha_spaces',
                'marca'             =>  'required|alpha_spaces',
                'presentacion'      =>  'required|exists:presentaciones,id',
                'seccion'           =>  'required|exists:secciones,id',
                'medida'            =>  'required|exists:unidad_medidas,id',
                'cantidadM'         =>  'required|numeric',
                'cantidadX'         =>  'required|numeric',
                'ubicacion'         =>  'required|alpha_spaces',
                'deposito'          =>  'required|in:farmacia,alimentacion',
                'descripcion'       =>  'required',
                'file'              =>  'image'
            ], $this->menssage);

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
