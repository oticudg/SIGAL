<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Deposito;
use App\Departamento;
use App\Provedore;
use Auth;

class depositosController extends Controller
{
    private $menssage = [
        'nombre.unique' => 'Ya fue registrado un Almacén con este nombre',
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

            return Response()->json(['status' => 'success', 'menssage' => 'Almacén registrado']);
        }
    }

    public function editarDeposito(Request $request,$id){

        $deposito = Deposito::where('id',$id)->first();

        if(!$deposito){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este Almacén no existe']);
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

            return Response()->json(['status' => 'danger', 'menssage' => 'Esta almacén no exist']);
        }
        else{

            Deposito::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Almacén Eliminado']);
        }
    }


    public function getDeposito($id){

        $deposito = Deposito::where('id',$id)->first(['id', 'codigo', 'nombre']);

        if(!$deposito){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este Almacén no existe']);
        }
        else{

            return $deposito;
        }
    }

    public function allDepositos(){
        return Deposito::orderBy('id', 'desc')->get(['id','codigo', 'nombre']);
    }

    public function allTerceros($tipo = null){
      switch($tipo){

        case 'proveedor':
          return Provedore::get(['id', 'nombre', DB::raw('("proveedor") as type')]);
        break;

        case 'servicio':
          return Departamento::get(['id', 'nombre', DB::raw('("servicio") as type')]);
        break;

        case 'deposito':
          $deposito = Auth::user()->deposito;

          return Deposito::where('id', '!=',$deposito)
                 ->get(['id', 'nombre', DB::raw('("deposito") as type')]);

        default:

          $deposito = Auth::user()->deposito;

          $provedores = DB::table('provedores')
                        ->where('deleted_at', null)
                        ->select('id', 'nombre', DB::raw('("proveedor") as type'));

          $servicios  = DB::table('departamentos')
                        ->where('deleted_at', null)
                        ->select('id', 'nombre', DB::raw('("servicio") as type'));

          $depositos  = DB::table('depositos')
                        ->where('id', '!=',$deposito)
                        ->where('deleted_at', null)
                        ->select('id', 'nombre', DB::raw('("deposito") as type'));

          $interno    = DB::table('depositos')
                          ->where('id', $deposito)
                          ->select('id', 'nombre', DB::raw('("interno") as type'));

          return $provedores
                 ->unionAll($servicios)
                 ->unionAll($depositos)
                 ->unionAll($interno)
                 ->orderBy('id', 'desc')
                 ->get();
        break;
      }
    }
}
