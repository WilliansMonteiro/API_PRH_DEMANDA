<?php

namespace App\Http\Controllers\Api\PAF;

use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SAA\Entities\Fundesec;


class AdmController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {

        $usuario = new Fundesec();

        $consultaDadosUsuario = $usuario->retorna_usuario_saa($request->matricula);


        if(empty($consultaDadosUsuario)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }
        return response()->json(['success' => true, 'data' => $consultaDadosUsuario]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

}
