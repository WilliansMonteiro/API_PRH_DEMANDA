<?php

namespace App\Http\Controllers\Api\ProcessoSeletivo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ProcessoSeletivo\Entities\InscricaoProcessoSeletivo;
use Modules\ProcessoSeletivo\Entities\StatusInscricaoProcessoSeletivo;
use Modules\ProcessoSeletivo\Http\Services\RecursoInscricaoService;
use Illuminate\Support\Str;

class RecursoInscricaoController extends Controller
{
    private $service;

    public function __construct(RecursoInscricaoService $service)
    {
        $this->service = $service;
    }

    public function solicitaRecursoInscricao(Request $request)
    {
        $response = new \stdClass();
        $response->status = false;
        $retornoSolicitacaoRecurso = false;
        $respostaAnexos = false;

        $inscricao = InscricaoProcessoSeletivo::where([
            ['sq_inscricao_processo_seletivo', $request->sq_inscricao_processo_seletivo]
        ])->first();
        $nr_matricula = $request->nr_matricula;
        $justificativa = $request->ds_justificativa;
        $status = StatusInscricaoProcessoSeletivo::EM_RECURSO;

        if(!is_null($inscricao)) {
            $retornoSolicitacaoRecurso = $this->service->solicitaRecursoInscricao($nr_matricula, $inscricao, $justificativa, $status);

            if ($retornoSolicitacaoRecurso) {
                $requisicao = $request->all();
                $anexosSalvos = $this->service->enviaAnexosRecurso($requisicao);

                if (!empty($anexosSalvos) && count($anexosSalvos) > 0) {
                    $respostaAnexos = $this->service->salvaAnexosInscricao($anexosSalvos, $nr_matricula, $inscricao);

                    if ($respostaAnexos) {
                        $response->status = true;

                        return response()->json(['success' => true, 'message' => 'Solicitação de recurso registrada com sucesso e anexos salvos.']);
                    }
                }
            }
        }

        return response()->json(['success' => false, 'message' => "Erro ao solicitar recurso."]);
    }
}
