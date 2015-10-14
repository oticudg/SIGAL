<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Entradas_modificada;
use App\Insumos_Emodificado;

class modificacionesController extends Controller
{
    public function index(){
        return view('modificaciones/indexModificaciones');
    }

    public function detallesEntrada(){

        return view('modificaciones/detallesEntradaModificada');
    }

    public function allEntradas(){

        return DB::table('entradas_modificadas')
                ->join('entradas', 'entradas_modificadas.entrada', '=', 'entradas.id')
                ->select(DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                    'entradas.codigo as codigo', 'entradas_modificadas.id as id')
                ->orderBy('entradas_modificadas.id', 'desc')->get();
    }

    public function getEntrada($id){

        $entrada = Entradas_modificada::where('id',$id)->first();

        if(!$entrada){
            return Response()->json(['status' => 'danger', 'menssage' => 'Esta Entrada no existe']);            
        }
        else{ 

            $modificacion = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                            ->join('entradas', 'entradas_modificadas.entrada', '=', 'entradas.id')
                            ->join('users', 'entradas_modificadas.usuario' , '=', 'users.id' )
                            ->select(DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%d/%m/%Y") as fecha'),
                                    DB::raw('DATE_FORMAT(entradas_modificadas.created_at, "%H:%i:%s") as hora'),  
                                    'users.email as usuario', 'entradas.codigo as codigo')
                            ->first();


           if(  Entradas_modificada::where('id',$id)->value('Mprovedor') == NULL && 
                Entradas_modificada::where('id',$id)->value('Morden') == NULL){
                
                $entrada = NULL;  
            }
            elseif(Entradas_modificada::where('id',$id)->value('Mprovedor') == NULL){
                
               $entrada = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                          ->select('entradas_modificadas.Oorden as orden', 'entradas_modificadas.Morden as Morden')   
                          ->first();
            }
            elseif(Entradas_modificada::where('id',$id)->value('Morden') == NULL){

               $entrada = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                          ->join('provedores', 'entradas_modificadas.Oprovedor', '=', 'provedores.id')
                          ->join('provedores as Mprovedores', 'entradas_modificadas.Mprovedor', '=', 'Mprovedores.id')
                          ->select('provedores.nombre as provedor', 'Mprovedores.nombre as Mprovedor')   
                          ->first();   
            }
            else{

                $entrada = DB::table('entradas_modificadas')->where('entradas_modificadas.id',$id)
                          ->join('provedores', 'entradas_modificadas.Oprovedor', '=', 'provedores.id')
                          ->join('provedores as Mprovedores', 'entradas_modificadas.Mprovedor', '=', 'Mprovedores.id')
                          ->select('provedores.nombre as provedor', 'Mprovedores.nombre as Mprovedor', 
                            'entradas_modificadas.Oorden as orden', 'entradas_modificadas.Morden as Morden')   
                          ->first();
            }

            $insumos  = false;

            return Response()->json(['status' => 'success', 'entrada' => $entrada , 'insumos' => $insumos, 
                    'modificacion' => $modificacion]);
        }
    }

}
