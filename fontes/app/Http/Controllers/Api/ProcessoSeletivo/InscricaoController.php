<?php

namespace App\Http\Controllers\Api\ProcessoSeletivo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ProcessoSeletivo\Entities\FuncaoVaga;
use Modules\ProcessoSeletivo\Entities\InscricaoProcessoSeletivo;
use Modules\ProcessoSeletivo\Http\Services\InscricaoService;

class InscricaoController extends Controller
{
    private $service;

    public function __construct(InscricaoService $service)
    {
        $this->service = $service;
    }

    public function getFormularioInscricao(Request $request)
    {
        $formulario_inscricao = $this->formulario($request->sq_funcao_vaga);
        return $formulario_inscricao;
    }

    // Método auxiliar chamado pelo getFormularioInscricao
    public function formulario($sq_funcao_vaga)
    {
        $funcao_vaga = FuncaoVaga::where('sq_funcao_vaga', '=', $sq_funcao_vaga)
        ->with(['funcao', 'formulario.formularioPergunta.perguntaFormulario.respostas.itensRespostas.tipoItemResposta', 'formulario.cienteFormulario.ciente'])
        ->get();
        return $funcao_vaga;
    }

    public function store(Request $request)
    {
        if ($request->filled('sq_inscricao_processo_seletivo')) {
            $sq_inscricao_processo_seletivo = $request->sq_inscricao_processo_seletivo;

            $verificaInscricao = InscricaoProcessoSeletivo::where([
                ['sq_inscricao_processo_seletivo', '=', $sq_inscricao_processo_seletivo],
                ['st_registro_ativo', 'A']
            ])->first();

            if (!is_null($verificaInscricao)) {
                return $this->update($request, $sq_inscricao_processo_seletivo);
            }
        }

        return $this->service->saveInscricao($request);
    }

    public function update(Request $request, $sq_inscricao_processo_seletivo)
    {
        $this->service->deleteRespostasFormularioInscricao($sq_inscricao_processo_seletivo);
        return $this->service->updateRespostasFormularioInscricao($request, $sq_inscricao_processo_seletivo);
    }

    public function search(Request $request)
    {
        $dn_processo_seletivo = null;
        $no_processo_seletivo = null;
        $cd_area_solicitante  = null;
        $cd_funcao            = null;
        $cd_status_insc_proc_seletiv = null;
        $nr_matricula = $request->nr_matricula;

        if ($request->filled('dn_processo_seletivo')) {
            $dn_processo_seletivo = $request->dn_processo_seletivo;
        }

        if ($request->filled('no_processo_seletivo')) {
            $no_processo_seletivo = $request->no_processo_seletivo;
        }

        if ($request->filled('cd_area_solicitante')) {
            $cd_area_solicitante = $request->cd_area_solicitante;
        }

        if ($request->filled('cd_funcao')) {
            $cd_funcao = $request->cd_funcao;
        }

        if ($request->filled('cd_status_insc_proc_seletiv')) {
            $cd_status_insc_proc_seletiv = $request->cd_status_insc_proc_seletiv;
        }

        // TRATAMENTO USADO CASO O SERVIÇO ENVIE OS PARÂMETROS NA URL (GET)
        // $numero = substr($dn_processo_seletivo, 0, 3);
        // $ano = substr($dn_processo_seletivo, 3, 7);
        // $numero_formatado = $numero.'/'.$ano;
        // $dn_processo_seletivo = $numero_formatado;

        $processo_seletivo = new InscricaoProcessoSeletivo();
        $consulta = $processo_seletivo
                    ->buscaInscricaoUsuario(
                        $nr_matricula,
                        $dn_processo_seletivo,
                        $no_processo_seletivo,
                        $cd_area_solicitante,
                        $cd_funcao,
                        $cd_status_insc_proc_seletiv
                    );

        if (empty($consulta)) {
            return response()->json(['success' => true, 'data' => collect($consulta), 'message' => 'Nenhum registro encontrado.']);
        }

        return response()->json(['success' => true, 'data' => collect($consulta)]);
    }

    public function getInscricao(Request $request)
    {
        $nr_matricula = $request->nr_matricula;
        $sq_inscricao = $request->sq_inscricao_processo_seletivo;

        $inscricao    = InscricaoProcessoSeletivo::where([
                            ['nr_matricula', '=', $nr_matricula],
                            ['sq_inscricao_processo_seletivo', '=', $sq_inscricao]
                        ])
                        ->with('funcaoVaga.funcao',
                               'funcaoVaga.formulario',
                               'funcaoVaga.processoSeletivo.solicitante',
                               'funcaoVaga.grupoProcessoSeletivo.empresaDependenciaDiretoria',
                               'funcaoVaga.grupoProcessoSeletivo.empresaDependenciaSuperintendencia',
                               'funcaoVaga.grupoProcessoSeletivo.empresaDependenciaGerencia',
                               'funcaoVaga.grupoProcessoSeletivo.cronogramas.etapa',
                               'comparacaoRequisitosBenner',
                               'historico.status')
                        ->get();
        return $inscricao;
    }

    public function formularioRespondido(Request $request)
    {
        $inscricao = InscricaoProcessoSeletivo::where('sq_inscricao_processo_seletivo', '=', $request->sq_inscricao_processo_seletivo)
                    ->with(['funcaoVaga.funcao', 'funcaoVaga.formulario.formularioPergunta.perguntaFormulario.respostas.itensRespostas.tipoItemResposta', 'funcaoVaga.formulario.cienteFormulario.ciente', 'respostasInscricao.itemResposta.resposta.perguntaFormulario'])
                    ->get();
        return $inscricao;
    }

    public function cancel(Request $request)
    {
        $response = new \stdClass();
        $response->status = false;

        $inscricao = InscricaoProcessoSeletivo::where([
            ['sq_inscricao_processo_seletivo', $request->sq_inscricao_processo_seletivo]
        ])->first();

        if(!is_null($inscricao)) {
            return $this->service->cancelaInscricao($inscricao);
        }
    }

    public function comparaDadosBannerInscricao(Request $request)
    {
        return response()->json($this->service->comparaDadosBannerInscricao($request));
    }
}
