<?php

namespace App\Http\Controllers;

use App\Entities\Benner\DadoUsuarioBenner;
use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use Modules\Modulo\Entities\Modulo;
use Modules\Modulo\Entities\TipoModulo;
use Modules\Perfil\Entities\PerfilAcesso;
use Modules\Perfil\Entities\Permissao;
use Modules\Usuario\Entities\UsuarioPerfil;
use Modules\Usuario\Entities\HistoricoAcessoUsuario;
use Modules\Usuario\Entities\AcaoHistoricoAcesso;
use Modules\Usuario\Entities\Usuario;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Mail\EnvioEmailUsuario;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Modules\SAA\Entities\VwDependencia;
use Modules\Usuario\Entities\DadoUsuarioTerceiro;
use App\Http\Services\GerenciarAcessoService;
use Illuminate\Support\Facades\Log;




class GerenciarAcessoController extends Controller
{

    private $service;

    public function __construct(GerenciarAcessoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        if (Auth::user()->isSuperAdmin()) {
            $modulo = Modulo::where('cd_tipo_modulo',TipoModulo::TIPO_INTERNO)->get()->sortBy('ds_modulo')->pluck('ds_modulo', 'cd_modulo');
        } else {
            $nrMatricula = Auth::user()->nr_matricula;
            $permissao = Auth::user()->getModuloGestorAcesso($nrMatricula)->toArray();
            $cdModulo = [];
            foreach ($permissao as $value) {
                $cdModulo[] =  $value->cd_modulo;
            }
            $modulo = Modulo::whereIn('cd_modulo', $cdModulo)->where('cd_tipo_modulo',TipoModulo::TIPO_INTERNO)
        ->orderBy('ds_modulo', 'ASC')
        ->pluck('ds_modulo', 'cd_modulo');
        }

        $dbArea = \DB::table('prh.tb_dado_usuario_benner')
            ->selectRaw('distinct ds_area_benner ds_area')
            ->orderBy('ds_area_benner')
            ->get();

        $area = [];
        foreach ($dbArea as $a) {
            $area[$a->ds_area] = $a->ds_area;
        }

       $terceiros_pendentes =  $this->service->consulta_inicial_pendentes_terceiros();
       $terceiros_acesso    = $this->service->consulta_inicial_acesso_terceiros();
       $area_terceiros      = DB::select('SELECT * FROM PRH.vw_dependencia');

       $usuarios_brb_controle = $this->service->consulta_inicial_acesso_brb();
       return view('gerenciarAcesso.index', compact('modulo', 'area', 'terceiros_pendentes', 'terceiros_acesso', 'area_terceiros', 'usuarios_brb_controle'));
    }



    public function searchPendentesTerceiros(Request $request)
    {

        $retorno = null;
        $retorno = $this->service->searchPendentesTerceiros($request);
        return $retorno;


    }


    public function searchAcessoTerceiros(Request $request)
    {
      $retorno = null;
      $retorno = $this->service->searchAcessoTerceiros($request);
      return json_encode($retorno);

    }


    public function NovoAcesso()
    {
        $modulos = Modulo::where(['deleted_at' => null])->where('cd_tipo_modulo',TipoModulo::TIPO_INTERNO)->orderBy('ds_modulo', 'ASC')->pluck('ds_modulo', 'cd_modulo');
        return view('gerenciarAcesso.novoAcesso', compact('modulos'));
    }


