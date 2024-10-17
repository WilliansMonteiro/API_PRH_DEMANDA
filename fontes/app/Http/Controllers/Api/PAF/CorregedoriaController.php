<?php

namespace App\Http\Controllers\Api\PAF;

use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SAA\Entities\Fundesec;


class CorregedoriaController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {

        $funcionarioBenner = new Funcionario();
        $consultaDadosFuncionario = $funcionarioBenner->retornaDadosFuncionarioApiMgc($request->matricula);

        if(empty($consultaDadosFuncionario)){
            return response()->json(['success' => false, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => $consultaDadosFuncionario]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

}
