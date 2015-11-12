<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Privilegio;

class usersController extends Controller
{

    private $menssage = [
        'password.required'  => 'El campo contraseña es obligatorio',
        'password.min'       => 'La contraseña debe contener al menos 8 caracteres',
        'password.confirmed' => 'La contraseña no coincide'
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

    public function registrar(Request $request){   

        $data = $request->all();

        $validator = Validator::make($data,[
            'nombre'         => 'required|alpha|min:2|max:15',
            'apellido'       => 'required|alpha|min:2|max:20',
            'cedula'         => 'required|cedula',
            'email'          => 'required|email|max:50|unique:users',
            'password'       => 'required|min:8|confirmed',
            'pUsuario'       => 'required',
            'pUsuarioR'      => 'required',
            'pUsuarioM'      => 'required',
            'pUsuarioE'      => 'required',
            'pDepartamento'  => 'required',
            'pDepartamentoR' => 'required',
            'pDepartamentoE' => 'required',
            'pInsumo'        => 'required',
            'pInsumoR'       => 'required',
            'pInsumoM'       => 'required',
            'pInsumoE'       => 'required',
            'pInventario'    => 'required',
            'pInventarioH'   => 'required',
            'pModificacion'  => 'required',
            'pEntrada'       => 'required',
            'pEntradaV'      => 'required',
            'pEntradaR'      => 'required',
            'pSalida'        => 'required',
            'pSalidaV'       => 'required',
            'pSalidaR'       => 'required',
            'pEstadistica'   => 'required'
        ], $this->menssage);

        if($validator->fails()){

            return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
        }
        else{

            if( $data['pUsuario'] == null && $data['pDepartamento'] == null && $data['pInsumo'] == null
                && $data['pInventario'] == null && $data['pModificacion'] == null && 
                $data['pEntrada'] == null && $data['pSalida'] == null && $data['pEstadistica'] == null){

                return Response()->json(['status' => 'danger', 'menssage' => 'Por favor Asigné al menos un privilegio a este usuario']);
            }
            
            $usuario = User::create([
                        'nombre'   => $data['nombre'],
                        'apellido' => $data['apellido'],
                        'cedula'   => $data['cedula'],
                        'email'    => $data['email'],
                        'password' => bcrypt($data['password'])
                    ])['id'];

            Privilegio::create([
                'usuario'        => $usuario,
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
                'estadisticas'   => $data['pEstadistica']
            ]);

            return Response()->json(['status' => 'success', 'menssage' => 'Usuario registrado']);
        }
    }

    public function allUsuarios(){

        return User::select(DB::raw('CONCAT(nombre, " " , apellido) as nombre'), 'cedula', 
            'email', 'id')->get();
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
                    'entradas as pEntradaV', 'entradaR as pEntradaR', 'salidas as pSalidaV', 'salidaR as pSalidaR', 'estadisticas as pEstadistica')
                  ->first();

        if(!$usuario){

            return Response()->json(['status' => 'danger', 'menssage' => 'Este usuario no exist']);            
        }
        else{

            return ['usuario' => $usuario]; 
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
                    'cedula'   => 'required|cedula',
                    'password'       => 'min:8|confirmed',
                    'pUsuario'       => 'required',
                    'pUsuarioR'      => 'required',
                    'pUsuarioM'      => 'required',
                    'pUsuarioE'      => 'required',
                    'pDepartamento'  => 'required',
                    'pDepartamentoR' => 'required',
                    'pDepartamentoE' => 'required',
                    'pInsumo'        => 'required',
                    'pInsumoR'       => 'required',
                    'pInsumoM'       => 'required',
                    'pInsumoE'       => 'required',
                    'pInventario'    => 'required',
                    'pInventarioH'   => 'required',
                    'pModificacion'  => 'required',
                    'pEntrada'       => 'required',
                    'pEntradaV'      => 'required',
                    'pEntradaR'      => 'required',
                    'pSalida'        => 'required',
                    'pSalidaV'       => 'required',
                    'pSalidaR'       => 'required',
                    'pEstadistica'   => 'required'
            ], $this->menssage);


            if($validator->fails()){

                return Response()->json(['status' => 'danger', 'menssage' => $validator->errors()->first()]);
            }
            else{
                
                if( $data['pUsuario'] == null && $data['pDepartamento'] == null && $data['pInsumo'] == null
                && $data['pInventario'] == null && $data['pModificacion'] == null && 
                $data['pEntrada'] == null && $data['pSalida'] == null && $data['pEstadistica'] == null){

                    return Response()->json(['status' => 'danger', 'menssage' => 'Por favor Asigné al menos un privilegio a este usuario']);
                }

                if( isset($data['password']) ){
                    
                    User::where('id',$id)->update([
                        'password' => bcrypt($data['password'])
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
                    'estadisticas'   => $data['pEstadistica']
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
