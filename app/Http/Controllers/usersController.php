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
                  ->first(['cedula', 'nombre', 'apellido', 'email', 'deposito', 'rol']);

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
                    'deposito'		   => 'deposito',
                    'rol'            => 'required|rol'
            ], $this->menssage);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{

                $update = [
                  'nombre'        => $data['nombre'],
                  'apellido'      => $data['apellido'],
                  'cedula'        => $data['cedula']
                ];

                if( isset($data['password']) ){
                    $update['password'] = bcrypt($data['password']);
                }

                if( isset($data['deposito']) ){
                    $update['deposito'] = $data['deposito'];
                }

                if( isset($data['rol']) ){
                    $update['rol'] = $data['rol'];
                }

                User::where('id',$id)->update($update);

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
