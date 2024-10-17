<?php

namespace App\Http\Controllers;


use App\Entities\Benner\DadoUsuarioBenner;
use Illuminate\Http\Request;
use Modules\Modulo\Entities\Modulo;
use Modules\Modulo\Entities\TipoModulo;
use Modules\Usuario\Entities\Usuario;
use Modules\Usuario\Entities\UsuarioPerfil;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Atividade\Entities\Notifica;
use Modules\Atividade\Entities\Atividade;
use Modules\Atividade\Entities\AtividadeResponsavel;
use Modules\EquipeAvaliacao\Entities\EquipeAvaliacao;
use Modules\EquipeAvaliacao\Entities\MembroEquipeAvaliacao;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usuario = Usuario::where('nr_matricula', Auth::user()->nr_matricula)->get()->first();
        $dataAtual = date('Y-m-d H:i:s');
        $uniaoNaoiniciada = Atividade::query();
        $uniaoNaoiniciada->join(
          'prh.tb_atividade_responsavel',
          'prh.tb_atividade.sq_atividade',
          'prh.tb_atividade_responsavel.sq_atividade'
      )->join(
           'prh.tb_ciclo_avaliativo',
           'prh.tb_atividade.sq_ciclo_avaliativo',
           'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
          )->where('dt_fim_periodo_acomp_atvd', '>=' , $dataAtual)->where('cd_situacao_atividade', 1);
          $consultaNaoIniciada = $uniaoNaoiniciada->get()->count();


          $uniaoEmAtendimento = Atividade::query();
          $uniaoEmAtendimento->join(
            'prh.tb_atividade_responsavel',
            'prh.tb_atividade.sq_atividade',
            'prh.tb_atividade_responsavel.sq_atividade'
        )->join(
             'prh.tb_ciclo_avaliativo',
             'prh.tb_atividade.sq_ciclo_avaliativo',
             'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
            )->where('dt_fim_periodo_acomp_atvd', '>=' , $dataAtual)->where('cd_situacao_atividade', 2);
            $consultaEmAtendimento = $uniaoEmAtendimento->get()->count();



            $uniaoConcluida = Atividade::query();
            $uniaoConcluida->join(
              'prh.tb_atividade_responsavel',
              'prh.tb_atividade.sq_atividade',
              'prh.tb_atividade_responsavel.sq_atividade'
          )->join(
               'prh.tb_ciclo_avaliativo',
               'prh.tb_atividade.sq_ciclo_avaliativo',
               'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
              )->where('dt_fim_periodo_acomp_atvd', '>=' , $dataAtual)->where('cd_situacao_atividade', 3);
              $consultaConcluida = $uniaoConcluida->get()->count();


              $uniaoCancelada = Atividade::query();
              $uniaoCancelada->join(
                'prh.tb_atividade_responsavel',
                'prh.tb_atividade.sq_atividade',
                'prh.tb_atividade_responsavel.sq_atividade'
            )->join(
                 'prh.tb_ciclo_avaliativo',
                 'prh.tb_atividade.sq_ciclo_avaliativo',
                 'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
                )->where('dt_fim_periodo_acomp_atvd', '>=' , $dataAtual)->where('cd_situacao_atividade', 5);
                $consultaCancelada = $uniaoCancelada->get()->count();




                $uniaoNaoiniciadaGestor = Atividade::query();
                $uniaoNaoiniciadaGestor->join(
                   'prh.tb_ciclo_avaliativo',
                   'prh.tb_atividade.sq_ciclo_avaliativo',
                   'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
                  )->where('cd_situacao_atividade', 1)->where('dt_fim_periodo_acomp_atvd', '>=', $dataAtual);
                  $consultaNaoIniciadaGestor = $uniaoNaoiniciadaGestor->get()->count();



                  $uniaoEmAtendimentoGestor = Atividade::query();
                  $uniaoEmAtendimentoGestor->join(
                     'prh.tb_ciclo_avaliativo',
                     'prh.tb_atividade.sq_ciclo_avaliativo',
                     'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
                    )->where('cd_situacao_atividade', 2)->where('dt_fim_periodo_acomp_atvd', '>=', $dataAtual);
                    $consultaEmAtendimentoGestor = $uniaoEmAtendimentoGestor->get()->count();


                  $uniaoConcluidaGestor = Atividade::query();
                  $uniaoConcluidaGestor->join(
                     'prh.tb_ciclo_avaliativo',
                     'prh.tb_atividade.sq_ciclo_avaliativo',
                     'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
                    )->where('cd_situacao_atividade', 3)->where('dt_fim_periodo_acomp_atvd', '>=', $dataAtual);
                    $consultaConcluidaGestor = $uniaoConcluidaGestor->get()->count();


                    $uniaoCanceladaGestor = Atividade::query();
                    $uniaoCanceladaGestor->join(
                       'prh.tb_ciclo_avaliativo',
                       'prh.tb_atividade.sq_ciclo_avaliativo',
                       'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
                      )->where('cd_situacao_atividade', 5)->where('dt_fim_periodo_acomp_atvd', '>=', $dataAtual);
                      $consultaCanceladaGestor = $uniaoCanceladaGestor->get()->count();


         return view('home', compact('consultaNaoIniciada', 'consultaEmAtendimento', 'consultaConcluida', 'consultaCancelada', 'consultaNaoIniciadaGestor', 'consultaEmAtendimentoGestor', 'consultaConcluidaGestor', 'consultaCanceladaGestor'));
    }

    public function dashboard()
    {
        $matricula = Auth::user()->nr_matricula;

        $modulo_performance = $this->moduloPermissao($matricula, 1);
        $modulo_admin = $this->moduloPermissao($matricula, 2);
        $modulo_processo_seletivo = $this->moduloPermissao($matricula, 4);
        $modulo_saa = $this->moduloPermissao($matricula, 13);
        $modulo_solicitacao = $this->moduloPermissao($matricula, 10);
        $modulo_movimentacao = $this->moduloPermissao($matricula, 3);
        $modulo_relatorio = $this->moduloPermissao($matricula, 12);
        $modulo_banco_talentos = $this->moduloPermissao($matricula, 16);

        $modulos_externos = $this->modulosExternos();

        return view('dashboard.dashboard', compact('modulo_performance','modulo_admin','modulo_saa','modulo_solicitacao','modulo_movimentacao','modulo_relatorio', 'modulo_processo_seletivo','modulo_banco_talentos','modulos_externos'));
    }

    public function moduloPermissao($matricula, $cd_modulo){

        $modulo = UsuarioPerfil::join('prh.tb_permissao', 'tb_usuario_perfil.sq_permissao','=','tb_permissao.sq_permissao')
        ->join('prh.tb_modulo', 'tb_permissao.cd_modulo','=','tb_modulo.cd_modulo')
        ->where('tb_usuario_perfil.nr_matricula', $matricula)
        ->where('tb_permissao.cd_modulo', $cd_modulo)
        ->where('tb_modulo.cd_tipo_modulo',TipoModulo::TIPO_INTERNO)
        ->select('tb_permissao.cd_modulo','tb_modulo.ds_modulo','tb_modulo.ds_imagem_brs')->get();

        if($modulo != "[]"){
            return [true,$modulo];
        }else{
            return [false,$modulo];
        }

    }

    public function modulosExternos(){
        return Modulo::where('cd_tipo_modulo',TipoModulo::TIPO_EXTERNO)->get();
    }


    public function dashboardAdministracao()
    {
        return view('dashboard.administracao');
    }

    public function dashboardSolicitacao()
    {
        return view('dashboard.solicitacao');
    }

    public function dashboardAvaliacao()
    {
        return view('dashboard.avaliacao');
    }

    public function dashboardSaa()
    {

        $matricula = Auth::user()->nr_matricula;
        //Verifica se user tem a permissão APROVADO(GERIT)
        $perfil = UsuarioPerfil::where('nr_matricula', $matricula)->where('sq_permissao', 181)->get();

        if($perfil != "[]"){
            $valorperfil = true;
        }else{
            $valorperfil = false;
        }

        return view('dashboard.saa', compact('valorperfil'));
    }

    public function dashboardRelatorios()
    {
        return view('dashboard.relatorios');
    }

    public function dashboardMovimentacao()
    {
        return view('dashboard.movimentacao');
    }

    public function dashboardBancoTalentos()
    {
        return view('dashboard.banco-talentos');
    }

    public function SessaoAtividade(Request $request){

        $dataAtual = date('Y-m-d H:i:s');
       if($request->has('gestor')){
        $uniao = Atividade::query();
                $uniao->join(
                   'prh.tb_ciclo_avaliativo',
                   'prh.tb_atividade.sq_ciclo_avaliativo',
                   'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
                  )->where('cd_situacao_atividade', $request->cd_situacao_atividade)->where('dt_fim_periodo_acomp_atvd', '>=', $dataAtual);
                      $consulta = $uniao->get();
                 if(session()->has('sessaoAtividade')){
                  session()->flush('sessaoAtividade');
                 }

              session()->put('sessaoAtividade', $consulta);
              return redirect(route('atividade'));

       }else {
        $uniao = Atividade::query();
        $uniao->join(
          'prh.tb_atividade_responsavel',
          'prh.tb_atividade.sq_atividade',
          'prh.tb_atividade_responsavel.sq_atividade'
      )->join(
           'prh.tb_ciclo_avaliativo',
           'prh.tb_atividade.sq_ciclo_avaliativo',
           'prh.tb_ciclo_avaliativo.sq_ciclo_avaliativo'
          )->where('dt_fim_periodo_acomp_atvd', '>=' , $dataAtual)->where('nr_matricula', Auth::user()->nr_matricula)->where('cd_situacao_atividade', $request->cd_situacao_atividade);
          $consulta = $uniao->get();


          if(session()->has('sessaoAtividade')){
            session()->flush('sessaoAtividade');
          }

          session()->put('sessaoAtividade', $consulta);
          return redirect(route('atividade'));
       }
    }

}
