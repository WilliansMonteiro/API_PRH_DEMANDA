<?php

namespace App\Http\Controllers;

use App\Mail\EnvioEmailUsuario;
use Illuminate\Support\Facades\Mail;
use Modules\Usuario\Entities\Usuario;



class TesteMailController extends Controller
{
    public function index()
    {
        $view = 'mail.test';

        $user = Usuario::where('nr_matricula', 653667)->first();

        $parameters = [
            'usuario'=> $user,
            'mensagem' => 'mensagem qualquer'
        ];


        Mail::to('marciombsaraiva@gmail.com')
            ->send(new EnvioEmailUsuario($view, $parameters));
       
        
    }
}
