<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entities\Benner\GestorLotacao;


class GestorLotacaoController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {

        $gestor_lotacao = new GestorLotacao();
        $consultarDados = $gestor_lotacao->query_gestor_lotacao_api($request);

        if(empty($consultarDados)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => $consultarDados]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */



}
