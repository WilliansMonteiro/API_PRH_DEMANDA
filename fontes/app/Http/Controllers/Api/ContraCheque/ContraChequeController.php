<?php

namespace App\Http\Controllers\Api\ContraCheque;

use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entities\Benner\ContraChequeEmpregado;
use Barryvdh\DomPDF\Facade as PDF;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(title="API de ContraCheque", version="1.0")
 */


class ContraChequeController extends Controller
{
    
    /**
     * @OA\Get(
     *     path="/contra-cheque/search-pdf",
     *     tags={"ContraCheque"},
     *     summary="Busca o PDF do contracheque",
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         description="CPF do empregado",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="mesReferencia",
     *         in="query",
     *         description="Mês de referência do contracheque",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="anoReferencia",
     *         in="query",
     *         description="Ano de referência do contracheque",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="idContracheque",
     *         in="query",
     *         description="ID do contracheque",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF gerado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Parâmetro inválido"
     *     )
     * )
     */
    public function search(Request $request)
    {

        if(!$request->filled('cpf')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro cpf.']);
        }
        if(!$request->filled('mesReferencia')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro mesReferencia.']);
        }
        if(!$request->filled('anoReferencia')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro anoReferência.']);
        }
        if(!$request->filled('idContracheque')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro idcontracheque.']);
        }


        $numero_cpf = $this->formatarCPF($request->cpf);
        $mes_referencia = $request->mesReferencia;
        $ano_referencia = $request->anoReferencia;
        $id_contracheque = $request->idContracheque;

        $contra_cheque = new ContraChequeEmpregado();

        $dados_contra_cheque = $contra_cheque->consultaContraCheque($numero_cpf, $ano_referencia, $mes_referencia, $id_contracheque);


        if(empty($dados_contra_cheque)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        if(empty($contra_cheque->consultaDescricaoContrache($dados_contra_cheque[0]->idcontracheque))){
            return response()->json(['success' => true, 'message' => 'Não foram encontrados registros da descrição do contra cheque']);
        }

        if(empty($contra_cheque->consultaInformacoesPessoais($dados_contra_cheque[0]->cpf))){
            return response()->json(['success' => true, 'message' => 'Não foram encontrados registros de conta do empregado!']);
        }

        if(empty($contra_cheque->consultaInformacoesLotacaoFuncao($dados_contra_cheque[0]->cpf))){
            return response()->json(['success' => true, 'message' => 'Não foram encontrados registros de lotação e função do empregado!']);
        }


        $dados_pdf   = $contra_cheque->consultaContraCheque($numero_cpf, $ano_referencia, $mes_referencia, $id_contracheque)[0];
        $descricao   = $contra_cheque->consultaDescricaoContrache($dados_pdf->idcontracheque);
        $informacoes = $contra_cheque->consultaInformacoesPessoais($dados_pdf->cpf)[0];
        $lotacao_funcao = $contra_cheque->consultaInformacoesLotacaoFuncao($dados_pdf->cpf)[0];

        $pdf = PDF::loadView('demonstrativoPagamento.contra-cheque', compact('dados_pdf', 'descricao', 'informacoes', 'lotacao_funcao', 'mes_referencia', 'ano_referencia'))->setPaper('a4', 'landscape');
        // return $pdf->download('demonstrativo-pagamento-empregado.pdf');
        return $pdf->stream('a4', 'landscape');


        if(empty($dados_pdf)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => $dados_pdf]);

    }
    
