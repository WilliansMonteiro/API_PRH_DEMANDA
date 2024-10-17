<?php

namespace App\Console\Commands;

use App\Exceptions\Handler;
use Illuminate\Console\Command;
use Modules\ProcessoSeletivo\Http\Services\CargaRequisitoFuncaoBennerService;

class CargaNiveisEscolaridadeBenner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carga:niveis-escolaridade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza níveis de escolaridades na tabela PRH.TB_STATUS_TIPO_ESCOLARIDADE vindos do Benner (TA_NIVEISESCOLARIDADE).';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Handler $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $nivel_escolaridade = new CargaRequisitoFuncaoBennerService();
        $carga = $nivel_escolaridade->cargaNiveisEscolaridadeBenner();
        return $carga;
    }
}
