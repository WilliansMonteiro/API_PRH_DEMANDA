<?php

namespace App\Console\Commands;

use App\Exceptions\Handler;
use Illuminate\Console\Command;
use Modules\ProcessoSeletivo\Http\Services\CargaRequisitoFuncaoBennerService;

class CargaCertificacaoBenner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carga:certificacao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza certificações vindas do Benner';

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
        $pccr = new CargaRequisitoFuncaoBennerService();
        $carga  = $pccr->cargaCertificacoesBenner();
        return $carga;
    }
}