    public function SolicitaNovoAcesso(Request $request)
    {
        $request->validate(
            [
            'cd_modulo' => 'required',
            'cd_perfil' => 'required'
        ],
            [
            'cd_modulo.required' => 'O Campo Módulo é obrigatório',
            'cd_perfil.required' => 'O campo Perfil é obrigatório',
        ]
        );


        $usuarioPerfil = UsuarioPerfil::where('nr_matricula', Auth::user()->nr_matricula)
            ->with('permissao')
            ->whereHas('permissao', function ($query) use ($request) {
                $query->where(['cd_perfil_acesso' => $request->get('cd_perfil'),
                    'cd_modulo' => $request->get('cd_modulo')
                ]);
            })
            ->get()
            ->first();

        $permissao = Permissao::where(['cd_perfil_acesso' => $request->get('cd_perfil'),
            'cd_modulo' => $request->get('cd_modulo')
        ])  ->with('perfil')
            ->with('modulo')
            ->get()
            ->first();

        if ($usuarioPerfil) {
            Alert::alert('', 'Perfil já cadastrado.', 'error');
            return back()->withInput();
        }

        $savePermissao = new UsuarioPerfil();
        $savePermissao->nr_matricula = Auth::user()->nr_matricula;
        $savePermissao->sq_permissao = $permissao->sq_permissao;
        $savePermissao->st_solicitacao = UsuarioPerfil::SOLICITACAO_STATUS_SOLICITADA;
        $savePermissao->save();

        $perfilGestorAcesso = Permissao::where(['cd_perfil_acesso' => PerfilAcesso::GESTOR_DE_ACESSO,
            'cd_modulo' => $request->get('cd_modulo')
        ])->get()->first();

        if($perfilGestorAcesso){

        $usuario =  Usuario::query();
        $usuario->with('usuarioPerfis');
        $usuario->whereHas('usuarioPerfis', function ($query) use ($perfilGestorAcesso) {
            $query->where(['sq_permissao' => $perfilGestorAcesso->sq_permissao,
               'st_solicitacao' => UsuarioPerfil::SOLICITACAO_STATUS_APROVADA
               ]);
        });

        $usuario->select('ds_email');
        $listaEmailGestorModulo = $usuario->get();

        $arrEmail = [];
        foreach ($listaEmailGestorModulo as $email) {
            $arrEmail[] = $email->ds_email;
        }

        $view = 'mail.gerenciarAcesso.novoAcesso';

        $user = Usuario::where('nr_matricula', Auth::user()->nr_matricula)->with('areaBenner')->first();

        $parameters = [
            'usuario'=> $user,
            'mensagem' => 'Solicito novo acesso para o módulo '.$permissao->modulo->ds_modulo. ', com o perfil de '.$permissao->perfil->ds_perfil_acesso.'.' ,
        ];

        // if (count($arrEmail) > 0) {
        //     Mail::to($arrEmail)
        //         ->send(new EnvioEmailUsuario($view, $parameters));
        // }

    }

        if ($savePermissao) {
            Alert::alert('', 'Solicitação realizada com sucesso.', 'success');
            return back()->withInput();
        }
    }

    public function buscarPerfilAcesso(Request $request)
    {
        if ($request->ajax()) {
            $perfis = Perfil::where(['deleted_at' => null, 'cd_modulo' => $request->buscar])->pluck('ds_perfil', 'cd_perfil');
        }
        return $perfis;
    }


    public function getSolicitacoesPendentes(Request $request)
    {
        $response = new \stdClass();

        $usuarios = DB::table('prh.tb_usuario_perfil up')
            ->Join('prh.tb_dado_usuario_benner da', function ($join) {
                $join->on('up.nr_matricula', '=', 'da.nr_matricula')
                    ->where('da.st_registro_ativo', '=', 'S');
            });
        $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
            ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
            ->join('prh.tb_perfil_acesso pe', 'p.cd_perfil_acesso', 'pe.cd_perfil_acesso')
            ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo');

        if (!Auth::user()->isSuperAdmin()) {
            $nrMatricula = Auth::user()->nr_matricula;
            $permissao = Auth::user()->getModuloGestorAcesso($nrMatricula)->toArray();
            $cdModulo = [];
            foreach ($permissao as $value) {
                $cdModulo[] =  $value->cd_modulo;
            }

            $usuarios->whereIn('m.cd_modulo', $cdModulo);
        }

        if ($request->filled('cd_modulo')) {
            $usuarios->where('p.cd_modulo', $request->cd_modulo);
        }

        if ($request->filled('ds_area')) {
            $usuarios->where('da.ds_area_benner', $request->ds_area);
        }

        if ($request->filled('nr_matricula')) {
            $usuarios->where('u.nr_matricula', $request->nr_matricula);
        }

        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_SOLICITADA);
        $usuarios->whereNotNull('up.nr_matricula');
        $usuarios->orderBy('u.nr_matricula');
        $results = $usuarios->get();

        $funcionario = new Funcionario();
        $matriculas = $results->pluck("nr_matricula")->toArray();
        $nomesSociais = $funcionario->consultarNomeSocialPorLote($matriculas);

        if(sizeof($nomesSociais) > 0){
            foreach($results as $usuario){
                    if(array_key_exists($usuario->nr_matricula, $nomesSociais)){
                        $usuario->no_usuario = $nomesSociais[$usuario->nr_matricula];
                    }
            }
        }

        $response->count = $results->count();
        $response->data = $results;

