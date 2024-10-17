<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class EnvioEmailSaa extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $parameters;


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
        return $this->from(env('MAIL_FROM'))
            ->view($this->view)
            ->with([
                'parameters' => $this->parameters
            ]);
    }
}
