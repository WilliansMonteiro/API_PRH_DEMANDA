<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UsuarioRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Modulo\Entities\Modulo;
use Modules\Perfil\Entities\PerfilAcesso;
use Modules\Usuario\Entities\Usuario;
use Modules\Usuario\Entities\UsuarioPerfil;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{

    public function search(Request $request)
    {
        $usuarios = DB::table('prh.tb_usuario_perfil up');
        $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
            ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
            ->join('prh.tb_perfil_acesso pe', 'p.cd_perfil_acesso', 'pe.cd_perfil_acesso')
            ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo');
        $usuarios->where('p.cd_modulo', Modulo::MODULO_API);
        $usuarios->where('pe.cd_perfil_acesso', PerfilAcesso::API);

        if ($request->filled('no_usuario')) {
            $usuarios->where('u.no_usuario', $request->no_usuario);
        }

        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_APROVADA);
        $usuarios->orderBy('u.nr_matricula');
        $consultaDadosUsuario = $usuarios->get();
        return response()->json(['success' => true, 'data' => $consultaDadosUsuario]);
    }

    public function store(UsuarioRequest $request)
    {

        $password = Hash::make($request->password);

        $consultaUsuario = Usuario::where(['no_usuario' => $request->no_usuario])->get()->first();

        if($consultaUsuario){
            return response()->json(['success' => true, 'message' => 'Usuário já cadastrado']);
        }

        $usuario = new Usuario();
        $novoUsuario = $usuario->saveUserApi($request->no_usuario, $password);

        if($novoUsuario->status){
            return response()->json(['success' => true, 'message' => 'Usuário cadastrado com sucesso']);
        }

        return response()->json(['success' => false, 'message' => 'Erro ao cadastrar usuário, tente novamente!']);
    }

    public function update(UsuarioRequest $request)
    {
        $password = Hash::make($request->password);
        if(filled($request->no_usuario)){
            $parameters = ['no_usuario' => $request->no_usuario];
        }

        if(filled($request->nr_matricula)){
            $parameters = [ 'nr_matricula' => $request->nr_matricula];
        }

        $consultaUsuario = Usuario::where($parameters)->get()->first();

        if(!$consultaUsuario){
            return response()->json(['success' => false, 'message' => 'Usuário não encontrado!']);
        }

        $atualizaUsuario = $consultaUsuario->update(['ds_senha'=> $password]);

        if($atualizaUsuario){
            return response()->json(['success' => true, 'message' => 'Usuário atualizado com sucesso']);
        }

        return response()->json(['success' => false, 'message' => 'Erro ao atualizar usuário, tente novamente!']);
    }
}
