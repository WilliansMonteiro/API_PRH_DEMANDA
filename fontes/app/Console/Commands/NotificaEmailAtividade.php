<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\EnvioEmailUsuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\Handler;
use Modules\Atividade\Entities\Atividade;
use Illuminate\Support\Facades\Log;


class NotificaEmailAtividade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atividade-ponto:notifica-email {ponto}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispara e-mail da Ativdade de Acordo com o Parâmetro do Ponto de Controle Cadastrado';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $handler;
    
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
    
        try {

            $atividades = Atividade::with('responsavel')->where('st_ponto_controle',$this->argument('ponto'))->where('cd_situacao_atividade',Atividade::EM_ATENDIMENTO)->get();

            foreach ($atividades as $atividade) {
                foreach ($atividade->responsavel as $responsavel) {
                    //CONDICIONAL PARA CASO TENHA EMAIL E QUE A DATA SEJA FIM SEJA NO FUTURO OU NO MESMO DIA DA DATA FIM
                    if(isset($responsavel->usuario->ds_email) && (Carbon::parse($atividade->dt_prazo_atividade)->isFuture() || Carbon::parse($atividade->dt_prazo_atividade)->isSameDay())){
                        $this->emailResponsavel($atividade,$responsavel);
                    }
                    
                }
            }

        } catch (\Exception $e) {
            $this->handler->sendEmail($e);
        }
        
        
                       
    }

    public function emailResponsavel($atividade,$responsavel){

        $view = 'mail.atividade.enviaNotificacaoPonto';

        $parameters = [
            'nome_usuario' => $responsavel->usuario->no_usuario,
            'prazo_fim' => Carbon::createFromFormat('Y-m-d H:i:s', $atividade->dt_prazo_atividade)->format('d/m/Y'),
            'descricao_atividade' => $atividade->ds_atividade,
            'porcentagem' => $atividade->conclusao($atividade->sq_atividade),
            'ciclo' => $atividade->cicloAvaliativo->ds_ciclo_avaliativo];

            Mail::to($responsavel->usuario->ds_email)
               ->send(new EnvioEmailUsuario($view, $parameters)); 
    }
}
