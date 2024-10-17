<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class ApiProtectedRoute extends BaseMiddleware
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
        try{
            $user = JWTAuth::parseToken()->authenticate();
        }catch (\Exception $e){
            if($e instanceof TokenInvalidException){
                return response()->json(['success' => false, 'message' => 'Token inválido!']);
            }else if($e instanceof TokenExpiredException){
                return response()->json(['success' => false, 'message' => 'Token expirado!']);
            }else {
                return response()->json(['success' => false, 'message' => 'Você precisa informar o token de acesso!', 'example' => 'Informar no Header: Authorization:Bearer token_acesso']);
            }
        }

        return $next($request);
    }
}
