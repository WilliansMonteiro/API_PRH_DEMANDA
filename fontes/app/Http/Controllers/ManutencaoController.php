<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

class ManutencaoController extends Controller
{

    public function index()
    {      
      return view('auth.developer');

    }

    public function exec(Request $request)
    {
      
      $exitCode = Artisan::call($request->get('ds_comando'));  
      $resultado = Artisan::output();   
      dd($resultado);

    }    
}
