<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class modificacionesController extends Controller
{
    public function index(){
        return view('inventario.modificaciones.index');
    }
}
