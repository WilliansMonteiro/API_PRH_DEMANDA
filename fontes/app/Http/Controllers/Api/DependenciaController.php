<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Area\Entities\VwDependencia;



class DependenciaController extends Controller
{

    public function search(Request $request)
    {
        $cd_empresa = null;
        $cd_dependencia = null; 
        $cd_empresa_dependencia = null;
        $cd_tipo_dependencia = null;
        $ativo = null;
       
        if($request->filled('cd_empresa')){
            $cd_empresa = $request->cd_empresa;
        }
        
        if($request->filled('cd_dependencia')){
            $cd_dependencia = $request->cd_dependencia;
        }
       
        if($request->filled('cd_empresa_dependencia')){
            $cd_empresa_dependencia = $request->cd_empresa_dependencia;
        }
        
        if($request->filled('cd_tipo_dependencia')){
            $cd_tipo_dependencia = $request->cd_tipo_dependencia;
        }
        
        if($request->filled('ativo')){
            $ativo = $request->ativo;
        }

        $dependencia = new VwDependencia();
        $consultaDependencia = $dependencia->consultarDependenciaApi($cd_dependencia, $cd_empresa, $cd_empresa_dependencia, $cd_tipo_dependencia, $ativo);
        

        
        if(empty($consultaDependencia)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => collect($consultaDependencia)]);

    }


}
