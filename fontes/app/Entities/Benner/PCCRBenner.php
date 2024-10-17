<?php

namespace App\Entities\Benner;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PCCRBenner extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    const BRB = 1;

    public function getPCCR()
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $sql = "SELECT  PCCR.handle,
                        PCCR.nome
                FROM {$constanteBanco}.CS_CLASSES PCCR WHERE PCCR.empresa = 1";

        return DB::connection('oracleBenner')->select($sql);
    }
}
