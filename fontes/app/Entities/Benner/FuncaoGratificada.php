<?php

namespace App\Entities\Benner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\FormularioAvaliacao\Entities\NaturezaAtividadeFuncao;

class FuncaoGratificada extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    const SUPERINTENDENTE = 954;

    /**
     * FuncaoGratificada constructor.
     */
    public function __construct()
    {
        $this->table = session('DB_BENNER_DATABASE').'.cs_funcoes';
    }

    public function funcaoByTrilha($trilha = null)
    {
        $constanteBanco = session('DB_BENNER_DATABASE');
        $sql = "SELECT  FC.handle,
                        FC.nome
                FROM {$constanteBanco}.CS_FUNCOES FC
                WHERE FC.k9_trilha = $trilha";

        return DB::connection('oracleBenner')->select($sql);
    }

    public function naturezaAtividadeFuncao()
    {
        return $this->hasMany(NaturezaAtividadeFuncao::class, 'handle', 'cd_funcao_benner');
    }



}
