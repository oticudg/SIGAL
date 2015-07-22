<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class insumosController extends Controller
{   
    public function index()
    {
        return view('insumos/indexInsumos');
    }
}
