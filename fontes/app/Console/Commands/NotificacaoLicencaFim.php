<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Solicitacao\Entities\Solicitacao;
use App\Mail\EnvioEmailUsuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\Handler;

class NotificacaoLicencaFim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacao:solicitacao';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disparo de E-mail quando o Tempo da Licença Estiver perto do Fim';

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
        
        try{

            $solicitacoes = Solicitacao::all();
            
            foreach ($solicitacoes as $solicitacao) {
            
                $dataFim = isset($solicitacao->historico('dt_fim_solicitacao')->first()->dt_fim_solicitacao) ? $solicitacao->historico('dt_fim_solicitacao')->first()->getOriginal('dt_fim_solicitacao') : null;

                if(!is_null($dataFim)){

                    if( Carbon::parse($dataFim)->isFuture() && isset($solicitacao->ds_email) ){
                        
                        $diasFimLicenca = daysBetweenDates($dataFim);
                        //Enviar um E-mail de Noticicação 60,30 e 15 Dias antes do Fim da Licença
                        if(in_array($diasFimLicenca,[60,30,15])){
                            $this->emailLicenca($solicitacao);
                        }
                        
                    }

                }
                
            }

    
        }catch(\Exception $e){
            $this->handler->sendEmail($e);
        }

    }


    public function emailLicenca($solicitacao){

        $view = 'solicitacao::mail.enviarNotificacaoFimLicenca';

        $parameters = [
            'dataAtual' => Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now())->format('d/m/Y'),
            'assunto' => $solicitacao->tipoSolicitacao->no_tipo_solicitacao,
            'solicitacao' => $solicitacao,
            'licencaFim' => $solicitacao->historico('dt_fim_solicitacao')->first()->dt_fim_solicitacao ];
   
            Mail::to($solicitacao->ds_email)
               ->send(new EnvioEmailUsuario($view, $parameters)); 
    }
}
