<?php

namespace App\Entities\Benner;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QualificacaoBenner extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    // Categorias
    const CERTIFICACAO = 2; // certificações
    const QUALIFICACAO = 3; // cursos, workshops
    const APROVACAO    = "APROVADO";
    const REJEICAO     = "REPROVADO";

    public function getCertificacoes()
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT  QA.handle,
                        QA.nome,
                        QA.categoria
                FROM {$constanteBanco}.TA_QUALIFICACOES QA WHERE QA.categoria = 2";

        return DB::connection('oracleBenner')->select($sql);
    }

    public function getQualificacoes()
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT  QA.handle,
                        QA.nome,
                        QA.categoria
                FROM {$constanteBanco}.TA_QUALIFICACOES QA WHERE QA.categoria = 3";

        return DB::connection('oracleBenner')->select($sql);
    }
}
