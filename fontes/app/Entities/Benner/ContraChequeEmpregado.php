<?php

namespace App\Entities\Benner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ContraChequeEmpregado extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;




    public function consultaContraCheque($numero_cpf, $ano_referencia, $mes_referencia, $id_contracheque)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $retorno = null;
        $sql = " SELECT folhasCalculadas.handle AS idContracheque
        ,funcionario.MATRICULA AS matricula
        ,funcionario.K9_MATRICULADIGITO AS matriculaDigito
        ,funcionario.CPFNUMERO AS cpf
        ,EXTRACT(YEAR FROM competencia.competencia) AS anoReferencia
        ,EXTRACT(MONTH FROM competencia.competencia) AS mesReferencia
        ,tiposFolha.NOME AS tipoFolha
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 257
                AND folhafuncionario = folhasCalculadas.handle
            ) AS rendimentos
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 258
                AND folhafuncionario = folhasCalculadas.handle
            ) AS descontos
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 259
                AND folhafuncionario = folhasCalculadas.handle
            ) AS liquido
        ,folhasCalculadas.tipoCalculo
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 347
                AND folhafuncionario = folhasCalculadas.handle
            ) AS depositoFgts
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3943
                AND folhafuncionario = folhasCalculadas.handle
            ) AS baseCalcFgts
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3350
                AND folhafuncionario = folhasCalculadas.handle
            ) AS margemConsignavelBruta
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 42103
                AND folhafuncionario = folhasCalculadas.handle
            ) AS margemConsignavelLiquida
        ,(SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 414
                AND folhafuncionario = folhasCalculadas.handle
            ) AS baseCalcInss
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 340
                AND folhafuncionario = folhasCalculadas.handle
            ) AS baseCalcIrrf
        ,(
            SELECT ROUND(valor / horas)
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 348
                AND folhafuncionario = folhasCalculadas.handle
            ) AS numDependentes
            ,(
		    SELECT salariohora
		    FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
		    WHERE verba = 3318
			AND folhafuncionario = folhasCalculadas.handle
		    ) AS cargahoraria
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 42371
                AND folhafuncionario = folhasCalculadas.handle
            ) AS folgasTre
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3442
                AND folhafuncionario = folhasCalculadas.handle
            ) AS abonosNovos
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3432
                AND folhafuncionario = folhasCalculadas.handle
            ) AS abonosVelhos FROM {$constanteBanco}.DO_FUNCIONARIOS funcionario INNER JOIN {$constanteBanco}.FP_FUNCIONARIOFOLHASCALCULADAS folhasCalculadas ON funcionario.handle = folhasCalculadas.FUNCIONARIO INNER JOIN {$constanteBanco}.FP_COMPETENCIAS competencia ON folhasCalculadas.competencia = competencia.handle INNER JOIN {$constanteBanco}.z_flagsitens tiposFolha ON tiposFolha.handle = folhasCalculadas.TIPOFOLHA WHERE (
            SELECT COUNT(folhafuncionario)
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS folhaVerba
            INNER JOIN {$constanteBanco}.FP_VERBAS verba ON folhaVerba.VERBA = verba.HANDLE
                AND verba.TIPOVERBA IN (
                    1
                    ,2
                    )
            WHERE folhafuncionario = folhasCalculadas.handle
            ) > 0
        AND folhasCalculadas.SITUACAO = 2
        AND funcionario.CPFNUMERO = '$numero_cpf' and EXTRACT(YEAR FROM competencia.competencia) = $ano_referencia and EXTRACT(MONTH FROM competencia.competencia) = $mes_referencia AND folhasCalculadas.handle = $id_contracheque";

       $retorno = DB::connection('oracleBenner')->select($sql);
       return $retorno;
    }


    public function consultaContraChequeDados($numero_cpf, $ano_referencia, $mes_referencia)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $retorno = null;
        $sql = " SELECT folhasCalculadas.handle AS idContracheque
        ,funcionario.MATRICULA AS matricula
        ,funcionario.K9_MATRICULADIGITO AS matriculaDigito
        ,funcionario.CPFNUMERO AS cpf
        ,EXTRACT(YEAR FROM competencia.competencia) AS anoReferencia
        ,EXTRACT(MONTH FROM competencia.competencia) AS mesReferencia
        ,tiposFolha.NOME AS tipoFolha
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 257
                AND folhafuncionario = folhasCalculadas.handle
            ) AS rendimentos
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 258
                AND folhafuncionario = folhasCalculadas.handle
            ) AS descontos
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 259
                AND folhafuncionario = folhasCalculadas.handle
            ) AS liquido
        ,folhasCalculadas.tipoCalculo
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 347
                AND folhafuncionario = folhasCalculadas.handle
            ) AS depositoFgts
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3943
                AND folhafuncionario = folhasCalculadas.handle
            ) AS baseCalcFgts
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3350
                AND folhafuncionario = folhasCalculadas.handle
            ) AS margemConsignavelBruta
        ,(
            SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 42103
                AND folhafuncionario = folhasCalculadas.handle
            ) AS margemConsignavelLiquida
        ,(SELECT valor
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 414
                AND folhafuncionario = folhasCalculadas.handle
            ) AS baseCalcInss
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 340
                AND folhafuncionario = folhasCalculadas.handle
            ) AS baseCalcIrrf
        ,(
            SELECT ROUND(valor / horas)
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 348
                AND folhafuncionario = folhasCalculadas.handle
            ) AS numDependentes
            ,(
		    SELECT salariohora
		    FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
		    WHERE verba = 3318
			AND folhafuncionario = folhasCalculadas.handle
		    ) AS cargahoraria
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 42371
                AND folhafuncionario = folhasCalculadas.handle
            ) AS folgasTre
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3442
                AND folhafuncionario = folhasCalculadas.handle
            ) AS abonosNovos
        ,(
            SELECT horas
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS
            WHERE verba = 3432
                AND folhafuncionario = folhasCalculadas.handle
            ) AS abonosVelhos FROM {$constanteBanco}.DO_FUNCIONARIOS funcionario INNER JOIN {$constanteBanco}.FP_FUNCIONARIOFOLHASCALCULADAS folhasCalculadas ON funcionario.handle = folhasCalculadas.FUNCIONARIO INNER JOIN {$constanteBanco}.FP_COMPETENCIAS competencia ON folhasCalculadas.competencia = competencia.handle INNER JOIN {$constanteBanco}.z_flagsitens tiposFolha ON tiposFolha.handle = folhasCalculadas.TIPOFOLHA WHERE (
            SELECT COUNT(folhafuncionario)
            FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS folhaVerba
            INNER JOIN {$constanteBanco}.FP_VERBAS verba ON folhaVerba.VERBA = verba.HANDLE
                AND verba.TIPOVERBA IN (
                    1
                    ,2
                    )
            WHERE folhafuncionario = folhasCalculadas.handle
            ) > 0
        AND folhasCalculadas.SITUACAO = 2
        AND funcionario.CPFNUMERO = '$numero_cpf' and EXTRACT(YEAR FROM competencia.competencia) = $ano_referencia and EXTRACT(MONTH FROM competencia.competencia) = $mes_referencia";

       $retorno = DB::connection('oracleBenner')->select($sql);
       return $retorno;

    }

    public function consultaDescricaoContrache($idContraCheque)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $retorno = null;
        $sql = "    SELECT folhaVerba.FOLHAFUNCIONARIO AS idContracheque,
        verba.HANDLE AS idVerba,
        verba.codigo AS codigoVerba,
        verba.TIPOVERBA,
        verba.nome,
        folhaVerba.valor,
        folhaVerba.horas AS referencia
        FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS folhaVerba
        INNER JOIN {$constanteBanco}.FP_VERBAS verba ON folhaVerba.VERBA = verba.HANDLE AND verba.TIPOVERBA IN (1, 2)
        WHERE folhaVerba.FOLHAFUNCIONARIO = $idContraCheque order by verba.codigo";
        $retorno = DB::connection('oracleBenner')->select($sql);
        return $retorno;
    }



    public function consultaDescricaoContracheRendimentos($idContraCheque)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $retorno = null;
        $sql = "    SELECT
        verba.codigo AS codigo,
        verba.nome AS descricao,
        folhaVerba.valor
        FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS folhaVerba
        INNER JOIN {$constanteBanco}.FP_VERBAS verba ON folhaVerba.VERBA = verba.HANDLE AND verba.TIPOVERBA IN (1, 2)
        WHERE folhaVerba.FOLHAFUNCIONARIO = $idContraCheque and verba.TIPOVERBA = 1 order by verba.codigo";
        $retorno = DB::connection('oracleBenner')->select($sql);
        return $retorno;
    }

    public function consultaDescricaoContracheDescontos($idContraCheque)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $retorno = null;
        $sql = "    SELECT
        verba.codigo AS codigo,
        verba.nome AS descricao,
        folhaVerba.valor
        FROM {$constanteBanco}.FP_FUNCIONARIOFOLHAVERBAS folhaVerba
        INNER JOIN {$constanteBanco}.FP_VERBAS verba ON folhaVerba.VERBA = verba.HANDLE AND verba.TIPOVERBA IN (1, 2)
        WHERE folhaVerba.FOLHAFUNCIONARIO = $idContraCheque and verba.TIPOVERBA = 2 order by verba.codigo";
        $retorno = DB::connection('oracleBenner')->select($sql);
        return $retorno;
    }



    public function consultaInformacoesPessoais($numero_cpf)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $retorno = null;
        $sql = "          SELECT
        funcionario.MATRICULA          AS matricula,
        funcionario.K9_MATRICULADIGITO AS matriculaDigito,
        funcionario.CPFNUMERO          AS cpf,
        funcionario.NOME               AS nome,
        tipoColab.nome                 AS regimeJuridico,
        classes.NOME                   AS classe,
        cargo.TITULO                   AS cargo,
        agencia.CODIGOAGENCIA          AS agencia,
        RPAD(LPAD(REPLACE(REPLACE(CONTACORRENTE,'-',''),' ',''),7,'0'),6)
        || '-'
        || SUBSTR(LPAD(REPLACE(REPLACE(CONTACORRENTE,'-',''),' ',''),7,'0'),7,1)
                                 AS contaCorrente,
        funcionario.DATAADMISSAO AS dataAdmissao,
        (padrao.step)            AS padrao,
        funcionario.SITUACAO cd_situacao_funcionario,
        s.nome ds_situacao_funcionario
      FROM
      {$constanteBanco}.DO_FUNCIONARIOS funcionario
      INNER JOIN {$constanteBanco}.FP_FUNCIONARIOPAGAMENTO pagamento
      ON
        funcionario.handle = pagamento.funcionario
      INNER JOIN {$constanteBanco}.CS_CARGOS cargo
      ON
        cargo.handle = funcionario.cargo
      INNER JOIN {$constanteBanco}.CS_Classes classes
      ON
        classes.handle = cargo.classe
      INNER JOIN {$constanteBanco}.ta_bancoagencias agencia
      ON
        agencia.handle = pagamento.agencia
      INNER JOIN {$constanteBanco}.K9_DO_TIPOCOLABORADORES tipoColab
      ON
        tipoColab.handle = funcionario.k9_tipofaixacolaborador
      INNER JOIN {$constanteBanco}.cs_classesteps Padrao
      ON
        padrao.handle = funcionario.step
      INNER JOIN {$constanteBanco}.ta_situacoes s
      ON s.HANDLE = funcionario.situacao where funcionario.CPFNUMERO = '$numero_cpf' ";

      $retorno = DB::connection('oracleBenner')->select($sql);
      return $retorno;

    }


    public function consultaInformacoesLotacaoFuncao($numero_cpf)
    {
        $constanteBanco = $this->retorna_constante_ambiente();
        $sql = " SELECT TRUNC(SYSDATE) AS MES, ";
        $sql .= "        SYSDATE AS DATA_ATUALIZACAO, ";
        $sql .= "        BRB.CPF, ";
        $sql .= "        BRB.EMAIL, ";
        $sql .= "        BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO GESTOR, ";
        $sql .= "        BRB1.NOME NOME_GESTOR, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN (UPPER(I.TITULO) LIKE ('%CONSELHEIRO%') OR ";
        $sql .= "               UPPER(I.TITULO) LIKE ('%MEMBRO%')) THEN ";
        $sql .= "           'Conselheiros' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 1 THEN ";
        $sql .= "           'Empregados' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 2 THEN ";
        $sql .= "           'Estatutários' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 3 THEN ";
        $sql .= "           'Estagiários' ";
        $sql .= "        END AS TIPO_DE_COLABORADOR,        ";
        $sql .= "        C.MATRICULA || C.K9_MATRICULADIGITO AS MATRICULA, ";
        $sql .= "        C.NOME, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN K.K9_ATIVIDADEGRATIFICADA = 'S' THEN ";
        $sql .= "           'Atividade Gratificada' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 2 THEN ";
        $sql .= "           'Estatuários' ";
        $sql .= "          WHEN K.K9_NATUREZA = 1 THEN ";
        $sql .= "           'Função técnica' ";
        $sql .= "          WHEN K.K9_NATUREZA = 2 THEN ";
        $sql .= "           'Função gerencial' ";
        $sql .= "          WHEN (UPPER(K.NOME) LIKE ('%CONSULTOR%') OR ";
        $sql .= "               UPPER(K.NOME) LIKE ('%CORREGEDOR%')) THEN ";
        $sql .= "           'Cargo em comissão' ";
        $sql .= "          ELSE ";
        $sql .= "           'Cargo' ";
        $sql .= "        END AS TIPO_DE_FUNCAO, ";
        $sql .= "        I.HANDLE CD_CARGO, ";
        $sql .= "        I.TITULO AS DS_CARGO, ";
        $sql .= "        K.HANDLE CD_FUNCAO, ";
        $sql .= "        K.NOME AS DS_FUNCAO, ";
        $sql .= "        COALESCE(K.NOME, I.TITULO) CARGO_FUNCAO_ATIVIDADE, ";
        $sql .= "        K.K9_NIVEL AS NIVEL_DA_FUNCAO, ";
        $sql .= "        M.HANDLE ID_SUBSTITUICAO, ";
        $sql .= "        M.NOME AS SUBSTITUICAO, ";
        $sql .= "        M.K9_NIVEL AS NIVEL_DA_SUBSTITUICAO, ";
        $sql .= "        EE.NOME AS UNIDADE_PRIMARIA, ";
        $sql .= "        E.APELIDO AS HIERARQUIA_PRIMARIA, ";
        $sql .= "        E.K9_AOD AS HIERARQUIA_PRIMARIA_AOD, ";
        $sql .= "        GG.NOME AS UNIDADE_TEMPORARIA, ";
        $sql .= "        G.APELIDO AS ADICAO_TEMPORARIA, ";
        $sql .= "        COALESCE(GG.NOME, EE.NOME) UNIDADE_LOTACAO, ";
        $sql .= "        COALESCE(G.APELIDO, E.APELIDO) LOTACAO, ";
        $sql .= "        COALESCE(G.K9_AOD, E.K9_AOD) AOD, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN (COALESCE(G.K_TIPOHIERARQUIA, E.K_TIPOHIERARQUIA)) = 4 THEN ";
        $sql .= "           COALESCE(G.APELIDO, E.APELIDO) ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "           N1.APELIDO ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "           N2.APELIDO ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "           N3.APELIDO ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "           N4.APELIDO ";
        $sql .= "        END AS GERENCIA, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "           N1.APELIDO ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "           N2.APELIDO ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "           N3.APELIDO ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "           N4.APELIDO ";
        $sql .= "        END AS SUPERINTENDENCIA, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           N1.APELIDO ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           N2.APELIDO ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           N3.APELIDO ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           N4.APELIDO ";
        $sql .= "        END AS DIRETORIA, ";
        $sql .= "        NN.NOME AS AFASTAMENTO, ";
        $sql .= "        BB.NOME AS ORGAO_CESSIONARIO, ";
        $sql .= "        C.DATAADMISSAO, ";
        $sql .= "        TO_CHAR(C.DATAADMISSAO, 'DD/MM/YYYY') DATA_ADMISSAO_FORMAT, ";
        $sql .= "        TRUNC((MONTHS_BETWEEN(SYSDATE, C.DATAADMISSAO))/12) TEMPO_BANCO, ";
        $sql .= "        C.HANDLE, ";
        $sql .= "        C.DATANASCIMENTO, ";
        $sql .= "        TO_CHAR(C.DATANASCIMENTO, 'DD/MM/YYYY') DATA_NASCIMENTO_FORMAT, ";
        $sql .= "        TRUNC((MONTHS_BETWEEN(SYSDATE, C.DATANASCIMENTO))/12) IDADE, ";
        $sql .= "        C.SEXO, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN C.DEFICIENCIA = 1 THEN ";
        $sql .= "           'Sim' ";
        $sql .= "          WHEN C.DEFICIENCIA = 2 THEN ";
        $sql .= "           'Não' ";
        $sql .= "        END AS DEFICIENCIA ";
        $sql .= "   FROM {$constanteBanco}.DO_FUNCIONARIOS C ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOHIERARQUIAS D ";
        $sql .= "     ON C.HANDLE = D.FUNCIONARIO ";
        $sql .= "    AND D.INICIO <= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) ";
        $sql .= "    AND (D.FIM >= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) OR D.FIM IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS E ";
        $sql .= "     ON D.HIERARQUIA = E.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_UNIDADES EE ";
        $sql .= "     ON E.UNIDADE = EE.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOCESSOES F ";
        $sql .= "     ON C.HANDLE = F.FUNCIONARIO ";
        $sql .= "    AND F.CESSAOINICIO <= TRUNC(SYSDATE) ";
        $sql .= "    AND (F.CESSAOFIM >= TRUNC(SYSDATE) OR F.CESSAOFIM IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS G ";
        $sql .= "     ON F.HIERARQUIAINTERNA = G.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_UNIDADES GG ";
        $sql .= "     ON G.UNIDADE = GG.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOCARGOS H ";
        $sql .= "     ON C.HANDLE = H.FUNCIONARIO ";
        $sql .= "    AND H.INICIO <= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) ";
        $sql .= "    AND (H.FIM >= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) OR H.FIM IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.CS_CARGOS I ";
        $sql .= "     ON H.CARGO = I.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOFUNCOES J ";
        $sql .= "     ON C.HANDLE = J.FUNCIONARIO ";
        $sql .= "    AND J.INICIO <= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) ";
        $sql .= "    AND (J.FIM >= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) OR J.FIM IS NULL) ";
        $sql .= "    AND (J.TIPOFUNCAO = 1 OR J.TIPOFUNCAO IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.CS_FUNCOES K ";
        $sql .= "     ON J.FUNCAO = K.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOFUNCOES L ";
        $sql .= "     ON C.HANDLE = L.FUNCIONARIO ";
        $sql .= "    AND L.INICIO <= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) ";
        $sql .= "    AND (L.FIM >= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) OR L.FIM IS NULL) ";
        $sql .= "    AND (L.TIPOFUNCAO = 2 OR L.TIPOFUNCAO IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.CS_FUNCOES M ";
        $sql .= "     ON L.FUNCAO = M.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOAFASTAMENTOS N ";
        $sql .= "     ON C.HANDLE = N.FUNCIONARIO ";
        $sql .= "    AND N.AFASTAMENTO <= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) ";
        $sql .= "    AND ((N.RETORNO - 1) >= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) OR N.RETORNO IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.FP_MOTIVOSAFASTAMENTOS NN ";
        $sql .= "     ON N.MOTIVO = NN.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.K9_DO_FUNCIONARIOCESSOES B ";
        $sql .= "     ON C.HANDLE = B.FUNCIONARIO ";
        $sql .= "    AND B.DATAVIGENCIA <= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) ";
        $sql .= "    AND (B.DATARETORNO >= (CASE ";
        $sql .= "          WHEN C.DEMISSAODATA <= TRUNC(SYSDATE) THEN ";
        $sql .= "           C.DEMISSAODATA ";
        $sql .= "          ELSE ";
        $sql .= "           TRUNC(SYSDATE) ";
        $sql .= "        END) OR B.DATARETORNO IS NULL) ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_OUTRASEMPRESAS BB ";
        $sql .= "     ON B.ORGAOCESSIONARIO = BB.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1 ";
        $sql .= "     ON (CASE ";
        $sql .= "          WHEN G.NIVELSUPERIOR IS NULL THEN ";
        $sql .= "           E.NIVELSUPERIOR ";
        $sql .= "          ELSE ";
        $sql .= "           G.NIVELSUPERIOR ";
        $sql .= "        END) = N1.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2 ";
        $sql .= "     ON N1.NIVELSUPERIOR = N2.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3 ";
        $sql .= "     ON N2.NIVELSUPERIOR = N3.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4 ";
        $sql .= "     ON N3.NIVELSUPERIOR = N4.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N5 ";
        $sql .= "     ON N4.NIVELSUPERIOR = N5.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.K9_BRB_COLABORADORES BRB ";
        $sql .= "     ON BRB.MATRICULA = C.MATRICULA ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOS BRB1 ";
        $sql .= "     ON BRB1.HANDLE = BRB.SUPERVISOR ";
        $sql .= "  WHERE C.TIPOCOLABORADOR IN (1, 2, 3) ";
        $sql .= "    AND I.CODIGO NOT IN (1000000) ";
        $sql .= "    AND (C.DEMISSAODATA >= TRUNC(SYSDATE) OR C.DEMISSAODATA IS NULL) ";
        $sql .= "    AND C.DATAADMISSAO <= TRUNC(SYSDATE) ";


        if($numero_cpf != null)
        {
            $sql .= " AND UPPER(BRB.CPF) like UPPER('%" .$numero_cpf. "%') ";

        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;
    }


    public function retorna_constante_ambiente()
    {
      $constanteBanco = null;
      $app_env = env('APP_ENV');
      if ($app_env == 'local' || $app_env == 'dsv') {
         $constanteBanco = 'rhdesenvolvimento';
      }elseif($app_env == 'hmo' || $app_env == 'HMO'){
         $constanteBanco = 'rh_homologacao';
      }else{
          $constanteBanco = 'rhproducao';
      }

      return $constanteBanco;
    }




}
