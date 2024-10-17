<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Connection\SSOFactory;
use Illuminate\Support\Facades\Auth;
use Modules\Menu\Entities\Menu;
use App\Entities\Benner\DadoUsuarioBenner;
use Illuminate\Support\Str;
use Modules\Modulo\Entities\Modulo;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Session::put('DB_DATABASE', 'prh');

        if(isset($_SERVER["SERVER_NAME"]) && strpos(" " . $_SERVER["SERVER_NAME"], 'prh.brb.com.br') == true){
            Session::put('DB_BENNER_DATABASE', 'rhproducao');
        }elseif(isset($_SERVER["SERVER_NAME"]) && strpos(" " . $_SERVER["SERVER_NAME"], 'prhhmo.brb.com.br')== true){
            Session::put('DB_BENNER_DATABASE', 'rh_homologacao');
        }else{
            Session::put('DB_BENNER_DATABASE', 'rhdesenvolvimento');
        }


        view()->composer(['layout.partials.sidebar', 'view1', 'view2'], function($view) {

            if(Request::route() == null){
                return redirect('/home');
            }
            $uriModulo = '';
            $uriRotaNivel1 = '';
            $uriRotaNivel2 = '';
            $uriRotaNivel3 = '';
            $cdModulo = null;
            $dsModuloDashboard = null;
            $routedashboard = null;
            $uri = Request::path();

            if (Auth::check()){

                $nrMatricula = Auth::user()->nr_matricula;
                $dadosUsuarioBennerAtivo = Auth::user()->dadosBennerAtivo();

                if($dadosUsuarioBennerAtivo->count() === 0){

                    $dadoUsuarioBenner = new DadoUsuarioBenner();
                    $dadoUsuarioBenner->salvar($nrMatricula);
                }

                $url = explode('/',Request::path());

                $uriModulo = $url[0];
                $uriRotaNivel1 = isset($url[1]) ? $url[1] : '';
                $uriRotaNivel2 = isset($url[2]) ? $url[2] : '';
                $uriRotaNivel3 = isset($url[3]) ? $url[3] : '';

                if($uriModulo == 'modulo-avaliacao'){
                    $cdModulo = Modulo::MODULO_AVALIACAO;
                    $routedashboard = route('dashboard.avaliacao');
                    $dsModuloDashboard = 'Dashboard Performance';
                }elseif($uriModulo == 'modulo-admin'){
                    $cdModulo = Modulo::MODULO_ADMIN;
                    $routedashboard = route('dashboard.administracao');
                    $dsModuloDashboard = 'Dashboard Administração';
                }elseif($uriModulo == 'modulo-relatorios'){
                    $cdModulo  = Modulo::MODULO_RELATORIOS;
                    $routedashboard = route('dashboard.relatorios');
                    $dsModuloDashboard = 'Dashboard Relatórios';
                } elseif($uriModulo == 'modulo-solicitacao'){
                    $cdModulo = Modulo::MODULO_SOLICITACOES;
                    $routedashboard = route('dashboard.solicitacao');
                    $dsModuloDashboard = 'Dashboard Solicitações';
                } elseif($uriModulo == 'modulo-saa') {
                    $cdModulo = Modulo::MODULO_SAA;
                    $routedashboard = route('dashboard.saa');
                    $dsModuloDashboard = 'Dashboard SAA';
                }elseif($uriModulo == 'modulo-movimentacao') {
                    $cdModulo = Modulo::MODULO_MOVIMENTACAO;
                    $routedashboard = route('dashboard.movimentacao');
                    $dsModuloDashboard = 'Dashboard Movimentação';
                }elseif($uriModulo == 'modulo-processo-seletivo') {
                    $cdModulo = Modulo::MODULO_PROCESSO_SELETIVO;
                    $routedashboard = route('dashboard.processoSeletivo');
                    $dsModuloDashboard = 'Dashboard Proc. Selet.';
                }elseif($uriModulo == 'modulo-banco-talentos') {
                    $cdModulo = Modulo::MODULO_BANCO_TALENTOS;
                    $routedashboard = route('dashboard.banco.talentos');
                    $dsModuloDashboard = 'Dashboard BTA';
                }

                $menu = new Menu();
                $menuDinamicoNivel1 = $menu->menu_dinamico_nivel1($nrMatricula,$cdModulo);
                $menuDinamicoNivel2 = $menu->menu_dinamico_nivel2($nrMatricula,$cdModulo);
                $menuDinamicoNivel3 = $menu->menu_dinamico_nivel3($nrMatricula,$cdModulo);
                $moduloMenuDinamico = $menu->modulo_menu_dinamico($nrMatricula,$cdModulo);

            } else {
                $menuDinamicoNivel1 = [];
                $menuDinamicoNivel2 = [];
                $menuDinamicoNivel3 = [];
                $moduloMenuDinamico = [];
            }

            $view
                ->with('menuDinamicoNivel1', $menuDinamicoNivel1)
                ->with('menuDinamicoNivel2', $menuDinamicoNivel2)
                ->with('menuDinamicoNivel3', $menuDinamicoNivel3)
                ->with('moduloMenuDinamico', $moduloMenuDinamico)
                ->with('cdModulo', $cdModulo)
                ->with('uriModulo', $uriModulo)
                ->with('uriRotaNivel1', $uriRotaNivel1)
                ->with('uriRotaNivel2', $uriRotaNivel2)
                ->with('uriRotaNivel3', $uriRotaNivel3)
                ->with('uri', $uri)
                ->with('routedashboard', $routedashboard)
                ->with('dsModuloDashboard', $dsModuloDashboard)
            ;
        });


        if (env('APP_ENV') === 'local'){
            URL::forceScheme('http');
            return;
        }
        URL::forceScheme('https');
    }
}
