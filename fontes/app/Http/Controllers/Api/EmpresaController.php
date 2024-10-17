<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Area\Entities\VwEmpresaRH;



class EmpresaController extends Controller
{

    public function search(Request $request)
    {
        $cd_empresa = null;
       
        if($request->filled('cd_empresa')){
            $cd_empresa = $request->cd_empresa;
        }

        $empresa = new VwEmpresaRH();
        $consultaEmpresa = $empresa->consultarEmpresaApi($cd_empresa);
        
        
        if(empty($consultaEmpresa)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => collect($consultaEmpresa)]);

    }


}
