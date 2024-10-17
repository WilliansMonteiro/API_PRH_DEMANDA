<?php

namespace App\Http\Middleware;

use App\Entities\Benner\Cargo;
use App\Entities\Benner\FuncaoGratificada;
use Closure;
use Modules\Menu\Entities\Menu;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class CheckAccessRouterMenuBuilder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $nrMatricula = Auth::user()->nr_matricula;
        $uri = $request->route()->getPrefix();

        if(Auth::user()->dadosBennerAtivo()->count() > 0&& $uri == '/modulo-movimentacao'){
            if(Auth::user()->dadosBennerAtivo()[0]->cd_funcao_benner == FuncaoGratificada::SUPERINTENDENTE ||
                Auth::user()->dadosBennerAtivo()[0]->cd_cargo_benner == Cargo::DIRETOR_EXECUTIVO
            ){
                return $next($request);
            }
        }

        $menu = new Menu();
        $consultaRota = $menu->rotasByMatricula($nrMatricula, $uri)->count();

        if($consultaRota === 0){
            Alert::toast('Você não possui perfil de acesso para esse módulo!', 'warning');
            return redirect('/home');
        }

        return $next($request);
    }
}