        return response()->json($response);

    }

    public function getControleAcesso(Request $request)
    {
        $retorno = null;
        $retorno = $this->service->search_controle_brb($request);
        return json_encode($retorno);
    }



    public function show($id)
    {
        $usuario = UsuarioPerfil::where('sq_usuario_perfil', $id)
            ->with('usuario')
            ->with('permissao.modulo')
            ->with('permissao.perfil')
            ->get()
            ->first();
        $dadosBenner = DadoUsuarioBenner::where(['nr_matricula'=> $usuario->nr_matricula, 'st_registro_ativo' => 'S'])->get()->first();

        $cd_area_terceiro = $usuario->areaTerceiro ? $usuario->areaTerceiro->cd_area_usuario : null;
        $ds_area_terceiro = VwDependencia::lotacao($cd_area_terceiro);

        return view('gerenciarAcesso.show', compact('usuario', 'dadosBenner', 'ds_area_terceiro'));
    }


    public function showPerfis($nr_Matricula, $idAction)
    {
        $nrMatricula = $nr_Matricula;
        $historico_acesso = new HistoricoAcessoUsuario;
        $dadosHistorico = $historico_acesso->historico_acesso_usuario($nrMatricula);
        $permissao = Auth::user()->getModuloGestorAcesso($nrMatricula)->toArray();
        $arrModuloPermissao = [];
        foreach ($permissao as $value) {
            $arrModuloPermissao[] =  $value->cd_modulo;
        }


        $usuario = Usuario::where('nr_matricula', $nrMatricula)
            ->with('usuarioPerfis.permissao.perfil')
            ->with('usuarioPerfis.permissao.modulo')
            ->get()
            ->first();

        $dadosBenner = DadoUsuarioBenner::where(['nr_matricula'=> $nrMatricula, 'st_registro_ativo' => 'S'])->get()->first();

        $cd_area_terceiro = $usuario->areaTerceiro ? $usuario->areaTerceiro->cd_area_usuario : null;
        $ds_area_terceiro = VwDependencia::lotacao($cd_area_terceiro);

        if ($idAction == 1) {
            $action = 'visualizar';
        } else {
            $action = 'deletar';
        }
        return view('gerenciarAcesso.showControleAcesso', compact('usuario', 'dadosBenner', 'action', 'arrModuloPermissao', 'dadosHistorico', 'ds_area_terceiro'));
    }

    public function infoAcessosUsuarios(Request $request){
        $modulos = [];
        $acessos = [];

        // if(sizeof($request->nrMatriculas) >= 1000){
        //     $chunks = array_chunk($request->nrMatriculas, 1000);

        //     foreach($chunks as $chunk){
        //         $usuarios = Usuario::whereIn('nr_matricula', $chunk)
        //             ->with('usuarioPerfis.permissao.perfil')
        //             ->with('usuarioPerfis.permissao.modulo')
        //             ->get();
                
        //             foreach($usuarios as $usuario){
        //                 foreach($usuario->usuarioPerfis as $perfil){
        //                     $modulos[$usuario->nr_matricula][] = $perfil->permissao->modulo->ds_modulo;
        //                     $acessos[$usuario->nr_matricula][] = $perfil->permissao->perfil->ds_perfil_acesso;
        //                 }
        //             }
        //     }
        // }else{
            $usuarios = Usuario::whereIn('nr_matricula', $request->nrMatriculas)
                ->with('usuarioPerfis.permissao.perfil')
                ->with('usuarioPerfis.permissao.modulo')
                ->get();
            
            foreach($usuarios as $usuario){
                foreach($usuario->usuarioPerfis as $perfil){
                    $modulos[$usuario->nr_matricula][] = $perfil->permissao->modulo->ds_modulo;
                    $acessos[$usuario->nr_matricula][] = $perfil->permissao->perfil->ds_perfil_acesso;
                }
            }
        //}


        return json_encode(["modulos" => $modulos, "acessos" => $acessos]);
    }

    public function ativarPerfilAcessoUsuario(Request $request, $sq_usuario_perfil)
    {

        try {
            DB::beginTransaction();
            UsuarioPerfil::where('sq_usuario_perfil', '=', $sq_usuario_perfil)->update([
                 'st_solicitacao' => 'A',
            ]);
            HistoricoAcessoUsuario::create([
                'sq_usuario_perfil' => $sq_usuario_perfil,
                'cd_acao_hist_acesso' => AcaoHistoricoAcesso::ATIVACAO,
                'dt_inclusao'        => Carbon::now()
            ]);
            DB::commit();
            Alert::alert('', 'Perfil de acesso ativado com sucesso.', 'success');
            return json_encode(['msg' => 'Perfil de acesso ativado com sucesso.']);
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }

    }
    public function removePerfilAcessoUsuario(Request $request, $sq_usuario_perfil)
    {
        // dd($request->all(), $sq_usuario_perfil);
        try {
            DB::beginTransaction();
            UsuarioPerfil::where('sq_usuario_perfil', '=', $sq_usuario_perfil)->update([
                 'st_solicitacao' => 'R',
            ]);
            HistoricoAcessoUsuario::create([
                'sq_usuario_perfil' => $sq_usuario_perfil,
                'cd_acao_hist_acesso' => AcaoHistoricoAcesso::REPROVACAO,
                'dt_inclusao'        => Carbon::now()
            ]);
            DB::commit();
            Alert::alert('', 'Perfil de acesso inativado com sucesso.', 'success');
            return json_encode(['msg' => 'Perfil de acesso inativado com sucesso.']);
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollback();
        }
    }

    public function adicionarPerfil($nrMatricula)
    {
        $modulo = null;
        $usuario = Usuario::where('nr_matricula', $nrMatricula)
            ->with('usuarioPerfis.permissao.perfil')
            ->with('usuarioPerfis.permissao.modulo')
            ->get()
            ->first();


            $cd_area_terceiro = $usuario->areaTerceiro ? $usuario->areaTerceiro->cd_area_usuario : null;
            $ds_area_terceiro = VwDependencia::lotacao($cd_area_terceiro);
            $usuario_terceiro = DadoUsuarioTerceiro::where('nr_matricula', '=', $usuario->nr_matricula)->first();

            $usuarioAuth = Auth::user();

        if ($usuarioAuth->isSuperAdmin()) {
            $modulo = Modulo::where('cd_tipo_modulo',TipoModulo::TIPO_INTERNO)->get()->sortBy('ds_modulo')->pluck('ds_modulo', 'cd_modulo');
        } else {
            $modulo = DB::table('prh.tb_usuario_perfil up')
                ->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
                ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo')
                ->whereIn('p.cd_perfil_acesso', [PerfilAcesso::GESTOR_DE_ACESSO, PerfilAcesso::ADMINISTRADOR])
                ->where('up.nr_matricula', $usuarioAuth->nr_matricula)
                ->orderBy('m.ds_modulo')
                ->get()->pluck('ds_modulo', 'cd_modulo');
        }

        if(!is_null($usuario_terceiro))
        {
           $modulo  = Modulo::where('cd_modulo', Modulo::MODULO_SAA)->get()->sortBy('ds_modulo')->pluck('ds_modulo', 'cd_modulo');
        }

        $perfil = [];

        return view('gerenciarAcesso.adicionarControleAcesso', compact('usuario', 'modulo', 'perfil', 'ds_area_terceiro'));
    }

    public function saveAdicionarPerfil(Request $request)
    {
        $request->validate(
            [
            'cd_modulo' => 'required',
            'cd_perfil' => 'required'
        ],
            [
                'cd_modulo.required' => 'O Campo Módulo é obrigatório',
                'cd_perfil.required' => 'O campo Perfil é obrigatório',
            ]
        );


        $usuarioPerfil = UsuarioPerfil::where('nr_matricula', $request->get('nr_matricula'))
            ->with('permissao')
            ->whereHas('permissao', function ($query) use ($request) {
                $query->where(['cd_perfil_acesso' => $request->get('cd_perfil'),
                    'cd_modulo' => $request->get('cd_modulo')
                ]);
            })
            ->get()
            ->first();

        $permissao = Permissao::where(['cd_perfil_acesso' => $request->get('cd_perfil'),
            'cd_modulo' => $request->get('cd_modulo')
        ])->get()->first();

        if ($usuarioPerfil) {
            Alert::alert('', 'Perfil já cadastrado.', 'error');
            return back()->withInput();
        }

        $savePermissao = new UsuarioPerfil();
        $savePermissao->nr_matricula = $request->get('nr_matricula');
        $savePermissao->sq_permissao = $permissao->sq_permissao;
        $savePermissao->nr_matricula_gestor_acesso = Auth::user()->nr_matricula;
        $savePermissao->st_solicitacao = UsuarioPerfil::SOLICITACAO_STATUS_APROVADA;
        $savePermissao->save();

        if ($savePermissao) {
            HistoricoAcessoUsuario::create([
                'sq_usuario_perfil' => $savePermissao->sq_usuario_perfil,
                'cd_acao_hist_acesso' => AcaoHistoricoAcesso::CONCEDER_ACESSO,
                'dt_inclusao'        => Carbon::now()
            ]);
            Alert::alert('', 'Perfil de acesso cadastrado com sucesso.', 'success');
        }


        return back()->withInput();
    }

    public function deletarPerfil($id)
    {
        // if (UsuarioPerfil::destroy($id)) {
        //     Alert::alert('', 'Registro excluído com sucesso.', 'success');
        //     return json_encode(['msg' => 'Registro excluído com sucesso.']);
        // }
        dd($id);
    }

    public function aprovarSolicitacao($id)
    {
        $usuario = UsuarioPerfil::where('sq_usuario_perfil', $id)
            ->with('usuario')
            ->with('permissao.modulo')
            ->with('permissao.perfil')
            ->get()
            ->first();

        $perfil = PerfilAcesso::with('permissoes');
        $perfil->whereHas('permissoes', function ($query) use ($usuario) {
            $query->where('cd_modulo', $usuario->permissao->modulo->cd_modulo);
        });

        $perfilAcesso = $perfil->pluck('ds_perfil_acesso', 'cd_perfil_acesso');

        return view('gerenciarAcesso.aprovarSolicitacao', compact('perfilAcesso', 'usuario'));
    }


    public function saveAprovacaoSolicitacao(Request $request)
    {
        $request->validate(
            [
            'cd_perfil' => 'required',
        ],
            [
                'cd_perfil.required' => 'O campo Perfil é obrigatório',
            ]
        );
        $permissao = Permissao::where(['cd_perfil_acesso' => $request->get('cd_perfil'),
            'cd_modulo' => $request->get('cd_modulo')
        ])->get()->first();

        $usuarioPerfil = UsuarioPerfil::find($request->get('sq_usuario_perfil'));
        $usuarioPerfil->sq_permissao = $permissao->sq_permissao;
        $usuarioPerfil->nr_matricula_gestor_acesso = Auth::user()->nr_matricula;
        $usuarioPerfil->st_solicitacao = 'A';
        $update = $usuarioPerfil->save();

        if ($update) {
            HistoricoAcessoUsuario::create([
                'sq_usuario_perfil' => $request->get('sq_usuario_perfil'),
                'cd_acao_hist_acesso' => AcaoHistoricoAcesso::APROVACAO,
                'dt_inclusao'        => Carbon::now()
            ]);
            Alert::alert('', 'Aprovação efetuada com sucesso.', 'success');
            return redirect(route('gerenciarAcesso'));
        }
        return back()->withInput();
    }

    public function reprovarSolicitacao($id)
    {
        $usuario = UsuarioPerfil::where('sq_usuario_perfil', $id)
            ->with('usuario')
            ->with('permissao.modulo')
            ->with('permissao.perfil')
            ->get()
            ->first();

        return view('gerenciarAcesso.reprovarSolicitacao', compact('usuario'));
    }

    public function saveReprovacaoSolicitacao(Request $request)
    {
        $request->validate(
            [
            'ds_justificativa' => 'required|max:400',
        ],
            [
                'ds_justificativa.required' => 'O campo Justifcativa é obrigatório',
                'ds_justificativa.max' => 'O número máximo permitido é de 400 caracteres',
            ]
        );

        $usuarioPerfil = UsuarioPerfil::find($request->get('sq_usuario_perfil'));
        $usuarioPerfil->nr_matricula_gestor_acesso = Auth::user()->nr_matricula;
        $usuarioPerfil->ds_justificativa = $request->get('ds_justificativa');
        $usuarioPerfil->st_solicitacao = 'R';
        $update = $usuarioPerfil->save();

        if ($update) {
            HistoricoAcessoUsuario::create([
                'sq_usuario_perfil' => $request->get('sq_usuario_perfil'),
                'cd_acao_hist_acesso' => AcaoHistoricoAcesso::REPROVACAO,
                'dt_inclusao'        => Carbon::now()
            ]);
            Alert::alert('', 'Reprovação efetuada com sucesso.', 'success');
            return redirect(route('gerenciarAcesso'));
        }
        return back()->withInput();
    }
}
