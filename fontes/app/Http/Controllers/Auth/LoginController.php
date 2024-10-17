<?php

namespace App\Http\Controllers\Auth;

use Adldap\AdldapInterface;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Connection\SSOFactory;
use Modules\Usuario\Entities\DadoUsuarioTerceiro;
use Modules\Usuario\Entities\Usuario;
use App\Entities\Benner\Funcionario;
use App\Http\Requests\LoginRequest;
use Modules\Auditoria\Entities\Auditoria;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    protected $ldap;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'nr_matricula';
    }   

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username());
    }

    protected function authenticated(Request $request, $user)
    {
        if(Auth::check() && Auth::user()->hasAcessoAprovado()){
            return redirect()->route('home');
        }
        return redirect()->route('solicitaAcesso');
    }

    public static function v4() 
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

    // 32 bits for "time_low"
    mt_rand(0, 0xffff), mt_rand(0, 0xffff),

    // 16 bits for "time_mid"
    mt_rand(0, 0xffff),

    // 16 bits for "time_hi_and_version",
    // four most significant bits holds version number 4
    mt_rand(0, 0x0fff) | 0x4000,

    // 16 bits, 8 bits for "clk_seq_hi_res",
    // 8 bits for "clk_seq_low",
    // two most significant bits holds zero and one for variant DCE1.1
    mt_rand(0, 0x3fff) | 0x8000,

    // 48 bits for "node"
    mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

    public function showLoginForm()
    {
        $sso = SSOFactory::getInstance();

        $matriculaUsuario =  $sso->getUser();
        $maquinaDoUsuarioLogado   =  $sso->getMachine();
        $hashLogin = self::v4();

        Session::forget('matriculaDoUsuarioLogado');
        Session::forget('maquinaDoUsuarioLogado');
        Session::forget('hashLogin');

        Session::put('matriculaDoUsuarioLogado', $matriculaUsuario);
        Session::put('maquinaDoUsuarioLogado', $maquinaDoUsuarioLogado);
        Session::put('hashLogin', $hashLogin);

        return view('auth.login', compact('matriculaUsuario', 'hashLogin'));
    }


    public function login(LoginRequest $request)
    {       
      
        $nrMatricula = $request->get('nr_matricula');
        
        if($nrMatricula !== Session::get('matriculaDoUsuarioLogado')){
            return redirect()->back()->withErrors(['msg' => 'Matricula do Usuário não identificado pelas credenciais fornecidas, verifique e tente novamente!']); 
        }
        
        $usuarioLdap = $this->ldap->search()->where('cn' , '=' , 'u'.Session::get('matriculaDoUsuarioLogado'))->get()->first();

        if($nrMatricula === Session::get('matriculaDoUsuarioLogado') && ($usuarioLdap  !== null && $this->ldap->auth()->attempt($usuarioLdap->getDistinguishedName(), $request->get('codigo'), true))){
            $user  = Usuario::where('nr_matricula',$nrMatricula)->first();

            $funcionario = new Funcionario();
            $dadosFuncionario = $funcionario->consultaDadosFuncionario($nrMatricula);
    
            if(!$user){
                $usuario = new Usuario();
                $usuario->savePrimeiroAcesso($nrMatricula);
            }
    
            if(!$dadosFuncionario){
                $usuario_terceiro = new DadoUsuarioTerceiro();
                $usuario_terceiro->saveAcessoTerceiro($nrMatricula);
            }
    
            if ($this->attemptLogin($request)) {
    
                $retorno_auditoria = Auditoria::create([
                    'cd_evento' => 1,
                    'nr_matricula' => $nrMatricula,
                    'ds_complemento' => "Acesso do usuário $nrMatricula ao PRH - Plataforma Auxiliar de Recursos Humanos",
                ]);
    
                if($retorno_auditoria == true){
                    return $this->sendLoginResponse($request);
                } else {
                    dd('Erro! Não foi possível registrar o log de acesso na tabela Auditoria.');
                }
                    
            }            
    
            $this->incrementLoginAttempts($request); 
        }else{
            return redirect()->back()->withErrors(['msg' => 'Usuário não identificado pelas credenciais fornecidas, verifique sua senha']);
        }
        

        return $this->sendFailedLoginResponse($request);
    }

    protected function attemptLogin(Request $request)
    {
       return Auth::loginUsingId($this->credentials($request),true);
    }




}
