<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use Modules\Usuario\Entities\Usuario;

class AuthController extends Controller
{

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
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['no_usuario', 'password']);

        if($request->get('nr_matricula')){
            $credentials = $request->only(['nr_matricula', 'password']);

           $usuario = Usuario::find($request->get('nr_matricula'));

            if (!$usuario->isSuperAdmin()) {
                return response()->json(['success' => false, 'error' => 'Acesso Não autorizado'], 401);
            }
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['success' => false, 'error' => 'Credenciais de acesso incorretas'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['success'=> true, 'message' => 'Sucesso ao efetuar logout']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => 3600
        ]);
    }
}
