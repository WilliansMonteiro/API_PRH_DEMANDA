<?php

namespace App\Entities\Benner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;

class Funcionario extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;

    public $incrementing = false;
    public $timestamps = false;

    /**
     * Funcionario constructor.
     */
    public function __construct()
    {
        $this->table = session('DB_BENNER_DATABASE') . '.do_funcionarios';

    }


    public function consultaFuncionario($nrMatricula, $handle)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $ferias = "SELECT *
        FROM
              (SELECT A.DIASCREDITO, A.HANDLE FROM {$constanteBanco}.FP_FUNCIONARIOFERIASADQUIRIDAS A
              where a.funcionario = {$handle} ORDER BY A.HANDLE DESC) B
              left JOIN

               (SELECT sum(DIASFERIAS + DIASABONO1 + DIASABONO2) as somadias,
                     A.PERIODOADQUIRIDO
                FROM {$constanteBanco}.FP_FUNCIONARIOFERIASGOZADAS A
               WHERE A.FUNCIONARIO = {$handle}
               group by a.periodoadquirido ORDER BY A.HANDLE DESC ) a ON B.HANDLE = A.PERIODOADQUIRIDO ";


        $abono = "SELECT sum(a.diassaldo) as saldo_abonos, c.nome as tipo_abono , b.DATAADMISSAO AS tempoBanco   from {$constanteBanco}.k9_do_funcionarioabonos a
        inner join {$constanteBanco}.do_funcionarios b on b.handle = a.funcionario
        inner join {$constanteBanco}.k9_do_tiposabonos c on c.handle = a.tipoabono
        where a.prazoconcessao is not NULL AND b.MATRICULA || b.K9_MATRICULADIGITO = {$nrMatricula}
        group by b.matricula, c.nome , b.DATAADMISSAO, b.K9_MATRICULADIGITO";


        $ferias = DB::connection('oracleBenner')->select($ferias);
        $abono = DB::connection('oracleBenner')->select($abono);
        return ["abono" => $abono, "ferias" => $ferias];
    }


    public function consultaDadosFuncionario($nrMatricula = null, $nrMatriculaGestor = null, $cdDependenciaEmpresaRH = null, $isSuperintendente = false, $isDiretor = false)
    {
        //$constanteBanco = $this->retorna_constante_ambiente();
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = " SELECT BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO GESTOR, ";
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

        if ($nrMatricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = " . $nrMatricula;
        }

        if ($nrMatriculaGestor != null) {
            $sql .= "    AND BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO = " . $nrMatriculaGestor;
        }

        if ($cdDependenciaEmpresaRH != null) {
            $sql .= "    AND COALESCE(G.K9_AOD, E.K9_AOD) = lpad(" . $cdDependenciaEmpresaRH . ",6,0)";

        }

        if ($isSuperintendente) {
            $sql .= "  AND (K.HANDLE  = 954 OR M.HANDLE = 954)";
        }

        if ($isDiretor) {
            $sql .= "  and I.HANDLE = 242 ";
        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    public function consultarPeriodoFuncionarioBRB($nrMatricula = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $select = "SELECT MATRICULA,
                       NOME,
                       MONTHS_BETWEEN(TO_DATE(SYSDATE, 'DD-MM-YYYY'),
                                      TO_DATE(DATAADMISSAO, 'DD-MM-YYYY')) Months
                  FROM {$constanteBanco}.DO_FUNCIONARIOS
                 WHERE SITUACAO NOT IN (1, 5) ";
        if ($nrMatricula != null) {
            $select .= "    AND MATRICULA || K9_MATRICULADIGITO = " . $nrMatricula;
        }

        $results = DB::connection('oracleBenner')->select($select);

        return $results;
    }

    public function consultarCertificacaoQualificacao($nrMatricula , $categoria)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');

        $sql =  " SELECT A.MATRICULA, ";
        $sql .= " A.NOME, ";
        $sql .= " E.HANDLE AS QUALIFICACAO_HANDLE, ";
        $sql .= " E.CATEGORIA AS CATEGORIA ";
        $sql .= " FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOAFASTAMENTOS H ON A.HANDLE = H.FUNCIONARIO AND H.AFASTAMENTO <= SYSDATE AND (H.RETORNO > SYSDATE OR H.RETORNO IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOHIERARQUIAS I ON A.HANDLE = I.FUNCIONARIO AND I.INICIO <= SYSDATE AND (I.FIM >= SYSDATE OR I.FIM IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOQUALIFICACOES D ON D.FUNCIONARIO = A.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.TA_QUALIFICACOES E ON D.QUALIFICACAO = E.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOFUNCOES K ON A.HANDLE = K.FUNCIONARIO AND K.INICIO <= SYSDATE AND (K.FIM >= SYSDATE OR K.FIM IS NULL) AND(K.TIPOFUNCAO = 1 OR K.TIPOFUNCAO IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_FUNCOES M ON K.FUNCAO = M.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOCESSOES Q ON A.HANDLE = Q.FUNCIONARIO AND Q.CESSAOINICIO <= SYSDATE AND (Q.CESSAOFIM >= SYSDATE OR Q.CESSAOFIM IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS R ON Q.HIERARQUIAINTERNA = R.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOCARGOS S ON A.HANDLE = S.FUNCIONARIO AND S.INICIO <= SYSDATE AND (S.FIM >= SYSDATE OR S.FIM IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_CARGOS T ON S.CARGO = T.HANDLE ";
        $sql .= " WHERE A.TIPOCOLABORADOR = 1 AND A.SITUACAO NOT IN (1) ";
        $sql .= " AND(A.DEMISSAODATA > SYSDATE OR A.DEMISSAODATA IS NULL) ";
        $sql .= " AND A.DATAADMISSAO <= SYSDATE ";
        $sql .= " AND(H.MOTIVO <> 8 OR H.MOTIVO IS NULL) ";
        $sql .= " AND M.NOME IS NOT NULL ";
        $sql .= " AND D.K_DATAVENCIMENTO > SYSDATE ";
        $sql .= " AND a.matricula || a.k9_matriculadigito = $nrMatricula";
        //$sql .= " AND A.MATRICULA = {$nrMatricula} ";
        $sql .= " AND E.CATEGORIA = {$categoria} ";
        $sql .= " ORDER BY M.NOME, A.NOME ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;
    }

    public function consultarPeriodoFuncaoExercida($nrMatricula = null, $funcao = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $select = "SELECT C.MATRICULA,
                   C.NOME,
                   CASE
                     WHEN A.TIPOFUNCAO = 1 THEN
                      'Função/Atividade'
                     ELSE
                      'Substituição'
                   END AS TIPO,
                   MONTHS_BETWEEN(CASE
                                    WHEN A.FIM IS NOT NULL THEN
                                     TO_DATE(A.FIM, 'DD-MM-YYYY')
                                    ELSE
                                     TO_DATE(SYSDATE, 'DD-MM-YYYY')
                                  END,
                                  TO_DATE(A.INICIO, 'DD-MM-YYYY')) TOTAL_MESES,
                   A.INICIO,
                   A.FIM,
                   B.NOME AS CARGO_FUNCAO_ATIVIDADE,
                   B.HANDLE,
                   B.K9_CODIGO AS CODIGO,
                   B.K9_NIVEL AS NIVEL,
                   D.NOME AS NATUREZA
              FROM {$constanteBanco}.DO_FUNCIONARIOFUNCOES A
              LEFT JOIN {$constanteBanco}.CS_FUNCOES B
                ON A.FUNCAO = B.HANDLE
              LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOS C
                ON A.FUNCIONARIO = C.HANDLE
              LEFT JOIN {$constanteBanco}.K9_CS_NATUREZA D
                ON B.K9_NATUREZA = D.HANDLE
                WHERE 1 = 1
                ";
        if ($nrMatricula != null) {
            $select .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = " . $nrMatricula;
        }
        if ($funcao != null) {
            $select .= " AND B . HANDLE IN({$funcao})";
        }

        $results = DB::connection('oracleBenner')->select($select);

        return $results;
    }

    public function retornaFuncaoGrauTrilha($nrMatricula)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');

        $sql  = " SELECT A.MATRICULA,";
        $sql .= " A.NOME, ";
        $sql .= " D.TITULO AS CARGO, ";
        $sql .= " C.NOME AS FUNCAO_ATIVIDADE, ";
        $sql .= " C.HANDLE AS HANDLE_FUNCAO, ";
        $sql .= " EE.APELIDO AS HIERARQUIA, ";
        $sql .= " R.APELIDO AS ADICAO_TEMPORARIA, ";
        $sql .= " G.NOME AS FORMACAO, ";
        $sql .= " H.NOME AS GRAU, ";
        $sql .= " H.HANDLE AS GRAU_HANDLE, ";
        $sql .= " S.CODIGO AS CODIGO_TRILHA_FUNCAO, ";
        $sql .= " T.CODIGO  AS CODIGO_TRILHA_CARGO, ";
        $sql .= " T.NOME AS NOME_TRILHA_CARGO ";
        $sql .= " FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOFORMACOES F ON A.HANDLE = F.FUNCIONARIO ";
        $sql .= " LEFT JOIN {$constanteBanco}.TA_FORMACOES G ON F.CURSO = G.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.TA_GRAUSENSINO H ON G.GRAU = H.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOFUNCOES B ON A.HANDLE = B.FUNCIONARIO AND B.FIM IS NULL ";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_FUNCOES C ON B.FUNCAO = C.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_CARGOS D ON A.CARGO = D.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOHIERARQUIAS E ON A.HANDLE = E.FUNCIONARIO AND E.INICIO <= SYSDATE AND (E.FIM >= SYSDATE OR E.FIM IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS EE ON E.HIERARQUIA = EE.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOCESSOES Q ON A.HANDLE = Q.FUNCIONARIO AND Q.CESSAOINICIO <= SYSDATE AND (Q.CESSAOFIM >= SYSDATE OR Q.CESSAOFIM IS NULL) ";
        $sql .= " LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS R ON Q.HIERARQUIAINTERNA = R.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.K9_CS_TRILHAS S ON C.K9_TRILHA  = S.HANDLE ";
        $sql .= " LEFT JOIN {$constanteBanco}.K9_CS_CARREIRAS T ON T.HANDLE = D.K9_CARREIRA ";
        $sql .= " WHERE A.SITUACAO NOT IN(5, 1) ";
        $sql .= " AND A.TIPOCOLABORADOR = 1 AND A.SITUACAO NOT IN (1)";
        $sql .= " AND (F.COMPLETO = 'S')";
        $sql .= " AND a.matricula || a.k9_matriculadigito = {$nrMatricula}";
        //$sql .= " AND A.MATRICULA = {$nrMatricula}";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;
    }


    public function consultarHistoricoPorFuncao($nr_matricula, $listaFuncoes = [])
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = " SELECT C.MATRICULA, ";
        $sql .= "        C.NOME, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN A.TIPOFUNCAO = 1 THEN ";
        $sql .= "           'Função/Atividade' ";
        $sql .= "          ELSE ";
        $sql .= "           'Substituição' ";
        $sql .= "        END AS TIPO, ";
        $sql .= "        TO_CHAR(A.INICIO, 'DD/MM/YYYY') DT_INICIO, ";
        $sql .= "        TO_CHAR(A.FIM, 'DD/MM/YYYY') DT_FIM, ";
        $sql .= "        TRUNC((MONTHS_BETWEEN(COALESCE(A.fim, SYSDATE), A.inicio))) TEMPO_LOTACAO, ";
        $sql .= "        B.NOME AS CARGO_FUNCAO_ATIVIDADE, ";
        $sql .= "        B.HANDLE, ";
        $sql .= "        B.K9_CODIGO AS CODIGO, ";
        $sql .= "        B.K9_NIVEL AS NIVEL, ";
        $sql .= "        D.NOME AS NATUREZA ";
        $sql .= "   FROM {$constanteBanco}.DO_FUNCIONARIOFUNCOES A ";
        $sql .= "   LEFT JOIN {$constanteBanco}.CS_FUNCOES B ";
        $sql .= "     ON A.FUNCAO = B.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOS C ";
        $sql .= "     ON A.FUNCIONARIO = C.HANDLE ";
        $sql .= "   LEFT JOIN {$constanteBanco}.K9_CS_NATUREZA D ";
        $sql .= "     ON B.K9_NATUREZA = D.HANDLE ";
        $sql .= "  WHERE 1 = 1 ";

        if ($nr_matricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = " . $nr_matricula;
        }

        if(count($listaFuncoes)){
            $sql .= "    AND b.handle  in "."(".implode(",",$listaFuncoes).")";;
        }

        $sql .= "  ORDER BY A.TIPOFUNCAO ASC, A.INICIO DESC,A.FIM DESC ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }


    public function consultarHistoricoPorLotacao($nr_matricula)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = "SELECT  MATRICULA || K9_MATRICULADIGITO MATRICULA, ";
        $sql .= "  EMPREGADO,  ";
        $sql .= "  TO_CHAR(INICIO, 'DD/MM/YYYY') DT_INICIO,  ";
        $sql .= "  TO_CHAR(FIM, 'DD/MM/YYYY') DT_FIM,  ";
        $sql .= "  TRUNC((MONTHS_BETWEEN(COALESCE(FIM, SYSDATE), INICIO))/12) TEMPO_LOTACAO, ";
        $sql .= "  LOTACAO || '/' || GERENCIA || '/' || SUPERINTENDENCIA || '/' || DIRETORIA LOTACAO,  ";
        $sql .= "  TIPO ";
        $sql .= "  FROM (SELECT A.MATRICULA, ";
        $sql .= "               A.K9_MATRICULADIGITO, ";
        $sql .= "               A.NOME AS EMPREGADO, ";
        $sql .= "               'Normal' AS TIPO, ";
        $sql .= "               B.INICIO, ";
        $sql .= "               B.FIM, ";
        $sql .= "               ((CASE ";
        $sql .= "                 WHEN B.FIM IS NULL THEN ";
        $sql .= "                  TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY') ";
        $sql .= "                 ELSE ";
        $sql .= "                  B.FIM ";
        $sql .= "               END) - B.INICIO + 1) AS DIAS, ";
        $sql .= "               C.K9_AOD AS AOD, ";
        $sql .= "               C.APELIDO AS LOTACAO, ";
        $sql .= "               C.NOME, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS GERENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS SUPERINTENDENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS DIRETORIA, ";
        $sql .= "               NE.NOME AS EMPRESA ";
        $sql .= "          FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= "         INNER JOIN {$constanteBanco}.DO_FUNCIONARIOHIERARQUIAS B ";
        $sql .= "            ON A.HANDLE = B.FUNCIONARIO ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS C ";
        $sql .= "            ON B.HIERARQUIA = C.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1 ";
        $sql .= "            ON C.NIVELSUPERIOR = N1.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2 ";
        $sql .= "            ON N1.NIVELSUPERIOR = N2.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3 ";
        $sql .= "            ON N2.NIVELSUPERIOR = N3.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4 ";
        $sql .= "            ON N3.NIVELSUPERIOR = N4.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.K_TIPOSEMPRESAS NE ";
        $sql .= "            ON C.K_TIPOEMPRESA = NE.HANDLE ";
        $sql .= "        UNION ";
        $sql .= "        SELECT A.MATRICULA, ";
        $sql .= "               A.K9_MATRICULADIGITO, ";
        $sql .= "               A.NOME AS EMPREGADO, ";
        $sql .= "               'Adição' AS TIPO, ";
        $sql .= "               B.CESSAOINICIO, ";
        $sql .= "               B.CESSAOFIM, ";
        $sql .= "               ((CASE ";
        $sql .= "                 WHEN B.CESSAOFIM IS NULL THEN ";
        $sql .= "                  TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY') ";
        $sql .= "                 ELSE ";
        $sql .= "                  B.CESSAOFIM ";
        $sql .= "               END) - B.CESSAOINICIO + 1) AS DIAS, ";
        $sql .= "               C.K9_AOD AS AOD, ";
        $sql .= "               C.APELIDO AS LOTACAO, ";
        $sql .= "               C.NOME, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS GERENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS SUPERINTENDENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS DIRETORIA, ";
        $sql .= "               NE.NOME AS EMPRESA ";
        $sql .= "          FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= "         INNER JOIN {$constanteBanco}.DO_FUNCIONARIOCESSOES B ";
        $sql .= "            ON A.HANDLE = B.FUNCIONARIO ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS C ";
        $sql .= "            ON B.HIERARQUIAINTERNA = C.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1 ";
        $sql .= "            ON C.NIVELSUPERIOR = N1.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2 ";
        $sql .= "            ON N1.NIVELSUPERIOR = N2.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3 ";
        $sql .= "            ON N2.NIVELSUPERIOR = N3.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4 ";
        $sql .= "            ON N3.NIVELSUPERIOR = N4.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.K_TIPOSEMPRESAS NE ";
        $sql .= "            ON C.K_TIPOEMPRESA = NE.HANDLE) A ";

        if ($nr_matricula != null) {
             $sql .= " WHERE A.MATRICULA || a.k9_matriculadigito = " . $nr_matricula;
        }

        $sql .= " ORDER BY 1, 3 DESC, 4 DESC ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    public function consultarTempoUltimaLotacao($nr_matricula)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = "SELECT  MATRICULA || K9_MATRICULADIGITO MATRICULA, ";
        $sql .= "  EMPREGADO,  ";
        $sql .= "  TO_CHAR(INICIO, 'DD/MM/YYYY') DT_INICIO,  ";
        $sql .= "  TO_CHAR(FIM, 'DD/MM/YYYY') DT_FIM, ";
        $sql .= " trunc(mod(months_between(trunc(sysdate), INICIO), 12)) meses, ";
        $sql .= " trunc(months_between(trunc(sysdate), INICIO) / 12) anos, ";
        $sql .= "  LOTACAO || '/' || GERENCIA || '/' || SUPERINTENDENCIA || '/' || DIRETORIA LOTACAO,  ";
        $sql .= "  TIPO ";
        $sql .= "  FROM (SELECT A.MATRICULA, ";
        $sql .= "               A.K9_MATRICULADIGITO, ";
        $sql .= "               A.NOME AS EMPREGADO, ";
        $sql .= "               'Normal' AS TIPO, ";
        $sql .= "               B.INICIO, ";
        $sql .= "               B.FIM, ";
        $sql .= "               ((CASE ";
        $sql .= "                 WHEN B.FIM IS NULL THEN ";
        $sql .= "                  TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY') ";
        $sql .= "                 ELSE ";
        $sql .= "                  B.FIM ";
        $sql .= "               END) - B.INICIO + 1) AS DIAS, ";
        $sql .= "               C.K9_AOD AS AOD, ";
        $sql .= "               C.APELIDO AS LOTACAO, ";
        $sql .= "               C.NOME, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS GERENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS SUPERINTENDENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS DIRETORIA, ";
        $sql .= "               NE.NOME AS EMPRESA ";
        $sql .= "          FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= "         INNER JOIN {$constanteBanco}.DO_FUNCIONARIOHIERARQUIAS B ";
        $sql .= "            ON A.HANDLE = B.FUNCIONARIO ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS C ";
        $sql .= "            ON B.HIERARQUIA = C.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1 ";
        $sql .= "            ON C.NIVELSUPERIOR = N1.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2 ";
        $sql .= "            ON N1.NIVELSUPERIOR = N2.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3 ";
        $sql .= "            ON N2.NIVELSUPERIOR = N3.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4 ";
        $sql .= "            ON N3.NIVELSUPERIOR = N4.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.K_TIPOSEMPRESAS NE ";
        $sql .= "            ON C.K_TIPOEMPRESA = NE.HANDLE";
        $sql .= "        UNION ";
        $sql .= "        SELECT A.MATRICULA, ";
        $sql .= "               A.K9_MATRICULADIGITO, ";
        $sql .= "               A.NOME AS EMPREGADO, ";
        $sql .= "               'Adição' AS TIPO, ";
        $sql .= "               B.CESSAOINICIO, ";
        $sql .= "               B.CESSAOFIM, ";
        $sql .= "               ((CASE ";
        $sql .= "                 WHEN B.CESSAOFIM IS NULL THEN ";
        $sql .= "                  TO_DATE(TO_CHAR(SYSDATE, 'DD/MM/YYYY'), 'DD/MM/YYYY') ";
        $sql .= "                 ELSE ";
        $sql .= "                  B.CESSAOFIM ";
        $sql .= "               END) - B.CESSAOINICIO + 1) AS DIAS, ";
        $sql .= "               C.K9_AOD AS AOD, ";
        $sql .= "               C.APELIDO AS LOTACAO, ";
        $sql .= "               C.NOME, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS GERENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS SUPERINTENDENCIA, ";
        $sql .= "               CASE ";
        $sql .= "                 WHEN C.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  C.APELIDO ";
        $sql .= "                 WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N1.APELIDO ";
        $sql .= "                 WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N2.APELIDO ";
        $sql .= "                 WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N3.APELIDO ";
        $sql .= "                 WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "                  N4.APELIDO ";
        $sql .= "               END AS DIRETORIA, ";
        $sql .= "               NE.NOME AS EMPRESA ";
        $sql .= "          FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= "         INNER JOIN {$constanteBanco}.DO_FUNCIONARIOCESSOES B ";
        $sql .= "            ON A.HANDLE = B.FUNCIONARIO ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS C ";
        $sql .= "            ON B.HIERARQUIAINTERNA = C.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1 ";
        $sql .= "            ON C.NIVELSUPERIOR = N1.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2 ";
        $sql .= "            ON N1.NIVELSUPERIOR = N2.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3 ";
        $sql .= "            ON N2.NIVELSUPERIOR = N3.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4 ";
        $sql .= "            ON N3.NIVELSUPERIOR = N4.HANDLE ";
        $sql .= "          LEFT JOIN {$constanteBanco}.K_TIPOSEMPRESAS NE ";
        $sql .= "            ON C.K_TIPOEMPRESA = NE.HANDLE) A ";
        //$sql .= "            WHERE B.FIM IS NULL";

        if ($nr_matricula != null) {
             $sql .= " WHERE A.MATRICULA || a.k9_matriculadigito = " . $nr_matricula;
        }

        $sql .= " ORDER BY 4 DESC ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    /**
     * Consulta que retorna os dados para a API ConsultaFuncionarios
     * Não alterar essa consulta, salvo quando for necessário alteração na API
     */
    public function retornaDadosFuncionarioApi($nrMatricula = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = " SELECT CASE ";
        $sql .= "          WHEN (UPPER(I.TITULO) LIKE ('%CONSELHEIRO%') OR ";
        $sql .= "               UPPER(I.TITULO) LIKE ('%MEMBRO%')) THEN ";
        $sql .= "           'Conselheiros' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 1 THEN ";
        $sql .= "           'Empregados' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 2 THEN ";
        $sql .= "           'Estatutários' ";
        $sql .= "        END AS TIPO_DE_COLABORADOR, ";
        $sql .= "        C.MATRICULA || C.K9_MATRICULADIGITO AS NR_MATRICULA, ";
        $sql .= "        C.NOME AS NO_USUARIO, ";
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
        $sql .= "        I.HANDLE CD_CARGO_BENNER, ";
        $sql .= "        I.TITULO AS DS_CARGO_BENNER, ";
        $sql .= "        NVL(M.HANDLE, K.HANDLE) CD_FUNCAO_BENNER, ";
        $sql .= "        NVL(M.NOME, K.NOME) DS_FUNCAO_BENNER, ";
        $sql .= "        K.K9_NIVEL AS NIVEL_DA_FUNCAO, ";
        $sql .= "        BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO MATRICULA_GESTOR, ";
        $sql .= "        BRB1.NOME NOME_GESTOR, ";
        $sql .= "        COALESCE(GG.NOME, EE.NOME) UNIDADE_AREA_BENNER, ";
        $sql .= "        COALESCE(G.APELIDO, E.APELIDO) DS_AREA_BENNER, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN (SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD),0,1) = 0) THEN ";
        $sql .= "               SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 2,7) ";
        $sql .= "          ELSE ";
        $sql .= "               SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 1,7) ";
        $sql .= "        END AS CD_AREA_BENNER, ";
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
        $sql .= "          WHEN (COALESCE(G.K_TIPOHIERARQUIA, E.K_TIPOHIERARQUIA)) = 4 THEN ";
        $sql .= "            SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "          END AS CD_AREA_BENNER_GEREN, ";
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
        $sql .= "       CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "         END AS CD_AREA_BENNER_SUPER, ";
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
        $sql .= "       CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "      END AS CD_AREA_BENNER_DIRETORIA ";
        $sql .= "       FROM {$constanteBanco}.DO_FUNCIONARIOS C ";
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
        $sql .= "  WHERE C.TIPOCOLABORADOR IN (1, 2) ";
        $sql .= "    AND I.CODIGO NOT IN (1000000) ";
        $sql .= "    AND (C.DEMISSAODATA >= TRUNC(SYSDATE) OR C.DEMISSAODATA IS NULL) ";
        $sql .= "    AND C.DATAADMISSAO <= TRUNC(SYSDATE) ";
        $sql .= "    AND (N.MOTIVO IS NULL OR N.MOTIVO != 326) ";

        if ($nrMatricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = "  . $nrMatricula;
        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }


    public function retornaDadosAfastamentosFuncionario($nrMatricula = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = " SELECT ";
        $sql .= "     C.MATRICULA || C.K9_MATRICULADIGITO AS NR_MATRICULA, ";
        $sql .= "     C.NOME AS NO_USUARIO, ";
        $sql .= "     N.MOTIVO as CD_AFASTAMENTO, ";
        $sql .= "     NN.NOME AS CD_DESCRICAO_AFASTAMENTO, ";
        $sql .= "      N.SITUACAOFUNCIONARIO AS SITUACAO_FUNCIONARIO, ";
        $sql .= "      N.AFASTAMENTO AS DT_AFASTAMENTO, ";
        $sql .= "      N.INICIOAFASTAMENTOEMPRESA AS DT_INICIO_AFAST, ";
        $sql .= "      N.FIMAFASTAMENTOEMPRESA AS DT_FIM_AFAST, ";
        $sql .= "       N.DIASTOTAL AS DIAS_TOTAL, ";
        $sql .= "      NN.K9_TIPOLICENCA AS TIPO_LICENCA, ";
        $sql .= "      B.TIPOCESSAO AS TIPO_CESSAO, ";
        $sql .= "      B.ORGAOCESSIONARIO AS ORGAO_CESSAO, ";
        $sql .= "      B.DATAVIGENCIA AS DT_VIGENCIA, ";
        $sql .= "      B.DATARETORNO AS DT_RETORNO_CESSAO, ";
        $sql .= "     CASE ";
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
        $sql .= "        I.HANDLE CD_CARGO_BENNER, ";
        $sql .= "        I.TITULO AS DS_CARGO_BENNER, ";
        $sql .= "        K.HANDLE CD_FUNCAO_BENNER, ";
        $sql .= "        K.NOME AS DS_FUNCAO_BENNER, ";
        $sql .= "        COALESCE(G.APELIDO, E.APELIDO) DS_AREA_BENNER, ";
        $sql .= "        SUBSTR( COALESCE(G.K9_AOD, E.K9_AOD), 2, 7 ) CD_AREA_BENNER, ";
       /* $sql .= "        K.K9_NIVEL AS NIVEL_DA_FUNCAO, ";
        $sql .= "        BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO MATRICULA_GESTOR, ";
        $sql .= "        COALESCE(GG.NOME, EE.NOME) UNIDADE_AREA_BENNER, ";
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
        $sql .= "          WHEN (COALESCE(G.K_TIPOHIERARQUIA, E.K_TIPOHIERARQUIA)) = 4 THEN ";
        $sql .= "            SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "          END AS CD_AREA_BENNER_GEREN, ";
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
        $sql .= "       CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "         END AS CD_AREA_BENNER_SUPER, ";
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
        $sql .= "       CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "      END AS CD_AREA_BENNER_DIRETORIA, ";*/
        $sql .= "      C.DEMISSAODATA AS DT_DEMISSAO ";
        $sql .= "       FROM {$constanteBanco}.DO_FUNCIONARIOS C ";
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
        $sql .= "  WHERE C.TIPOCOLABORADOR IN (1, 2) ";
        $sql .= "    AND I.CODIGO NOT IN (1000000) ";
        $sql .= "    AND (C.DEMISSAODATA >= TRUNC(SYSDATE) OR C.DEMISSAODATA IS NULL) ";
        $sql .= "    AND C.DATAADMISSAO <= TRUNC(SYSDATE) ";

        if ($nrMatricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = "  . $nrMatricula;
        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    public function consultaDadosFuncionarioADdBennerCpf($nr_cpf = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
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
        // $sql .= "        C.NOME, ";
        $sql .= "        CASE WHEN C.NOMESOCIAL IS NULL THEN C.NOME ELSE C.NOMESOCIAL END AS NOME, ";
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


        if($nr_cpf != null)
        {
            $sql .= " AND UPPER(BRB.CPF) like UPPER('%" .$nr_cpf. "%') ";

        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    public function consultaDadosFuncionarioADdBennerMatricula($nrMatricula = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
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
        // $sql .= "        C.NOME, ";
        $sql .= "        CASE WHEN C.NOMESOCIAL IS NULL THEN C.NOME ELSE C.NOMESOCIAL END AS NOME, ";
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


        if ($nrMatricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = " . $nrMatricula;
        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    public function consultaExperienciaExterna($nrMatricula = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');

        $sql = "SELECT B.MATRICULA,";
        $sql .= " B.NOME,";
        $sql .= " A.INICIO,";
        $sql .= " A.FIM";
        $sql .= " FROM {$constanteBanco}.K_DO_FUNCIONARIOEXPEXTERNA A";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOS B ON A.FUNCIONARIO = B.HANDLE";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_FUNCOES C ON A.FUNCAOEQUIVALENTE = C.HANDLE";
        $sql .= " WHERE A.SITUACAO = 1";
        //$sql .= " AND B.MATRICULA = $nrMatricula";
        $sql .= "   AND B.matricula || B.k9_matriculadigito = $nrMatricula ";

        return DB::connection('oracleBenner')->select($sql);
    }

    public function consultaExperienciaBRB($nrMatricula)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = "WITH";
        $sql .= " CALENDARIO AS (";
		$sql .= "   SELECT * FROM (";
        $sql .= "       SELECT ROWNUM + TO_DATE('31/12/1959', 'DD/MM/YYYY')  DIA ";
        $sql .= "        FROM {$constanteBanco}.DO_FUNCIONARIOFUNCOES";
        $sql .= "           WHERE ROWNUM < (SELECT TO_DATE(SYSDATE) - TO_DATE('31/12/1959', 'DD/MM/YYYY') + 1 FROM DUAL)";
		$sql .= "   )";
        $sql .= " ),";
        $sql .= " TB_CARGO AS (";
		$sql .= "   SELECT A.*, B.*";
		$sql .= "    FROM CALENDARIO A";
		$sql .= "       LEFT JOIN (";
		$sql .= "        SELECT A.HANDLE FUNCIONARIO, ";
        $sql .= "           A.MATRICULA, ";
        $sql .= "           A.NOME, ";
        $sql .= "           A.DATAADMISSAO ADMISSAO, ";
        $sql .= "           A.DATAADMISSAO INICIO, ";
        $sql .= "           CASE WHEN A.DEMISSAODATA IS NULL THEN TO_DATE(SYSDATE) ELSE A.DEMISSAODATA END FIM_T, ";
        $sql .= "           A.TIPOCOLABORADOR, ";
        $sql .= "           B.CODIGO, ";
        $sql .= "           B.TITULO CARGO, ";
        $sql .= "           C.NOME CLASSE ";
        $sql .= "        FROM {$constanteBanco}.DO_FUNCIONARIOS A ";
        $sql .= "           INNER JOIN {$constanteBanco}.CS_CARGOS B ON B.HANDLE = A.CARGO ";
		$sql .= "           LEFT JOIN {$constanteBanco}.CS_CLASSES C ON C.HANDLE = B.CLASSE ";
        $sql .= "        WHERE MATRICULA = $nrMatricula";
		$sql .= "       ) B ON A.DIA BETWEEN B.INICIO AND B.FIM_T";
        $sql .= "  ),";

	    $sql .= " TB_FUNCAO AS (";
		$sql .= "   SELECT A.*, B.*";
		$sql .= "    FROM CALENDARIO A";
		$sql .= " LEFT JOIN (";
        $sql .= "  SELECT A.MATRICULA, D.NOME FUNCAO, D.K9_CODIGO CODIGO, F.NOME NIVEL, G.NOME CLASSE, C.INICIO, C.FIM, A.HANDLE FUNCIONARIO,";
        $sql .= " CASE";
        $sql .= " WHEN FIM IS NULL AND DEMISSAODATA IS NOT NULL THEN DEMISSAODATA";
        $sql .= " WHEN FIM = LEAD( INICIO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO)";
        $sql .= " AND D.K9_CODIGO = LEAD( K9_CODIGO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) THEN FIM - 1";
        $sql .= " WHEN LEAD( INICIO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) BETWEEN INICIO AND NVL(FIM, TO_DATE(SYSDATE))";
        $sql .= " AND K9_CODIGO = LEAD( K9_CODIGO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) ";
        $sql .= " THEN LEAD( INICIO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) - 1";
        $sql .= " WHEN FIM IS NULL THEN TO_DATE(SYSDATE) ";
        $sql .= " ELSE FIM END FIM_T";
        $sql .= " FROM {$constanteBanco}.DO_FUNCIONARIOS A";
        $sql .= " INNER JOIN {$constanteBanco}.CS_CARGOS B ON B.HANDLE = A.CARGO";
        $sql .= " INNER JOIN {$constanteBanco}.DO_FUNCIONARIOFUNCOES C ON C.FUNCIONARIO = A.HANDLE";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_FUNCOES D ON D.HANDLE = C.FUNCAO";
        $sql .= " LEFT JOIN {$constanteBanco}.K9_CS_NATUREZA E ON E.HANDLE = D.K9_NATUREZA";
        $sql .= " LEFT JOIN {$constanteBanco}.K9_CS_NIVEIS F ON F.HANDLE = D.K9_NIVEL";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_CLASSES G ON G.HANDLE = D.K9_CLASSE";
        $sql .= " WHERE TIPOCOLABORADOR IN (1)";
        $sql .= " AND TIPOFUNCAO = 1";
        $sql .= " AND MATRICULA = $nrMatricula";
		$sql .= " ) B ON A.DIA BETWEEN B.INICIO AND B.FIM_T";
        $sql .= " ),";

        $sql .= " TB_SUBSTITUICAO AS (";
		$sql .= " SELECT A.*, B.*";
		$sql .= " FROM CALENDARIO A";
		$sql .= " LEFT JOIN (";
        $sql .= " SELECT A.MATRICULA, D.NOME SUBSTITUICAO, D.K9_CODIGO CODIGO, F.NOME NIVEL, G.NOME CLASSE, C.INICIO, C.FIM, A.HANDLE FUNCIONARIO,";
        $sql .= " CASE";
        $sql .= " WHEN FIM IS NULL AND DEMISSAODATA IS NOT NULL THEN DEMISSAODATA";
        $sql .= " WHEN FIM = LEAD( INICIO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO)";
        $sql .= " AND D.K9_CODIGO = LEAD( K9_CODIGO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) THEN FIM - 1";
        $sql .= " WHEN LEAD( INICIO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) BETWEEN INICIO AND NVL(FIM, TO_DATE(SYSDATE))";
        $sql .= " AND K9_CODIGO = LEAD( K9_CODIGO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) THEN LEAD( INICIO, 1) OVER (PARTITION BY A.HANDLE ORDER BY A.HANDLE, INICIO) - 1";
        $sql .= " WHEN FIM IS NULL THEN TO_DATE(SYSDATE) ";
        $sql .= " ELSE FIM END FIM_T";
        $sql .= " FROM {$constanteBanco}.DO_FUNCIONARIOS A";
        $sql .= " INNER JOIN {$constanteBanco}.CS_CARGOS B ON B.HANDLE = A.CARGO";
        $sql .= " INNER JOIN {$constanteBanco}.DO_FUNCIONARIOFUNCOES C ON C.FUNCIONARIO = A.HANDLE";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_FUNCOES D ON D.HANDLE = C.FUNCAO";
        $sql .= " LEFT JOIN {$constanteBanco}.K9_CS_NATUREZA E ON E.HANDLE = D.K9_NATUREZA";
        $sql .= " LEFT JOIN {$constanteBanco}.K9_CS_NIVEIS F ON F.HANDLE = D.K9_NIVEL";
        $sql .= " LEFT JOIN {$constanteBanco}.CS_CLASSES G ON G.HANDLE = D.K9_CLASSE";
        $sql .= " WHERE TIPOCOLABORADOR IN (1)";
        $sql .= " AND TIPOFUNCAO = 2";
        $sql .= " AND MATRICULA = $nrMatricula";
		$sql .= "  ) B ON A.DIA BETWEEN B.INICIO AND B.FIM_T";
        $sql .= " ),";

        $sql .= " SELECT MATRICULA, INICIO, FIM, (FIM - INICIO) + 1 DIAS, CARGO_FUNCAO, NIVEL, CLASSE,";
        $sql .= " CASE WHEN FIM - LEAD( INICIO, 1) OVER (PARTITION BY MATRICULA ORDER BY MATRICULA, INICIO) + 1 <> 0 THEN 'X' END AJUSTE";
        $sql .= " FROM (";
        $sql .= " SELECT DISTINCT MATRICULA, MIN(DIA) INICIO, MAX(DIA) FIM, CARGO_FUNCAO, NIVEL, CLASSE, Q_CARGO_FUNCAO FROM (";
		$sql .= " SELECT B.DIA, B.MATRICULA, B.NOME, NVL(NVL(SUBSTITUICAO, FUNCAO), CARGO) CARGO_FUNCAO,";
        $sql .= " NVL(D.NIVEL, C.NIVEL) NIVEL";
        $sql .= " NVL(NVL(D.CLASSE, C.CLASSE), B.CLASSE) CLASSE,";
        $sql .= " A.DIA - ROW_NUMBER() OVER(";
        $sql .= " PARTITION BY B.MATRICULA, NVL(NVL(SUBSTITUICAO, FUNCAO), CARGO)";
        $sql .= " ORDER BY B.MATRICULA, A.DIA";
        $sql .= " ) Q_CARGO_FUNCAO";
		$sql .= " FROM CALENDARIO A";
        $sql .= " INNER JOIN {$constanteBanco}.TB_CARGO B ON B.DIA = A.DIA";
        $sql .= " LEFT JOIN {$constanteBanco}.TB_FUNCAO C ON C.FUNCIONARIO = B.FUNCIONARIO AND C.DIA = B.DIA";
        $sql .= " LEFT JOIN {$constanteBanco}.TB_SUBSTITUICAO D ON D.FUNCIONARIO = B.FUNCIONARIO AND D.DIA = B.DIA";
		$sql .= " ORDER BY B.MATRICULA, A.DIA";
        $sql .= " ) GROUP BY MATRICULA, CARGO_FUNCAO, Q_CARGO_FUNCAO, NIVEL, CLASSE";

        $sql .= " ORDER BY MATRICULA, MIN(DIA)";
        $sql .= " )";

        return DB::connection('oracleBenner')->select($sql);
    }

    public function consultaEscolaridadeFuncionario($nrMatricula)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        // $sql = "SELECT a.matricula, a.nome, c.nome as curso, d.nome as area_conhecimento, e.handle as cd_grau_ensino, e.nome as grau_ensino, e.nivel as nivel_escolaridade, f.nome";
        $sql = "SELECT a.matricula, a.nome, c.nome as curso, d.nome as area_conhecimento, e.handle as cd_grau_ensino, e.nome as grau_ensino, a.nivelescolaridade, f.nome";
        $sql .= " FROM {$constanteBanco}.DO_FUNCIONARIOS A";
        $sql .= " LEFT JOIN {$constanteBanco}.DO_FUNCIONARIOFORMACOES B ON B.FUNCIONARIO = A.HANDLE";
        $sql .= " LEFT JOIN {$constanteBanco}.TA_FORMACOES C ON C.HANDLE = B.CURSO";
        $sql .= " LEFT JOIN {$constanteBanco}.TA_AREASCONHECIMENTO D ON D.HANDLE = C.AREA";
        $sql .= " LEFT JOIN {$constanteBanco}.TA_GRAUsENSINO E ON E.HANDLE = c.GRAU";
        $sql .= " left join {$constanteBanco}.ta_niveisescolaridade f on f.handle = a.nivelescolaridade";
        $sql .= " WHERE b.completo = 'S'";
        //$sql .= " and a.matricula = $nrMatricula";
        $sql .= " and a.matricula || a.k9_matriculadigito = $nrMatricula ";
        $sql .= " ORDER BY e.nivel DESC";
        return DB::connection('oracleBenner')->select($sql);
    }


    public function consultaDadosFuncionarioRotina($nrMatricula = null, $nrMatriculaGestor = null, $cdDependenciaEmpresaRH = null, $isSuperintendente = false, $isDiretor = false)
    {
        //$constanteBanco = $this->retorna_constante_ambiente();
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = " SELECT TRUNC(SYSDATE) AS MES, ";
        $sql .= "        SYSDATE AS DATA_ATUALIZACAO, ";
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

        if ($nrMatricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = " . $nrMatricula;
        }

        if ($nrMatriculaGestor != null) {
            $sql .= "    AND BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO = " . $nrMatriculaGestor;
        }

        if ($cdDependenciaEmpresaRH != null) {
            $sql .= "    AND COALESCE(G.K9_AOD, E.K9_AOD) = lpad(" . $cdDependenciaEmpresaRH . ",6,0)";

        }

        if ($isSuperintendente) {
            $sql .= "  AND (K.HANDLE  = 954 OR M.HANDLE = 954)";
        }

        if ($isDiretor) {
            $sql .= "  and I.HANDLE = 242 ";
        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    public function retornaDadosFuncionarioApiMgc($nrMatricula = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = " SELECT I.codigo, CASE ";
        $sql .= "          WHEN (UPPER(I.TITULO) LIKE ('%CONSELHEIRO%') OR ";
        $sql .= "               UPPER(I.TITULO) LIKE ('%MEMBRO%')) THEN ";
        $sql .= "           'Conselheiros' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 1 THEN ";
        $sql .= "           'Empregados' ";
        $sql .= "          WHEN C.TIPOCOLABORADOR = 2 THEN ";
        $sql .= "           'Estatutários' ";
        $sql .= "        END AS TIPO_DE_COLABORADOR, ";
        $sql .= "        C.MATRICULA || C.K9_MATRICULADIGITO AS NR_MATRICULA, ";
        $sql .= "        C.NOME AS NO_USUARIO, ";
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
        $sql .= "        I.HANDLE CD_CARGO_BENNER, ";
        $sql .= "        I.TITULO AS DS_CARGO_BENNER, ";
        $sql .= "        CASE ";
        $sql .= "           WHEN NVL(M.HANDLE, K.HANDLE) = 956 THEN ";
        $sql .= "               954 ";
        $sql .= "           ELSE ";
        $sql .= "               NVL(M.HANDLE, K.HANDLE) ";
        $sql .= "        END AS CD_FUNCAO_BENNER, ";
        $sql .= "        CASE ";
        $sql .= "           WHEN NVL(M.HANDLE, K.HANDLE) = 956 THEN ";
        $sql .= "               'Superintendente' ";
        $sql .= "           ELSE ";
        $sql .= "               NVL(M.NOME, K.NOME) ";
        $sql .= "           END AS DS_FUNCAO_BENNER, ";
        $sql .= "        K.K9_NIVEL AS NIVEL_DA_FUNCAO, ";
        $sql .= "        BRB1.MATRICULA || BRB1.K9_MATRICULADIGITO MATRICULA_GESTOR, ";
        $sql .= "        BRB1.NOME NOME_GESTOR, ";
        $sql .= "        COALESCE(GG.NOME, EE.NOME) UNIDADE_AREA_BENNER, ";
        $sql .= "        COALESCE(G.APELIDO, E.APELIDO) DS_AREA_BENNER, ";
        $sql .= "        CASE ";
        $sql .= "          WHEN (SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD),0,1) = 0) THEN ";
        $sql .= "               SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 2,7) ";
        $sql .= "          ELSE ";
        $sql .= "               SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 1,7) ";
        $sql .= "        END AS CD_AREA_BENNER, ";
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
        $sql .= "          WHEN (COALESCE(G.K_TIPOHIERARQUIA, E.K_TIPOHIERARQUIA)) = 4 THEN ";
        $sql .= "            SUBSTR(COALESCE(G.K9_AOD, E.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 4 THEN ";
        $sql .= "             SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "          END AS CD_AREA_BENNER_GEREN, ";
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
        $sql .= "       CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N2.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N3.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "          WHEN N4.K_TIPOHIERARQUIA = 5 THEN ";
        $sql .= "            SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "         END AS CD_AREA_BENNER_SUPER, ";
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
        $sql .= "       CASE ";
        $sql .= "          WHEN N1.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N1.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N2.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N2.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N3.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N3.K9_AOD), 2, 7) ";
        $sql .= "         WHEN N4.K_TIPOHIERARQUIA = 6 THEN ";
        $sql .= "           SUBSTR(TO_CHAR(N4.K9_AOD), 2, 7) ";
        $sql .= "      END AS CD_AREA_BENNER_DIRETORIA ";
        $sql .= "       FROM {$constanteBanco}.DO_FUNCIONARIOS C ";
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
        $sql .= "  WHERE C.TIPOCOLABORADOR IN (1, 2) ";
        $sql .= "    AND I.CODIGO NOT IN (1000000) ";
        $sql .= "    AND (C.DEMISSAODATA >= TRUNC(SYSDATE) OR C.DEMISSAODATA IS NULL) ";
        $sql .= "    AND C.DATAADMISSAO <= TRUNC(SYSDATE) ";
        $sql .= "    AND (N.MOTIVO IS NULL OR N.MOTIVO != 326) ";

        if ($nrMatricula != null) {
            $sql .= "    AND C.MATRICULA || C.K9_MATRICULADIGITO = "  . $nrMatricula;
        }

        $sql .= "  ORDER BY C.NOME, C.MATRICULA ";

        $results = DB::connection('oracleBenner')->select($sql);

        return $results;

    }

    function consultarNomeSocialPorLote($nrMatriculas){
        $results = [];
        $constanteBanco = session('DB_BENNER_DATABASE');

        foreach(array_chunk($nrMatriculas, 1000) as $chunk){
            $matriculas = implode(",", $chunk);

            $sql = "SELECT NOMESOCIAL, MATRICULA, K9_MATRICULADIGITO";
            $sql .= " FROM {$constanteBanco}.DO_FUNCIONARIOS";
            $sql .= " WHERE MATRICULA || K9_MATRICULADIGITO IN ("  . $matriculas.")";
            
            $result = DB::connection('oracleBenner')->select($sql);

            foreach($result as $nome){
                if($nome->nomesocial != null){
                    $results[$nome->matricula.$nome->k9_matriculadigito] = $nome->nomesocial;
                }
            }

        }

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
