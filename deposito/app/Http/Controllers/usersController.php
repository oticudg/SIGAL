<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Privilegio;
use App\Deposito;
use Hash;

class usersController extends Controller
{

    private $menssage = [
        'password.required'     => 'El campo contraseña es obligatorio',
        'passwordOri.required'  => 'El campo contraseña actual es obligatorio',
        'password.min'          => 'La contraseña debe contener al menos 8 caracteres',
        'password.confirmed'    => 'La contraseña no coincide',
        'deposito.required'     => 'Seleccione un Almacén'
    ];

    public function index()
    {
        return view('users/indexUsers');
    }

    public function viewRegistrar(){

        return view('users/registrarUser');
    }

    public function viewEditar(){
        return view('users/editarUser');
    }

    public function viewEliminar(){
        return view('users/eliminarUser');
    }

    public function viewDeposito(){
        return view('users/cambiarDeposito');
    }

    public function viewPassword(){
        return view('users/cambiarPassword');
    }

    public function registrar(Request $request){

        $data = $request->all();

        $validator = Validator::make($data,[
            'cedula'    => 'required|cedula',
            'nombre'    => 'required|alpha|min:3|max:15',
            'apellido'  => 'required|alpha|min:3|max:20',
            'email'     => 'required|email|max:50|unique:users',
            'password'  => 'required|min:8|confirmed',
            'deposito'  => 'required|deposito',
            'rol'       => 'required|rol'
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            User::create([
                'nombre'   => $data['nombre'],
                'apellido' => $data['apellido'],
                'cedula'   => $data['cedula'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
                'deposito' => $data['deposito'],
                'rol'      => $data['rol']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Usuario registrado']);
        }
    }

    public function allUsuarios(){

        return DB::table('users')
               ->join('depositos', 'users.deposito','=','depositos.id')
               ->select(DB::raw('CONCAT(users.nombre, " " , users.apellido) as nombre'), 'users.cedula',
                'users.email', 'users.id', 'depositos.nombre as deposito')
               ->where('users.id', '!=', 1)
               ->where('users.deleted_at', '=', NULL)
               ->orderBy('users.id', 'desc')->get();
    }

    public function getUsuario($id){

        $usuario = DB::table('users')
                  ->where('users.id',$id)
                  ->join('privilegios', 'users.id', '=', 'privilegios.usuario')
                  ->select('users.nombre', 'users.apellido', 'users.id', 'users.email', 'users.cedula',
                    'usuarios as pUsuario', 'usuarioN as pUsuarioR', 'usuarioM as pUsuarioM', 'usuarioD as pUsuarioE',
                    'provedores as pProvedor', 'provedoreN as pProvedorR', 'provedoreM as pProvedorM', 'provedoreD as pProvedorE',
                    'departamentos as pDepartamento', 'departamentoN as pDepartamentoR', 'departamentoD as pDepartamentoE',
                    'insumos as pInsumo', 'insumoN as pInsumoR', 'insumoM as pInsumoM', 'insumoD as pInsumoE',
                    'inventarios as pInventario', 'inventarioH as pInventarioH', 'modificaciones as pModificacion',
                    'entradas as pEntradaV', 'entradaR as pEntradaR', 'salidas as pSalidaV', 'salidaR as pSalidaR', 'estadisticas as pEstadistica',
                    'departamentoM as pDepartamentoM', 'depositos as pDeposito' , 'depositoN as pDepositoR',
                    'depositoM as pDepositoM','depositoD as pDepositoE')
                  ->first();

        if(!$usuario){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);
        }
        else{

            return ['usuario' => $usuario];
        }
    }

    public function getUsuariosDeposito(){
        $deposito = Auth::user()->deposito;

        return User::where('deposito', $deposito)->get(['id', 'nombre', 'apellido']);
    }

    public function editUsuario(Request $request,$id){

        $usuario = User::where('id',$id)->first();

        if(!$usuario || $usuario['id'] == 1){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);
        }
        else{

            $data = $request->all();

            $validator = Validator::make($data,[
                    'nombre'         => 'required|alpha|min:3|max:15',
                    'apellido'       => 'required|alpha|min:3|max:20',
                    'cedula'         => 'required|cedula',
                    'password'       => 'min:8|confirmed',
                    'deposito'		 => 'deposito',
                    'pUsuario'       => 'required',
                    'pUsuarioR'      => 'required',
                    'pUsuarioM'      => 'required',
                    'pUsuarioE'      => 'required',
                    'pDepartamento'  => 'required',
                    'pDepartamentoR' => 'required',
                    'pDepartamentoM' => 'required',
                    'pDepartamentoE' => 'required',
                    'pInsumo'        => 'required',
                    'pInsumoR'       => 'required',
                    'pInsumoM'       => 'required',
                    'pInsumoE'       => 'required',
                    'pInventario'    => 'required',
                    'pInventarioH'   => 'required',
                    'pModificacion'  => 'required',
                    'pEntradaV'      => 'required',
                    'pEntradaR'      => 'required',
                    'pSalidaV'       => 'required',
                    'pSalidaR'       => 'required',
                    'pEstadistica'   => 'required',
                    'pProvedor'      => 'required',
                    'pProvedorR'     => 'required',
                    'pProvedorM'     => 'required',
                    'pProvedorE'     => 'required',
                    'pDeposito'      => 'required',
                    'pDepositoR'     => 'required',
                    'pDepositoM'     => 'required',
                    'pDepositoE'     => 'required',
                    'pTranference'   => 'required'
            ], $this->menssage);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                if( $data['pUsuario'] == null && $data['pDepartamento'] == null && $data['pInsumo'] == null
                	&& $data['pInventario'] == null && $data['pModificacion'] == null &&
                	$data['pTranference'] == null && $data['pEstadistica'] == null &&
                    $data['pProvedor'] == null && $data['pDeposito'] == null){

                    return Response()->json(['status' => 'danger', 'menssage' => 'Por favor Asigné al menos un privilegio a este usuario']);
                }

                if( isset($data['password']) ){

                    User::where('id',$id)->update([
                        'password' => bcrypt($data['password'])
                    ]);
                }

                if( isset($data['deposito']) ){

                    User::where('id',$id)->update([
                        'deposito' => $data['deposito']
                    ]);
                }

                User::where('id',$id)->update([

                    'nombre'        => $data['nombre'],
                    'apellido'      => $data['apellido'],
                    'cedula'        => $data['cedula']
                ]);

                Privilegio::where('usuario', $id)->update([
                    'usuarios'       => $data['pUsuario'],
                    'usuarioN'       => $data['pUsuarioR'],
                    'usuarioM'       => $data['pUsuarioM'],
                    'usuarioD'       => $data['pUsuarioE'],
                    'provedores'     => $data['pProvedor'],
                    'provedoreN'     => $data['pProvedorR'],
                    'provedoreM'     => $data['pProvedorM'],
                    'provedoreD'     => $data['pProvedorE'],
                    'departamentos'  => $data['pDepartamento'],
                    'departamentoN'  => $data['pDepartamentoR'],
                    'departamentoM'  => $data['pDepartamentoM'],
                    'departamentoD'  => $data['pDepartamentoE'],
                    'insumos'        => $data['pInsumo'],
                    'insumoN'        => $data['pInsumoR'],
                    'insumoM'        => $data['pInsumoM'],
                    'insumoD'        => $data['pInsumoE'],
                    'insumos'        => $data['pInsumo'],
                    'inventarios'    => $data['pInventario'],
                    'inventarioH'    => $data['pInventarioH'],
                    'modificaciones' => $data['pModificacion'],
                    'entradas'       => $data['pEntradaV'],
                    'entradaR'       => $data['pEntradaR'],
                    'salidas'        => $data['pSalidaV'],
                    'salidaR'        => $data['pSalidaR'],
                    'estadisticas'   => $data['pEstadistica'],
                    'depositos'      => $data['pDeposito'],
                    'depositoN'      => $data['pDepositoR'],
                    'depositoM'      => $data['pDepositoM'],
                    'depositoD'      => $data['pDepositoE']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);
            }
        }
    }

    public function elimUsuario(Request $request,$id){

         $usuario = User::where('id',$id)->first();

        if(!$usuario || $usuario['id'] == 1){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);
        }
        else{

            User::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Usuario Eliminado']);

        }
    }

    public function getDeposito(){

        $deposito = Auth::user()->deposito;

        return Deposito::where('id', $deposito)->value('nombre');
    }

    public function editDeposito(Request $request){

        $data = $request->all();
        $id   = Auth::user()->id;


        $validator = Validator::make($data,[
                    'deposito' => 'required',
        ], $this->menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            User::where('id', $id)->update([
                'deposito' => $data['deposito']
            ]);

            return Response()->json(['status' => 'success']);
        }
    }

    public function editPassword(Request $request){

        $data = $request->all();
        $id   = Auth::user()->id;

        $menssage = [
            'password.required'     => 'Indique nueva contraseña',
            'passwordOri.required'  => 'Indique contraseña actual',
            'password.min'          => 'La contraseña debe contener al menos 8 caracteres',
            'password.confirmed'    => 'La confirmacion de la nueva contraseña no coincide'
        ];

        $validator = Validator::make($data,[
            'passwordOri'   => 'required',
            'password'      => 'required|min:8|confirmed'
        ], $menssage);

        if($validator->fails()){
            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            //Obtiene la contraseña actual que utiliza el usuario
            $oriPassword = User::where('id', $id)->value('password');

            /**
             *Si la contraseña actual no coincide, regresa un mensaje de error
             */
            if( !Hash::check($data['passwordOri'], $oriPassword) ){
                return Response()->json(['status' => 'danger', 'menssage' =>
                    'La contraseña actual no coincide']);
            }

            /**
             *Si la contraseña actual es la misma a midificar regresa un mensaje de error
             */
            if( Hash::check($data['password'], $oriPassword) ){
                return Response()->json(['status' => 'danger', 'menssage' =>
                    'La contraseña a modificar no puede ser la actual.']);
            }

            User::where('id', $id)->update([
                'password' => bcrypt($data['password'])
            ]);

            return Response()->json(['status' => 'success']);
        }
    }
}