    /**
     * @OA\Get(
     *     path="/contra-cheque/search-dados",
     *     tags={"ContraCheque"},
     *     summary="Busca dados do contracheque",
     *     @OA\Parameter(
     *         name="cpf",
     *         in="query",
     *         description="CPF do empregado",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="mesReferencia",
     *         in="query",
     *         description="Mês de referência do contracheque",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="anoReferencia",
     *         in="query",
     *         description="Ano de referência do contracheque",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados encontrados"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum registro encontrado"
     *     )
     * )
     */
    public function searchDados(Request $request)
    {
        if(!$request->filled('cpf')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro cpf.']);
        }
        if(!$request->filled('mesReferencia')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro mesReferencia.']);
        }
        if(!$request->filled('anoReferencia')){
            return response()->json(['success' => false, 'message' => 'Preencha o parâmetro anoReferência.']);
        }

        $numero_cpf = $this->formatarCPF($request->cpf);
        $mes_referencia = $request->mesReferencia;
        $ano_referencia = $request->anoReferencia;

        $contra_cheque = new ContraChequeEmpregado();

        $dados_contra_cheque = $contra_cheque->consultaContraChequeDados($numero_cpf, $ano_referencia, $mes_referencia);


        if(empty($dados_contra_cheque)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        if(empty($contra_cheque->consultaDescricaoContrache($dados_contra_cheque[0]->idcontracheque))){
            return response()->json(['success' => true, 'message' => 'Não foram encontrados registros da descrição do contra cheque']);
        }

        if(empty($contra_cheque->consultaInformacoesPessoais($dados_contra_cheque[0]->cpf))){
            return response()->json(['success' => true, 'message' => 'Não foram encontrados registros de conta do empregado!']);
        }

        if(empty($contra_cheque->consultaInformacoesLotacaoFuncao($dados_contra_cheque[0]->cpf))){
            return response()->json(['success' => true, 'message' => 'Não foram encontrados registros de lotação e função do empregado!']);
        }


        $dados_pdf = $contra_cheque->consultaContraChequeDados($numero_cpf, $ano_referencia, $mes_referencia);


        if (empty($dados_pdf)) {
             return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

       $resultado = [];

        foreach ($dados_pdf as $dados) {
              $tipoResumo = $dados->tipofolha; // Assumindo que há um campo tipo_resumo no seu objeto $dados_pdf
        if(!isset($resultado[$tipoResumo])) {
            $resultado[$tipoResumo] = [
            'idContraCheque'     => $dados->idcontracheque ? $dados->idcontracheque : null,
            'Mês Referência'     => $mes_referencia,
            'Ano Referência'     => $ano_referencia,
            'Total Bruto'        => $this->converterFormatoMoeda($dados->rendimentos),
            'Total Descontos'    => $this->converterFormatoMoeda($dados->descontos),
            'Total Líquido'      => $this->converterFormatoMoeda($dados->liquido),
            'Recolhimento Fgts'  => $this->converterFormatoMoeda($dados->basecalcfgts),
            'Margem Consignável' => $this->converterFormatoMoeda($dados->margemconsignavelbruta),
            'Rendimentos'       => [],
            'Descontos'         => [],
        ];
    }

       // Adicionar rendimentos e descontos específicos para cada tipo de resumo
        $consulta_rendimentos = $contra_cheque->consultaDescricaoContracheRendimentos($dados->idcontracheque);
        $consulta_descontos = $contra_cheque->consultaDescricaoContracheDescontos($dados->idcontracheque);

        $rendimentosArray = json_decode(json_encode($consulta_rendimentos), true);
        $descontosArray = json_decode(json_encode($consulta_descontos), true);

        foreach ($rendimentosArray as $coluna => $valor) {
        $valor['valor'] = $this->converterFormatoMoeda($valor['valor']);
        $resultado[$tipoResumo]['Rendimentos'][] = [$coluna => $valor];
       }

        foreach ($descontosArray as $coluna => $valor) {
        $valor['valor'] = $this->converterFormatoMoeda($valor['valor']);
        $resultado[$tipoResumo]['Descontos'][] = [$coluna => $valor];
       }

 }

    return response()->json(['success' => true, 'data' => $resultado]);


    }


    public function converterFormatoMoeda($num)
    {
        return number_format($num, 2, ',', '.');
    }


    function formatarCPF($cpf) {
        // Limpa caracteres indesejados
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        // Adiciona a máscara
        $cpfFormatado = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
        return $cpfFormatado;
    }


}
