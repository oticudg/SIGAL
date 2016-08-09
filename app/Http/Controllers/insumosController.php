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

        return Insumo::orderBy('id', 'desc')->get();
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

    public function getInsumosConsulta(Request $request){

        $consulta = $request->input('insumo');

        if($consulta != ""){

            return Insumo::where(function($query) use ($consulta){
                $query->where('descripcion', 'like', '%'.$consulta.'%')
                      ->orwhere('codigo', 'like', '%'.$consulta.'%');

            })->orderBy('id', 'desc')->take(50)->get();
        }

        return "[]"; 
    }

    public function editInsumo(Request $request,$id){

        $insumo = Insumo::where('id',$id)->first();

        if(!$insumo){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este insumo no existe']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[
                'codigo'      =>  'required',    
                'descripcion' =>  'required'
            ], $this->menssage);

            if($validator->fails()){
                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                //Codigo original del insumo a editar
                $codigo = Insumo::where('id',$id)->value('codigo'); 
                //Descripcion original del insumo a editar
                $desp   = Insumo::where('id',$id)->value('descripcion');

                /**
                 *Si no se han hecho modificaciones en la descripcion y en el codigo
                 *para este insumo, se regresa el mensaje de error
                 */
                if($codigo == $data['codigo'] && $desp == $data['descripcion']){
                    return Response()->json(['status' => 'danger', 'menssage' => 'No se han hecho modificaciónes']);
                }
                
                /**
                 *Si se modifico la descripcion y existe un insumo con la 
                 *misma descripcion se regresa el mensaje de error
                 */
                if($desp != $data['descripcion'] && 
                    Insumo::where('descripcion',$data['descripcion'])->first()){
                    
                    return Response()->json(['status' => 'danger', 'menssage' => 'Esta descripción ya se encuantra en uso']);
                }

                /**
                 *Si se modifico el codigo y existe un insumo con el  
                 *misma codigo se regresa el mensaje de error
                 */
                if($codigo != $data['codigo'] && 
                    Insumo::where('codigo',$data['codigo'])->first()){
                    
                    return Response()->json(['status' => 'danger', 'menssage' => 'Este codigo ya ha sido registrado']);
                }
                
                /**
                 *Si la descripcion se ha modificado y es diferente que la descripcion
                 *original de este insumo se guarda el cambio
                 */
                if($data['descripcion'] != $desp ){
                    insumo::where('id',$id)->update([
                        'descripcion' => $data['descripcion']
                    ]);
                }

                /**
                 *Si el codigo se ha modificado y es diferente que el codigo
                 *original de este insumo se guarda el cambio
                 */
                if($codigo != $data['codigo'] ){
                    insumo::where('id',$id)->update([
                        'codigo' => $data['codigo']
                    ]); 
                }

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
