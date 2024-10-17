<?php

namespace App\Console\Commands;
use App\Exceptions\Handler;
use Illuminate\Console\Command;
use App\Entities\Benner\DadoUsuarioBenner;

class ImportFuncaoCargoUsuario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importar:cargo_funcao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para atualizar diaramente cargo e função do funcionário';

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
        $dado_usuario_benner = new DadoUsuarioBenner;
        $atualizar_cargo_funcao = $dado_usuario_benner->verifica_dados_usuario();
        return $atualizar_cargo_funcao;
    }
}
