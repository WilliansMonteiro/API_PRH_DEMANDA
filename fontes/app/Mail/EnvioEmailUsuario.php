<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class EnvioEmailUsuario extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $parameters;
    const EMAIL_FROM = 'noreply@brb.com.br';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $parameters = [])
    {
        $this->view = $view;
        $this->parameters = $parameters;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //Enviar email somente em ambinete de produção, caso contrario o enviar email para nuext.
        if(config('app.env') == 'prd'){
            $emailDestino = $this->to[0]['address'];
        }else{
            $emailDestino = 'servnuext@brb.com.br';
        }

        //Redirecionar os emails da DIPES para o email pessoal da diretora a pedido da GEREG.
        if($emailDestino == 'ddipes@brb.com.br'){
            $emailDestino = 'cristiane.bukowitz@brb.com.br';
        }

        //Limpando Valores das Controllers
        $this->to = [];

        return $this->from(self::EMAIL_FROM)->to($emailDestino)
            ->view($this->view)
            ->with([
                'parameters' => $this->parameters
            ]);
    }
}
