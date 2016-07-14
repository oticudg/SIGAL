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

    public function allRoles(){
      return Role::orderBy('id', 'desc')->get();
    }

    public function allPermissions(){
      return Permission::get(['id', 'nombre', 'modulo']);
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
}
