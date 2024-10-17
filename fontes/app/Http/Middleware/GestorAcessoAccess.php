<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GestorAcessoAccess
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
        if (Auth::check() && Auth::user()->canAutorizarAcesso()) {
            return $next($request);
        } else {
            return redirect('/novoAcesso');
        }
        return redirect('/home');
    }
}
