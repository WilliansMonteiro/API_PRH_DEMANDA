<?php

namespace App\Entities\Benner;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GrauEnsinoBenner extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    public function __construct()
    {
        $this->table = Helper::getConstanteBancoBennerAmbiente().'.ta_grausensino';
    }

    public function getGrausEnsino()
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT  QA.handle,
                        QA.nome,
                        QA.nivel
                FROM {$constanteBanco}.TA_GRAUSENSINO QA";

        return DB::connection('oracleBenner')->select($sql);
    }
}
