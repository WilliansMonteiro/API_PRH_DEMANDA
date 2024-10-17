<?php

namespace App\Console\Commands;
use App\Exceptions\Handler;
use Illuminate\Console\Command;
use Modules\Usuario\Entities\Usuario;


class UsuarioAcesso extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'usuario:acesso';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza o primeiro acesso do usuário caso ele tenha solicitação aprovada';

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
        $usuario = new Usuario;
        $atualiza_acesso = $usuario->acesso_usuarios();
        return $atualiza_acesso;
    }
}
