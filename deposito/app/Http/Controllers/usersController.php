<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class usersController extends Controller
{   
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

    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[
            
            'nombre'   => 'required|alpha|min:2|max:15',
            'apellido' => 'required|alpha|min:2|max:20',
            'cedula'   => 'required|regex:/^([0-9]{6,8})$/',
            'email'    => 'required|email|max:50|unique:users',
            'password' => 'required|min:8|confirmed',
            'rol'      => 'required|in:farmacia,alimentacion',
            'rango'    => 'required|in:director,jefe,empleado'
        ]);

        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{
            
            User::create([
                'nombre'   => $data['nombre'],
                'apellido' => $data['apellido'],
                'cedula'   => $data['cedula'],
                'rol'      => $data['rol'],
                'rango'    => $data['rango'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password'])
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Usuario registrado']);
        }
    }

    public function allUsuarios(){

        return User::get();
    }

    public function getUsuario($id){

        $usuario = User::where('id',$id)->first();

        if(!$usuario){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);            
        }
        else{

            return $usuario; 
        }

    }

    public function editUsuario(Request $request,$id){

        $usuario = User::where('id',$id)->first();

        if(!$usuario){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);            
        }
        else{
            
            $data = $request->all();

            $validator = Validator::make($data,[
                    'nombre'   => 'required|alpha|min:3|max:15',
                    'apellido' => 'required|alpha|min:3|max:20',
                    'cedula'   => 'required|regex:/^([0-9]{6,8})$/',
                    'rol'      => 'required|in:farmacia,alimentacion',
                    'rango'    => 'required|in:director,jefe,empleado'
            ]);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
               
                User::where('id',$id)->update([

                    'nombre'        => $data['nombre'],
                    'apellido'      => $data['apellido'],
                    'cedula'        => $data['cedula'],
                    'rol'           => $data['rol'],
                    'rango'         => $data['rango']
                ]);

                return Response()->json(['status' => 'success', 'menssage' => 'Cambios Guardados']);

            }
        }
    }

    public function elimUsuario(Request $request,$id){

         $usuario = User::where('id',$id)->first();

        if(!$usuario){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);            
        }
        else{
            
            User::where('id',$id)->delete();
            return Response()->json(['status' => 'success', 'menssage' => 'Usuario Eliminado']);
            
        }
    }
    
}
