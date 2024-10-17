<?php

namespace App\Entities\Benner;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cargo extends Model
{
    protected $connection = 'oracleBenner';
    protected $table = null;
    public $incrementing = false;
    public $timestamps = false;

    const DIRETOR_EXECUTIVO = 242;

    /*protected $fillable = [
        'handle',
        'codigo',
        'titulo',
        'classe',
        'carreira',
    ];*/

    /**
     * Cargo constructor.
     */
    public function __construct()
    {
        $this->table = session('DB_BENNER_DATABASE').'.cs_cargos';
    }

}
