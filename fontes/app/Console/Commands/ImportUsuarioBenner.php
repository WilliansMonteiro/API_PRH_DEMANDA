<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exceptions\Handler;
use Modules\Usuario\Entities\Usuario;



class ImportUsuarioBenner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:usuario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar usuarios do Benner';

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
         $usuario = new Usuario();
         $import = $usuario->saveImportacaoBaseBenner();
         return $import;
    }
}
