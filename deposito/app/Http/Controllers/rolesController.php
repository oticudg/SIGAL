<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Role;
use App\Permissions_assigned;
use App\Permission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class rolesController extends Controller
{
    private $message = [
      'nombre.unique'       => 'Ya fue registrado un rol con este nombre',
      'permisos.required'   => 'No se han asignado permisos a este rol',
      'permisos.permissions'=> 'Los permisos asignados son invalidos'
    ];

    public function index()
    {
      return view('roles.index');
    }

    public function viewRegistrar(){
      return view('roles.registrar');
    }

    public function viewEditar(){
      return view('roles.editar');
    }

    public function allRoles(){
      return Role::orderBy('id', 'desc')->get();
    }

    public function allPermissions(){
      return Permission::get(['id', 'nombre', 'modulo']);
    }

    public function getRol($id){
      if(!Role::where('id', $id)->first()){
        return Response()->json(['status' => 'danger', 'message' => 'Este rol no existe']);
      }
      else{
        $nombre = Role::where('id', $id)->value('nombre');
        $permisos = Permissions_assigned::where('role', $id)->lists('permission');
        return Response()->json(['status' => 'success', 'nombre' => $nombre, 'permisos' => $permisos]);
      }
    }

    public function registrar(Request $request){

      $data = $request->all();

      $validator = Validator::make($data,[
          'nombre'   =>  'required|min:3|max:60|unique:roles',
          'permisos' =>  'required|permissions'
      ], $this->message);

      if($validator->fails()){
        return Response()->json(['status' => 'danger', 'message' => $validator->errors()->first()]);
      }
      else{

        $role = Role::create([
          'nombre' => $data['nombre']
        ])['id'];

        foreach ($data['permisos'] as $permiso) {
          Permissions_assigned::create([
            'role' => $role,
            'permission' => $permiso
          ]);
        }

        return Response()->json(['status' => 'success', 'message' => 'Rol registrado satisfactoriamente']);
      }
    }

    public function editar(Request $request, $id){

      $data = $request->all();

      $validator = Validator::make($data,[
          'nombre'   =>  'required|min:3|max:60',
          'permisos' =>  'required|permissions'
      ], $this->message);

      if(!Role::where('id', $id)->first()){
        return Response()->json(['status' => 'danger', 'message' => 'Este rol no existe']);
      }

      if($validator->fails()){
        return Response()->json(['status' => 'danger', 'message' => $validator->errors()->first()]);
      }
      else{

        $permisos = Permissions_assigned::where('role', $id)->lists('permission')->toArray();
        $add_permissions    = array_diff($data['permisos'], $permisos);
        $delete_permissions = array_diff($permisos, $data['permisos']);

        if($data['nombre'] == Role::where('id', $id)->value('nombre') &&
          (empty($add_permissions)  && empty($delete_permissions) )  ){
          return Response()->json(['status' => 'danger', 'message' => 'No se han hecho modificaciones']);
        }

        if($data['nombre'] != Role::where('id', $id)->value('nombre')){
          if(Role::where('nombre', $data['nombre'])->first()){
            return Response()->json(['status' => 'danger', 'message' => 'Ya fue registrado un rol con este nombre']);
          }
          else{
            Role::where('id', $id)->update([
              'nombre' => $data['nombre']
            ]);
          }
        }

        if(!empty($add_permissions)){
          foreach ($add_permissions as $permiso) {
            Permissions_assigned::create([
              'role' => $id,
              'permission' => $permiso
            ]);
          }
        }

        if(!empty($delete_permissions)){
          foreach ($delete_permissions as $permiso) {
            Permissions_assigned::where('role', $id)->where('permission', $permiso)->delete();
          }
        }
      }

      return Response()->json(['status' => 'success', 'message' => 'El rol ha sido modificado satisfactoriamente.']);
    }
}
