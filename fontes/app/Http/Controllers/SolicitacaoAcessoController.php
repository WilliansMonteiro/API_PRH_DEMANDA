<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Modulo\Entities\TipoModulo;
use Modules\Perfil\Entities\Perfil;
use Modules\Modulo\Entities\Modulo;
use Modules\Perfil\Entities\Permissao;
use Modules\Usuario\Entities\Usuario;
use Modules\Usuario\Entities\UsuarioPerfil;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Modules\SAA\Entities\VwDependencia;
use Modules\Usuario\Entities\DadoUsuarioTerceiro;
use Modules\Usuario\Entities\HistoricoAcessoUsuario;
use Modules\Usuario\Entities\AcaoHistoricoAcesso;
use Carbon\Carbon;



class SolicitacaoAcessoController extends Controller
{
    public function index()
    {

        $usuario = Auth::user();
        $modulo = null;
        $cd_area_terceiro = $usuario->areaTerceiro ? $usuario->areaTerceiro->cd_area_usuario : null;
        $ds_area_terceiro = VwDependencia::lotacao($cd_area_terceiro);
        $usuario_terceiro = DadoUsuarioTerceiro::where('nr_matricula', '=', $usuario->nr_matricula)->first();
        if(is_null($usuario_terceiro)){
            $modulo  = Modulo::where('cd_tipo_modulo',TipoModulo::TIPO_INTERNO)->get()->sortBy('ds_modulo')->pluck('ds_modulo', 'cd_modulo');
        }else{
            $modulo  = Modulo::where('cd_modulo', Modulo::MODULO_SAA)->get()->sortBy('ds_modulo')->pluck('ds_modulo', 'cd_modulo');
        }
        $perfil  = [];


        return view('solicitacaoAcesso.index', compact('modulo', 'perfil', 'usuario', 'ds_area_terceiro'));
    }

    public function saveSolicitaAcesso(Request $request)
    {

        $request->validate([
            'ds_telefone' => 'required',
            'ds_email' => 'required',
            'cd_modulo' => 'required',
            'cd_perfil' => 'required',
        ],
            [
                'ds_telefone.required' => 'O campo Telefone é obrigatório',
                'ds_email.required' => 'O campo E-mail é obrigatório',
                'cd_modulo.required' => 'Selecione um Módulo',
                'cd_perfil.required' => 'Selecione um Perfil',
            ]);

        $nrMatricula = Auth::user()->nr_matricula;

        $usuario = Usuario::where('nr_matricula','=', $nrMatricula)
            ->with('usuarioPerfis')
            ->get()
            ->first();

        if($usuario->st_primeiro_acesso == 'S' && !$usuario->hasSolicitacaoPrimeiroAcesso()) {

            $usuario->update([
                'ds_telefone' => $request->get('ds_telefone'),
                'ds_email' => $request->get('ds_email'),
                'st_primeiro_acesso' => 'N'
            ]);

            $permissao = Permissao::where(['cd_perfil_acesso' => $request->get('cd_perfil'),
                'cd_modulo' => $request->get('cd_modulo')
            ])->get()->first();

            $usuarioPerfil = UsuarioPerfil::create([
               'sq_permissao' => $permissao->sq_permissao,
                'nr_matricula' => $nrMatricula,
                'dt_registro' => new \DateTime(),
                'ds_justificativa' => null,
                'nr_matricula_gestor_acesso' => null,
                'st_solicitacao' => 'S'
            ]);
            $usuarioPerfil->save();
            if($usuarioPerfil){
                HistoricoAcessoUsuario::create([
                    'sq_usuario_perfil'   => $usuarioPerfil->sq_usuario_perfil,
                    'cd_acao_hist_acesso' => AcaoHistoricoAcesso::SOLICITACAO_ACESSO,
                    'dt_inclusao'         => Carbon::now()
                ]);
                Alert::toast('Solicitação realizada com sucesso.', 'success')->background('#55a846');
                return back()->withInput();
            }else{
                Alert::toast('Erro ao solicitar acesso, tente novamente!', 'error');
                return back()->withInput();
            }
        }

        Alert::toast('Você já possui solicitação de acesso ativa.', 'warning');
        return back()->withInput();

    }
}
