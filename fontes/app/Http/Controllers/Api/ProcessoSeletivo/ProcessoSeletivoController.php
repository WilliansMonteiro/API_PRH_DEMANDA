<?php

namespace App\Http\Controllers\Api\ProcessoSeletivo;

use App\Entities\Benner\DadoUsuarioBenner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Area\Entities\DependenciaRH;
use Modules\ProcessoSeletivo\Entities\CronogramaProcessoSeletivo;
use Modules\ProcessoSeletivo\Entities\FuncaoVaga;
use Modules\ProcessoSeletivo\Entities\GrupoProcessoSeletivo;
use Modules\ProcessoSeletivo\Entities\InscricaoProcessoSeletivo;
use Modules\ProcessoSeletivo\Entities\ProcessoSeletivo;
use Illuminate\Support\Str;

class ProcessoSeletivoController extends Controller
{
    public function search(Request $request)
    {
        $dn_processo_seletivo = null;
        $no_processo_seletivo = null;
        $cd_area_benner = null;
        $consulta = [];

        if ($request->filled('dn_processo_seletivo')) {
            $dn_processo_seletivo = $request->dn_processo_seletivo;
        }

        if ($request->filled('no_processo_seletivo')) {
            $no_processo_seletivo = $request->no_processo_seletivo;
        }

        if ($request->filled('cd_area_benner')) {
            $cd_area_benner = $request->cd_area_benner;
        }

        $numero = substr($dn_processo_seletivo, 0, 3);
        $ano = substr($dn_processo_seletivo, 3, 7);
        $numero_formatado = $numero.'/'.$ano;
        $dn_processo_seletivo = $numero_formatado;

        $processo_seletivo = new ProcessoSeletivo();

        if (!is_null($cd_area_benner)) {
            /*
            *   O método buscaSelecoesInternasComExclusividade() inclui processos seletivos exclusivos
            *   da área solicitante, com a flag de st_exclusivo_area_solicitante = 'S'
            *   Os processos exclusivos retornados são apenas para a área do usuário (ex.: NUEXT)
            */
            $consulta = $processo_seletivo->buscaSelecoesInternasComExclusividade($dn_processo_seletivo, $no_processo_seletivo, $cd_area_benner);
        } else {
            // O método buscaSelecoesInternasSemExclusividade() busca somente processos seletivos gerais,
            // com a flag de st_exclusivo_area_solicitante = 'N'
            $consulta = $processo_seletivo->buscaSelecoesInternasSemExclusividade($dn_processo_seletivo, $no_processo_seletivo);
        }

        if (empty($consulta)) {
            return response()->json(['success' => true, 'data' => collect($consulta), 'message' => 'Nenhum registro encontrado.']);
        }

        return response()->json(['success' => true, 'data' => collect($consulta)]);
    }

    public function getProcessoSeletivo(Request $request)
    {
        $sq_processo_seletivo = null;

        if ($request->filled('sq_processo_seletivo')) {
            $sq_processo_seletivo = $request->sq_processo_seletivo;
        }

        // $processo_seletivo = ProcessoSeletivo::where('sq_processo_seletivo', '=', $sq_processo_seletivo)
        //                                         ->with('solicitante',
        //                                                'gruposProcessoSeletivo.empresaDependenciaDiretoria',
        //                                                'gruposProcessoSeletivo.empresaDependenciaSuperintendencia',
        //                                                'gruposProcessoSeletivo.empresaDependenciaGerencia',
        //                                                'gruposProcessoSeletivo.cronogramas.etapa',
        //                                                'funcoesVagas.funcao',
        //                                                'funcoesVagas.formulario')
        //                                         ->get();

        $processo_seletivo  = ProcessoSeletivo::where('sq_processo_seletivo', '=', $sq_processo_seletivo)->first();
        $solicitante        = DependenciaRH::where('cd_dependencia_empresa_rh', $processo_seletivo['cd_area_solicitante'])->first();
        $cronogramas_grupos = $this->getGruposProcessoSeletivo($sq_processo_seletivo);
        $nome_grupos        = $this->getNomesGrupos($sq_processo_seletivo);
        $funcoes_vagas      = $this->getFuncoesProcessoSeletivo($sq_processo_seletivo);

        $consulta = [$processo_seletivo, $solicitante, $cronogramas_grupos, $nome_grupos, $funcoes_vagas];

        return $consulta;
    }

    public function getInscricoesAtivasUsuario(Request $request)
    {
        $nr_matricula = $request->nr_matricula;
        $arrInscricoesAtivas = [];

        foreach ($request->all() as $key => $value) {
            if (Str::contains($key, 'sq_funcao_vaga')) {
                $inscricao_ativa = new InscricaoProcessoSeletivo();
                $sq_funcao_vaga = $value;

                $inscricao_ativa = $inscricao_ativa->buscaInscricaoAtiva($sq_funcao_vaga, $nr_matricula);

                if (count($inscricao_ativa) > 0) {
                    array_push($arrInscricoesAtivas, $inscricao_ativa[0]->sq_funcao_vaga);
                }
            }
        }
        return $arrInscricoesAtivas;
        // return response()->json(['success' => true, 'data' => $arrInscricoesAtivas]);
    }

