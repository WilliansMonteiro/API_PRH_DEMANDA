<?php

namespace App\Http\Controllers\Api;

use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FuncionarioController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {

        $funcionarioBenner = new Funcionario();
        $consultaDadosFuncionario = $funcionarioBenner->retornaDadosFuncionarioApi($request->matricula);

        if(empty($consultaDadosFuncionario)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => $consultaDadosFuncionario]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDadosAfastamento(Request $request)
    {

        $funcionarioBenner = new Funcionario();
        $consultaDadosAfastamentos = $funcionarioBenner->retornaDadosAfastamentosFuncionario($request->matricula);

        if(empty($consultaDadosAfastamentos)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => $consultaDadosAfastamentos]);

    }


}
