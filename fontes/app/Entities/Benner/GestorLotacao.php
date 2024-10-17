<?php

namespace App\Entities\Benner;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GestorLotacao extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;


    public static function getGestorLotacao($cd_empresa_dependencia)
    {
        $codigo = strval($cd_empresa_dependencia);
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT DISTINCT
        NVL(CC1.DIRETORIA,C1.DIRETORIA) DIRETORIA
       ,NVL(CC1.SUPERINTENDENCIA,C1.SUPERINTENDENCIA) SUPERINTENDENCIA
       ,NVL(CC1.HIERARQUIA,C1.HIERARQUIA) LOTACAO
       ,NVL(CC1.AOD,C1.AOD) AOD
       ,NVL(NVL(D1.NOME,B1.NOME),A1.TITULO) CARGO_FUNCAO
       ,CASE WHEN D1.NOME IS NOT NULL THEN 'Substituição'
             WHEN B1.NOME IS NOT NULL THEN 'Efetivo'
             ELSE 'CARGO 'END TIPO_GESTOR
       ,NVL(DD.MATRICULA,A.MATRICULA) MATRICULA
       FROM $constanteBanco.DO_FUNCIONARIOS A
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOFUNCOES B ON B.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN B.INICIO AND NVL(B.FIM,SYSDATE) AND B.TIPOFUNCAO = 1
       LEFT JOIN $constanteBanco.CS_FUNCOES B1 ON B1.HANDLE = B.FUNCAO
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOHIERARQUIAS C ON C.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN C.INICIO AND NVL(C.FIM,SYSDATE)
       LEFT JOIN $constanteBanco.K_BRB_HIERARQUIAS C1 ON C1.IDHIERARQUIA = C.HIERARQUIA
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOCESSOES CC ON CC.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN CC.CESSAOINICIO AND NVL(CC.CESSAOFIM,SYSDATE)
       LEFT JOIN $constanteBanco.K_BRB_HIERARQUIAS CC1 ON CC1.IDHIERARQUIA = CC.HIERARQUIAINTERNA
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOFUNCOES D ON D.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN D.INICIO AND NVL(D.FIM,SYSDATE) AND D.TIPOFUNCAO = 2
       LEFT JOIN $constanteBanco.CS_FUNCOES D1 ON D1.HANDLE = D.FUNCAO
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOS DD ON DD.HANDLE = D.FUNCIONARIO
       LEFT JOIN $constanteBanco.CS_CARGOS A1 ON A1.HANDLE = A.CARGO
        WHERE 0=0
       AND (CASE
                WHEN UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'OUVID%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE DE Á%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE GER%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'SECRETÁRIO EXEC%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'CHEFE DE GAB%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE DE LOJ%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'SUPER%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE DE NÚ%'  THEN 'GESTOR'
                END
            ) = 'GESTOR'
       AND A.TIPOCOLABORADOR = 1
       AND A.SITUACAO NOT IN (1,5)
       AND TO_NUMBER(NVL(CC1.AOD,C1.AOD)) IN ($codigo) -- FILTRO AOD
       ORDER BY 3,4,1";
        return DB::connection('oracleBenner')->select($sql);

    }


    public function query_gestor_lotacao_api(Request $request)
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT DISTINCT
        NVL(CC1.DIRETORIA,C1.DIRETORIA) DIRETORIA
       ,NVL(CC1.SUPERINTENDENCIA,C1.SUPERINTENDENCIA) SUPERINTENDENCIA
       ,NVL(CC1.HIERARQUIA,C1.HIERARQUIA) LOTACAO
       ,NVL(CC1.AOD,C1.AOD) AOD
       ,NVL(NVL(D1.NOME,B1.NOME),A1.TITULO) CARGO_FUNCAO
       ,CASE WHEN D1.NOME IS NOT NULL THEN 'Substituição'
             WHEN B1.NOME IS NOT NULL THEN 'Efetivo'
             ELSE 'CARGO 'END TIPO_GESTOR
       ,'u'||NVL(DD.MATRICULA,A.MATRICULA) MATRICULA
       ,NVL(DD.NOME,A.NOME) NOME
       FROM $constanteBanco.DO_FUNCIONARIOS A
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOFUNCOES B ON B.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN B.INICIO AND NVL(B.FIM,SYSDATE) AND B.TIPOFUNCAO = 1
       LEFT JOIN $constanteBanco.CS_FUNCOES B1 ON B1.HANDLE = B.FUNCAO
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOHIERARQUIAS C ON C.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN C.INICIO AND NVL(C.FIM,SYSDATE)
       LEFT JOIN $constanteBanco.K_BRB_HIERARQUIAS C1 ON C1.IDHIERARQUIA = C.HIERARQUIA
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOCESSOES CC ON CC.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN CC.CESSAOINICIO AND NVL(CC.CESSAOFIM,SYSDATE)
       LEFT JOIN $constanteBanco.K_BRB_HIERARQUIAS CC1 ON CC1.IDHIERARQUIA = CC.HIERARQUIAINTERNA
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOFUNCOES D ON D.FUNCIONARIO = A.HANDLE AND SYSDATE BETWEEN D.INICIO AND NVL(D.FIM,SYSDATE) AND D.TIPOFUNCAO = 2
       LEFT JOIN $constanteBanco.CS_FUNCOES D1 ON D1.HANDLE = D.FUNCAO
       LEFT JOIN $constanteBanco.DO_FUNCIONARIOS DD ON DD.HANDLE = D.FUNCIONARIO
       LEFT JOIN $constanteBanco.CS_CARGOS A1 ON A1.HANDLE = A.CARGO
        WHERE 0=0
       AND (CASE
                WHEN UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'OUVID%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE DE Á%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE GER%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'SECRETÁRIO EXEC%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'CHEFE DE GAB%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE DE LOJ%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'SUPER%' OR
                     UPPER(NVL(NVL(D1.NOME,B1.NOME),A1.TITULO)) LIKE 'GERENTE DE NÚ%'  THEN 'GESTOR'
                END
            ) = 'GESTOR'
       AND A.TIPOCOLABORADOR = 1
       AND A.SITUACAO NOT IN (1,5) ";

       if($request->filled('cd_empresa_dependencia')){
         $sql.= "AND TO_NUMBER(NVL(CC1.AOD,C1.AOD)) IN ($request->cd_empresa_dependencia)";
       }

       if($request->filled('sg_dependencia')){
         $sql.="AND UPPER(NVL(CC1.HIERARQUIA,C1.HIERARQUIA)) like UPPER('%$request->sg_dependencia%')";
       }

    //    $sql.=" ORDER BY 3,4,1 ";

        return DB::connection('oracleBenner')->select($sql);
    }
}
