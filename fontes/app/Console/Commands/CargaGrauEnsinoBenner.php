<?php

namespace App\Console\Commands;

use App\Exceptions\Handler;
use Illuminate\Console\Command;
use Modules\ProcessoSeletivo\Http\Services\CargaRequisitoFuncaoBennerService;

class CargaGrauEnsinoBenner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carga:escolaridade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza escolaridades vindas do Benner (grau ensino)';

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
        $escolaridade = new CargaRequisitoFuncaoBennerService();
        $carga        = $escolaridade->cargaEscolaridadesBenner();
        return $carga;
    }
}
