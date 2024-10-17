<?php

namespace App\Entities\Benner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AreaHierarquica extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    /**
     * AreaHierarquica constructor.
     */
    public function __construct()
    {
        $this->table = session('DB_BENNER_DATABASE') . '.adm_hierarquias';
    }

    const CESEP = 13264;
    const DIPES = 13200;
    const SUAPE = 13240;
    const GEREG = 13247;
    const GETEP = 13263;
    const GEDEP = 13241;
    const GEREP = 13248;
    const SUEPE = 13262;
    const GEVID = 13249;

    const GECRM = 15236;
    const GEREI = 15203;
    const NUQUA = 15090;
    const NUPEC = 15112;
    const GEPOP = 15043;
    const NUADM = 15219;
    const GEJUR = 16029;

    const GEIMP = 12667;
    const GABIN = 11550;
    const COREG = 16063;
    const PRESI = 11540;


    public function getEmailArea($cdDependenciaRH){
        $constanteBanco = session('DB_BENNER_DATABASE');
        $select = "SELECT ah.K9_AOD , ah.APELIDO , ah.k9_email, ah.K_TIPOHIERARQUIA, kct.TIPO FROM
                                {$constanteBanco}.Adm_hierarquias  ah 
                                INNER JOIN {$constanteBanco}.K_CS_TIPOHIERARQUIA kct  ON ah.K_TIPOHIERARQUIA = kct.HANDLE
                                WHERE ah.K9_AOD = {$cdDependenciaRH} and ah.K9_ATIVO = 'S' AND ah.ATIVA  = 'S' AND ah.K9_EMAIL IS NOT NULL";

        $results = DB::connection('oracleBenner')->select($select);
        return $results;
    }

    public function getAreaHierarquia($cdDependenciaRH){
      
      $constanteBanco = session('DB_BENNER_DATABASE');

      $select = "SELECT * FROM (
        SELECT DISTINCT
            A.K9_AOD AOD
            , A.APELIDO SIGLA
            FROM {$constanteBanco}.ADM_HIERARQUIAS A
            LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS B ON B.HANDLE = A.NIVELSUPERIOR AND (B.ATIVA = 'S')
            WHERE B.K9_AOD IN ({$cdDependenciaRH}) 
            AND A.APELIDO NOT IN ('GELOT', 'EMCED', 'EMLIC', 'EMAFT', 'EMQEX', 'ONBRB')
            ) WHERE AOD NOT IN ({$cdDependenciaRH}) ";

      $results = DB::connection('oracleBenner')->select($select);
      return $results;

    }

    public function consultarVagaDisponivelArea($cdDependenciaRH = null, $funcao = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $select = "SELECT NE.NOME AS EMPRESA,       
                           CASE
                             WHEN C.K_TIPOHIERARQUIA = 6 THEN
                              C.APELIDO
                             WHEN N1.K_TIPOHIERARQUIA = 6 THEN
                              N1.APELIDO
                             WHEN N2.K_TIPOHIERARQUIA = 6 THEN
                              N2.APELIDO
                             WHEN N3.K_TIPOHIERARQUIA = 6 THEN
                              N3.APELIDO
                             WHEN N4.K_TIPOHIERARQUIA = 6 THEN
                              N4.APELIDO
                           END AS DIRETORIA,       
                           CASE
                             WHEN C.K_TIPOHIERARQUIA = 5 THEN
                              C.APELIDO
                             WHEN N1.K_TIPOHIERARQUIA = 5 THEN
                              N1.APELIDO
                             WHEN N2.K_TIPOHIERARQUIA = 5 THEN
                              N2.APELIDO
                             WHEN N3.K_TIPOHIERARQUIA = 5 THEN
                              N3.APELIDO
                             WHEN N4.K_TIPOHIERARQUIA = 5 THEN
                              N4.APELIDO
                           END AS SUPERINTENDENCIA,       
                           CASE
                             WHEN C.K_TIPOHIERARQUIA = 4 THEN
                              C.APELIDO
                             WHEN N1.K_TIPOHIERARQUIA = 4 THEN
                              N1.APELIDO
                             WHEN N2.K_TIPOHIERARQUIA = 4 THEN
                              N2.APELIDO
                             WHEN N3.K_TIPOHIERARQUIA = 4 THEN
                              N3.APELIDO
                             WHEN N4.K_TIPOHIERARQUIA = 4 THEN
                              N4.APELIDO
                           END AS GERENCIA,       
                           C.APELIDO AS HIERARQUIA,       
                           C.K9_AOD AS AOD,
                           C.ESTRUTURA,       
                           EEE.NOME AS CLASSE,       
                           CASE
                             WHEN E.ORIGEM = 1 THEN
                              'Cargo'
                             ELSE
                              (CASE
                                WHEN EE.K9_ATIVIDADEGRATIFICADA = 'S' THEN
                                 'Atividade Gratificada'
                                ELSE
                                 'Função Gratificada'
                              END)
                           END AS TIPO,
                           E2.NOME || EE1.NOME AS CARREIRA_TRILHA,
                           E.NOME AS CARGO_FUNCAO_ATIVIDADE,
                           E.CODIGO,
                           EE.HANDLE COD_FUNCAO,
                           SUM(A.PLANO) AS QUADRO_APROVADO,
                           SUM(A.TOTALQUADRO) AS QUADRO_EXISTENTE,
                           SUM(A.PLANO) - SUM(A.TOTALQUADRO) AS VAGAS
                      FROM {$constanteBanco}.QP_QUADROPESSOAL A
                      LEFT JOIN {$constanteBanco}.QP_QUADROHIERARQUIAS B
                        ON A.HIERARQUIA = B.HANDLE
                      LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS C
                        ON B.HIERARQUIA = C.HANDLE
                      LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1
                        ON C.NIVELSUPERIOR = N1.HANDLE
                      LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2
                        ON N1.NIVELSUPERIOR = N2.HANDLE
                      LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3
                        ON N2.NIVELSUPERIOR = N3.HANDLE
                      LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4
                        ON N3.NIVELSUPERIOR = N4.HANDLE
                      LEFT JOIN {$constanteBanco}.K_TIPOSEMPRESAS NE
                        ON C.K_TIPOEMPRESA = NE.HANDLE
                      LEFT JOIN {$constanteBanco}.QP_QUADROCARGOS D
                        ON A.CARGO = D.HANDLE
                      LEFT JOIN {$constanteBanco}.CS_UNIFICACARGOSFUNCOES E
                        ON D.CARGOFUNCAOUNIFICADO = E.HANDLE
                      LEFT JOIN {$constanteBanco}.CS_FUNCOES EE
                        ON E.FUNCAO = EE.HANDLE
                      LEFT JOIN {$constanteBanco}.K9_CS_TRILHAS EE1
                        ON EE.K9_TRILHA = EE1.HANDLE
                      LEFT JOIN {$constanteBanco}.CS_CLASSES EEE
                        ON E.CLASSE = EEE.HANDLE
                      LEFT JOIN {$constanteBanco}.CS_CARGOS E1
                        ON E.CARGO = E1.HANDLE
                      LEFT JOIN {$constanteBanco}.K9_CS_CARREIRAS E2
                        ON E1.K9_CARREIRA = E2.HANDLE
                     WHERE C.ATIVA = 'S'
                       AND (E.K9_ATIVO = 'S' OR E.CODIGO IN (1, 9104))
                       AND (C.ESTRUTURA LIKE ('2020%'))
                       AND C.ULTIMONIVEL = 'S'   
                       AND (A.PLANO + A.TOTALQUADRO > 0) ";

        if ($cdDependenciaRH != null) {
            $select .= "   AND C.K9_AOD = " . $cdDependenciaRH;
        }
        if ($funcao != null) {
            $select .= " AND EE.HANDLE IN({$funcao})";
        }

        $select .= " GROUP BY C.K9_AOD,
                              C.APELIDO,
                              E2.NOME || EE1.NOME,          
                              NE.NOME,
                              C.ESTRUTURA,          
                              CASE
                                WHEN C.K_TIPOHIERARQUIA = 6 THEN
                                 C.APELIDO
                                WHEN N1.K_TIPOHIERARQUIA = 6 THEN
                                 N1.APELIDO
                                WHEN N2.K_TIPOHIERARQUIA = 6 THEN
                                 N2.APELIDO
                                WHEN N3.K_TIPOHIERARQUIA = 6 THEN
                                 N3.APELIDO
                                WHEN N4.K_TIPOHIERARQUIA = 6 THEN
                                 N4.APELIDO
                              END,          
                              CASE
                                WHEN C.K_TIPOHIERARQUIA = 5 THEN
                                 C.APELIDO
                                WHEN N1.K_TIPOHIERARQUIA = 5 THEN
                                 N1.APELIDO
                                WHEN N2.K_TIPOHIERARQUIA = 5 THEN
                                 N2.APELIDO
                                WHEN N3.K_TIPOHIERARQUIA = 5 THEN
                                 N3.APELIDO
                                WHEN N4.K_TIPOHIERARQUIA = 5 THEN
                                 N4.APELIDO
                              END,          
                              CASE
                                WHEN C.K_TIPOHIERARQUIA = 4 THEN
                                 C.APELIDO
                                WHEN N1.K_TIPOHIERARQUIA = 4 THEN
                                 N1.APELIDO
                                WHEN N2.K_TIPOHIERARQUIA = 4 THEN
                                 N2.APELIDO
                                WHEN N3.K_TIPOHIERARQUIA = 4 THEN
                                 N3.APELIDO
                                WHEN N4.K_TIPOHIERARQUIA = 4 THEN
                                 N4.APELIDO
                              END,          
                              E.CODIGO,
                              EE.HANDLE,
                              E.NOME,          
                              EEE.NOME,          
                              CASE
                                WHEN E.ORIGEM = 1 THEN
                                 'Cargo'
                                ELSE
                                 (CASE
                                   WHEN EE.K9_ATIVIDADEGRATIFICADA = 'S' THEN
                                    'Atividade Gratificada'
                                   ELSE
                                    'Função Gratificada'
                                 END)
                              END
                     ORDER BY 1, 2, 3, 4, 5, 8";
        $results = DB::connection('oracleBenner')->select($select);
        return $results;
    }

    public function consultarHierarquiaPorArea($cdArea)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = "select distinct CASE
                         WHEN C.K_TIPOHIERARQUIA = 4 THEN
                          C.APELIDO
                         WHEN N1.K_TIPOHIERARQUIA = 4 THEN
                          N1.APELIDO
                         WHEN N2.K_TIPOHIERARQUIA = 4 THEN
                          N2.APELIDO
                         WHEN N3.K_TIPOHIERARQUIA = 4 THEN
                          N3.APELIDO
                         WHEN N4.K_TIPOHIERARQUIA = 4 THEN
                          N4.APELIDO
                       END AS SG_GERENCIA,
                       CASE
                         WHEN C.K_TIPOHIERARQUIA = 5 THEN
                          C.APELIDO
                         WHEN N1.K_TIPOHIERARQUIA = 5 THEN
                          N1.APELIDO
                         WHEN N2.K_TIPOHIERARQUIA = 5 THEN
                          N2.APELIDO
                         WHEN N3.K_TIPOHIERARQUIA = 5 THEN
                          N3.APELIDO
                         WHEN N4.K_TIPOHIERARQUIA = 5 THEN
                          N4.APELIDO
                       END AS SG_SUPERINTENDENCIA,
                       CASE
                         WHEN C.K_TIPOHIERARQUIA = 6 THEN
                          C.APELIDO
                         WHEN N1.K_TIPOHIERARQUIA = 6 THEN
                          N1.APELIDO
                         WHEN N2.K_TIPOHIERARQUIA = 6 THEN
                          N2.APELIDO
                         WHEN N3.K_TIPOHIERARQUIA = 6 THEN
                          N3.APELIDO
                         WHEN N4.K_TIPOHIERARQUIA = 6 THEN
                          N4.APELIDO
                       END AS SG_DIRETORIA,
                       CASE
                          WHEN C.K_TIPOHIERARQUIA = 4 THEN
                           replace(ltrim(replace(C.K9_AOD,'0',' ')),' ','0')
                          WHEN N1.K_TIPOHIERARQUIA = 4 THEN
                           replace(ltrim(replace(N1.K9_AOD,'0',' ')),' ','0')
                          WHEN N2.K_TIPOHIERARQUIA = 4 THEN
                           replace(ltrim(replace(N2.K9_AOD,'0',' ')),' ','0')
                          WHEN N3.K_TIPOHIERARQUIA = 4 THEN
                            replace(ltrim(replace(N3.K9_AOD,'0',' ')),' ','0')
                          WHEN N4.K_TIPOHIERARQUIA = 4 THEN
                            replace(ltrim(replace(N4.K9_AOD,'0',' ')),' ','0')
                        END AS CD_GERENCIA,
                        CASE
                          WHEN C.K_TIPOHIERARQUIA = 5 THEN
                           replace(ltrim(replace(C.K9_AOD,'0',' ')),' ','0')
                          WHEN N1.K_TIPOHIERARQUIA = 5 THEN
                           replace(ltrim(replace(N1.K9_AOD,'0',' ')),' ','0')
                          WHEN N2.K_TIPOHIERARQUIA = 5 THEN
                           replace(ltrim(replace(N2.K9_AOD,'0',' ')),' ','0')
                          WHEN N3.K_TIPOHIERARQUIA = 5 THEN
                            replace(ltrim(replace(N3.K9_AOD,'0',' ')),' ','0')
                          WHEN N4.K_TIPOHIERARQUIA = 5 THEN
                            replace(ltrim(replace(N4.K9_AOD,'0',' ')),' ','0')
                        END AS CD_SUPERINTENDENCIA,
                        CASE
                          WHEN C.K_TIPOHIERARQUIA = 6 THEN
                           replace(ltrim(replace(C.K9_AOD,'0',' ')),' ','0')
                          WHEN N1.K_TIPOHIERARQUIA = 6 THEN
                           replace(ltrim(replace(N1.K9_AOD,'0',' ')),' ','0')
                          WHEN N2.K_TIPOHIERARQUIA = 6 THEN
                           replace(ltrim(replace(N2.K9_AOD,'0',' ')),' ','0')
                          WHEN N3.K_TIPOHIERARQUIA = 6 THEN
                            replace(ltrim(replace(N3.K9_AOD,'0',' ')),' ','0')
                          WHEN N4.K_TIPOHIERARQUIA = 6 THEN
                            replace(ltrim(replace(N4.K9_AOD,'0',' ')),' ','0')
                        END AS CD_DIRETORIA
                  from {$constanteBanco}.ADM_HIERARQUIAS C
                  LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N1
                    ON C.NIVELSUPERIOR = N1.HANDLE
                  LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N2
                    ON N1.NIVELSUPERIOR = N2.HANDLE
                  LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N3
                    ON N2.NIVELSUPERIOR = N3.HANDLE
                  LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS N4
                    ON N3.NIVELSUPERIOR = N4.HANDLE
                  LEFT JOIN {$constanteBanco}.K_TIPOSEMPRESAS NE
                    ON C.K_TIPOEMPRESA = NE.HANDLE
                 WHERE C.ATIVA = 'S' ";

        if (isset($cdArea) && $cdArea != '') {
            $sql .= "   AND TO_NUMBER(replace(ltrim(replace(C.K9_AOD,'0',' ')),' ','0')) = {$cdArea} ";
        }

        $results = DB::connection('oracleBenner')->select($sql);
        return $results[0];
    }
}
