<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdminAccess
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
        if(Auth::check() && Auth::user()->isSuperAdmin()){
            return $next($request);
        } else {
            Alert::toast('Você não possui perfil de acesso para esse módulo!', 'warning');
            return redirect('/home');
        }
        return redirect('/login');

    }
}
