<?php

namespace App\Entities\Benner;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TrilhaBenner extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    const ATIVIDADES_BANCARIAS = 2;
    const TECNOLOGICA          = 3;
    const JURIDICA             = 4;

    public function __construct()
    {
        $constanteBanco = Helper::getConstanteBancoBennerAmbiente();
        $this->table = $constanteBanco.'.k9_cs_trilhas';
    }
}
