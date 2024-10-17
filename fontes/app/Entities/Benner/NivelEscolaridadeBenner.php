<?php

namespace App\Entities\Benner;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NivelEscolaridadeBenner extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    public function __construct()
    {
        $this->table = Helper::getConstanteBancoBennerAmbiente().'.ta_niveisescolaridade';
    }

    public function getNiveisEscolaridade()
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT  NE.handle,
                        NE.nivel,
                        NE.nome
                FROM {$constanteBanco}.TA_NIVEISESCOLARIDADE NE";

        return DB::connection('oracleBenner')->select($sql);
    }
}
