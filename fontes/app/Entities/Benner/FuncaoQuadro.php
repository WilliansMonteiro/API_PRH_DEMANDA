<?php

namespace App\Entities\Benner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FuncaoQuadro extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    public function consultaQuadroFuncoes($areaId,$nomeFuncao){
        
        $constanteBanco = session('DB_BENNER_DATABASE');

        $sql = "WITH TB_NIVEL_SUPERIOR AS (
            SELECT A.* FROM {$constanteBanco}.K_BRB_HIERARQUIAS A
    
        )
        , TB_QUADRO AS(
        
          SELECT C.HANDLE ID_HIERARQUIA
            , C.APELIDO HIERARQUIA
            , SUM(A.PLANO ) QA
            , SUM(A.TOTALQUADRO ) QE
            , SUM( A.VAGAS ) VAGAS
            , E.CODIGO ID_FUNCAO
            , E.NOME CARGO_FUNCAO
            , E2.NOME CLASSE
            , COALESCE( NVL(F.NOME, G.NOME), E2.NOME ) CARREIRA
            , CASE
                WHEN E.ORIGEM = 1 THEN 'Cargo'
                ELSE (CASE WHEN E1.K9_ATIVIDADEGRATIFICADA = 'S' THEN 'Atividade Gratificada' ELSE 'Função Gratificada' END)
            END AS ATIVIDADE_FUNCAO
            , NVL(NVL(E1.K9_VALORREFERENCIA, E1.VALOR), 1000) * 0.001 ORDEM_CARGO
        
        FROM {$constanteBanco}.QP_QUADROPESSOAL A
                LEFT JOIN {$constanteBanco}.QP_QUADROHIERARQUIAS B ON A.HIERARQUIA = B.HANDLE
                    LEFT JOIN {$constanteBanco}.ADM_HIERARQUIAS C ON B.HIERARQUIA = C.HANDLE
                LEFT JOIN {$constanteBanco}.QP_QUADROCARGOS D ON A.CARGO = D.HANDLE
                    LEFT JOIN {$constanteBanco}.CS_UNIFICACARGOSFUNCOES E ON D.CARGOFUNCAOUNIFICADO = E.HANDLE
                        LEFT JOIN {$constanteBanco}.CS_CLASSES E2 ON E.CLASSE = E2.HANDLE
                        -- Funções
                        LEFT JOIN {$constanteBanco}.CS_FUNCOES E1 ON E.FUNCAO = E1.HANDLE
                            LEFT JOIN {$constanteBanco}.K9_CS_TRILHAS F ON E1.K9_TRILHA = F.HANDLE
                        -- Cargos
                        LEFT JOIN {$constanteBanco}.CS_CARGOS E3 ON E.CARGO = E3.HANDLE
                            LEFT JOIN {$constanteBanco}.K9_CS_CARREIRAS G ON E3.K9_CARREIRA = G.HANDLE
        
        WHERE 1>0
            AND E.K9_ATIVO LIKE 'S'
            AND A.PLANO + A.TOTALQUADRO > 0
            AND C.ESTRUTURA LIKE '2020%'
            AND C.ATIVA = 'S'
        
        GROUP BY C.HANDLE, C.APELIDO, E.HANDLE, E.NOME, E.CODIGO, E2.NOME, NVL(F.NOME, G.NOME )
            , CASE WHEN E.ORIGEM = 1 THEN 'Cargo' ELSE (CASE WHEN E1.K9_ATIVIDADEGRATIFICADA = 'S' THEN 'Atividade Gratificada' ELSE 'Função Gratificada' END) END
            , NVL(NVL(E1.K9_VALORREFERENCIA, E1.VALOR), 1000)
        
        )
        
        SELECT B.IDHIERARQUIA ID_HIERARQUIA
            , B.HIERARQUIA
            , ID_FUNCAO
            , CARGO_FUNCAO
            , A.QA
            , A.QE
            , A.VAGAS
            
        FROM TB_QUADRO A
            INNER JOIN TB_NIVEL_SUPERIOR B ON A.ID_HIERARQUIA = B.IDHIERARQUIA
        
        WHERE 1 = 1
            AND AOD = '{$areaId}'";

        if($nomeFuncao){
            $sql .= "AND CARGO_FUNCAO LIKE '%{$nomeFuncao}%'";
        }


        return DB::connection('oracleBenner')->select($sql);
    }

}