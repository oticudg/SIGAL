<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Role;
use App\Permission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class rolesController extends Controller
{
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


}