    public function getGruposProcessoSeletivo($sq_processo_seletivo)
    {
        $grupos = GrupoProcessoSeletivo::where('sq_processo_seletivo', '=', $sq_processo_seletivo)
        ->with(['cronogramas.etapa'])
        ->get();
        return $grupos;
    }

    public function getNomesGrupos($sq_processo_seletivo)
    {
        $grupos = GrupoProcessoSeletivo::where('sq_processo_seletivo', '=', $sq_processo_seletivo)->get();
        $arrGrupos        = [];
        $objGrupo         = [];
        $string_grupo     = null;
        $diretoria        = null;
        $superintendencia = null;
        $gerencia         = null;

        foreach ($grupos as $chave => $grupo) {
            $limite = $chave + 1;

            if ($chave < $limite) {
                if ($grupo->sq_processo_seletivo == $grupos[$chave]['sq_processo_seletivo']) {
                    $diretoria = $grupo->empresaDependenciaDiretoria->sg_dependencia;

                    if (!is_null($grupo->cd_empresa_dependencia_sup)) {
                        $superintendencia = '/'.$grupo->empresaDependenciaSuperintendencia->sg_dependencia;
                    } else {
                        $superintendencia = '';
                    }

                    if (!is_null($grupo->cd_empresa_dependencia_ger)) {
                        $gerencia = '/'.$grupo->empresaDependenciaGerencia->sg_dependencia;
                    } else {
                        $gerencia = '';
                    }

                    $string_grupo = $diretoria . $superintendencia . $gerencia;

                    $objGrupo['sq_grupo_processo_seletivo'] = $grupo->sq_grupo_processo_seletivo;
                    $objGrupo['sg_grupo']                   = $string_grupo;

                    array_push($arrGrupos, $objGrupo);
                    $chave++;
                }
            }
        }
        return $arrGrupos;
    }

    public function getFuncoesProcessoSeletivo($sq_processo_seletivo)
    {
        $funcoes_vagas    = FuncaoVaga::where('sq_processo_seletivo', '=', $sq_processo_seletivo)
        ->with(['funcao', 'formulario.formularioPergunta.perguntaFormulario.respostas.itensRespostas.tipoItemResposta', 'formulario.cienteFormulario.ciente'])
        ->get();
        return $funcoes_vagas;
    }

    public function getCronogramasProcessoSeletivo($sq_processo_seletivo)
    {
        $cronogramas    = CronogramaProcessoSeletivo::where('sq_processo_seletivo', '=', $sq_processo_seletivo)->get();
        $arrCronogramas = [];
        $objCronograma  = [];

        foreach ($cronogramas as $chave => $etapa) {
            $limite = $chave + 1;

            if ($chave < $limite) {
                if ($etapa->sq_grupo_processo_seletivo == $cronogramas[$chave]['sq_grupo_processo_seletivo']) {
                    $nome_etapa = $etapa->etapa->ds_etapa_processo_seletivo;
                    $objCronograma['sq_cronograma_processo_seletivo'] = $etapa->sq_cronograma_processo_seletiv;
                    $objCronograma['sq_etapa_processo_seletivo']      = $etapa->sq_etapa_processo_seletivo;
                    $objCronograma['sq_grupo_processo_seletivo']      = $etapa->sq_grupo_processo_seletivo;
                    $objCronograma['sq_processo_seletivo']            = $etapa->sq_processo_seletivo;
                    $objCronograma['no_etapa']                        = $nome_etapa;
                    $objCronograma['dt_inicio_etapa']                 = $etapa->dt_inicio_etapa;
                    $objCronograma['dt_fim_etapa']                    = $etapa->dt_inicio_etapa;
                    $objCronograma['dt_inclusao']                     = $etapa->dt_inclusao;
                }
                array_push($arrCronogramas, $objCronograma);
                $chave++;
            }
        }
        return $arrCronogramas;
    }

    public function getAreaUsuarioBenner(Request $request)
    {
        $nr_matricula = null;

        if ($request->filled('nr_matricula')) {
            $nr_matricula = $request->nr_matricula;
        }

        $usuario_benner = DadoUsuarioBenner::where([
            ['nr_matricula', $nr_matricula],
            ['st_registro_ativo', 'S']])
            ->first();

        if (is_null($usuario_benner)) {
            return response()->json(['success' => true, 'data' => collect($usuario_benner), 'message' => 'Usuário não encontrado.']);
        }

        $area_usuario = $usuario_benner->cd_area_benner;
        $arrArea = ["cd_area_benner" => $area_usuario];

        return response()->json(['success' => true, 'data' => collect($arrArea), 'message' => 'Usuário encontrado.']);

        // return $area_usuario;
    }
}
