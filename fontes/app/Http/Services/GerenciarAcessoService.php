<?php

namespace App\Http\Services;

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




class GerenciarAcessoService
{

    public function consulta_inicial_pendentes_terceiros()
    {
        $usuarios = DB::table('prh.tb_usuario_perfil up')
            ->join('prh.tb_dado_usuario_terceiro da', function ($join) {
                $join->on('up.nr_matricula', '=', 'da.nr_matricula')
                    ->where('da.st_registro_ativo', '=', 'S');
            });
        $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
            ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
            ->join('saa.funcdesec fun', DB::raw('SUBSTR(up.nr_matricula, 1, LENGTH(up.nr_matricula)-1)'), 'fun.fdematric')
            ->leftJoin('prh.vw_dependencia v', 'v.cd_empresa_dependencia', 'da.cd_area_usuario')
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
        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_SOLICITADA);
        $usuarios->orderBy('u.nr_matricula');
        $results = $usuarios->get();
        return $results;
    }


    public function consulta_inicial_acesso_terceiros()
    {

        $usuarios = DB::table('prh.tb_usuario_perfil up')
            ->join('prh.tb_dado_usuario_terceiro da', function ($join) {
                $join->on('up.nr_matricula', '=', 'da.nr_matricula')
                 ->where('da.st_registro_ativo', '=', 'S');
            });

        $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
            ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
            ->join('saa.funcdesec fun', DB::raw('SUBSTR(up.nr_matricula, 1, LENGTH(up.nr_matricula)-1)'), 'fun.fdematric')
            ->leftJoin('prh.vw_dependencia v', 'v.cd_empresa_dependencia', 'da.cd_area_usuario')
            ->join('prh.tb_perfil_acesso pe', 'p.cd_perfil_acesso', 'pe.cd_perfil_acesso')
            ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo');

        $usuarios->where('u.st_primeiro_acesso', 'N');
        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_APROVADA);
        $usuarios->orderBy('u.nr_matricula');
        $usuarios->selectRaw('distinct u.nr_matricula, CASE WHEN fun.no_social IS NULL THEN u.no_usuario ELSE fun.no_social END AS no_usuario, da.cd_area_usuario, v.sg_dependencia');


        return $usuarios->get();

    }

    public function consulta_parametrizada_terceiros($request)
    {

        $retorno = null;
        $usuarios = DB::table('prh.tb_usuario_perfil up')
        ->join('prh.tb_dado_usuario_terceiro da', function ($join) {
            $join->on('up.nr_matricula', '=', 'da.nr_matricula')
                ->where('da.st_registro_ativo', '=', 'S');
        });
         $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
        ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
        ->join('saa.funcdesec fun', DB::raw('SUBSTR(up.nr_matricula, 1, LENGTH(up.nr_matricula)-1)'), 'fun.fdematric')
        ->leftJoin('prh.vw_dependencia v', 'v.cd_empresa_dependencia', 'da.cd_area_usuario')
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


        if ($request->filled('cd_modulo_terceiros')) {
            $usuarios->where('p.cd_modulo', $request->cd_modulo_terceiros);
        }

        if ($request->filled('cd_area_usuario_terceiros')) {
            $usuarios->where('da.cd_area_usuario', $request->cd_area_usuario_terceiros);
        }

        if ($request->filled('nr_matricula_terceiros')) {
            $usuarios->where('u.nr_matricula', $request->nr_matricula_terceiros);
        }

        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_SOLICITADA);
        $usuarios->orderBy('u.nr_matricula');
        $retorno = $usuarios->get();
        return $retorno;
    }




    public function consulta_parametrizada_acesso_terceiros($request)
    {

        $usuarios = DB::table('prh.tb_usuario_perfil up')
            ->join('prh.tb_dado_usuario_terceiro da', function ($join) {
                $join->on('up.nr_matricula', '=', 'da.nr_matricula')
                 ->where('da.st_registro_ativo', '=', 'S');
            });

        $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
            ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
            ->join('saa.funcdesec fun', DB::raw('SUBSTR(up.nr_matricula, 1, LENGTH(up.nr_matricula)-1)'), 'fun.fdematric')
            ->leftJoin('prh.vw_dependencia v', 'v.cd_empresa_dependencia', 'da.cd_area_usuario')
            ->join('prh.tb_perfil_acesso pe', 'p.cd_perfil_acesso', 'pe.cd_perfil_acesso')
            ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo');


        if ($request->filled('cd_modulo_terceiros')) {
            $usuarios->where('m.cd_modulo', $request->get('cd_modulo_terceiros'));
        }

        if ($request->filled('cd_area_usuario_terceiros')) {
            $usuarios->where('da.cd_area_usuario', $request->get('cd_area_usuario_terceiros'));
        }

        if ($request->filled('nr_matricula_terceiros')) {
            $usuarios->where('u.nr_matricula', $request->get('nr_matricula_terceiros'));
        }

        $usuarios->where('u.st_primeiro_acesso', 'N');
        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_APROVADA);
        $usuarios->orderBy('u.nr_matricula');
        $usuarios->selectRaw('distinct u.nr_matricula, CASE WHEN fun.no_social IS NULL THEN u.no_usuario ELSE fun.no_social END AS no_usuario, da.cd_area_usuario, v.sg_dependencia');

        return $usuarios->get();
    }


    public function searchPendentesTerceiros(Request $request)
    {
        $retorno = $this->consulta_parametrizada_terceiros($request);
        $resultado = "";
        $resultado .="<table class='table table-striped projects' id='minhaTabela'>";
        $resultado .="<thead class='bg-primary'>";
            $resultado .="<tr>";
                $resultado .="<th class='text-center'>Matrícula</th>";
                $resultado .="<th class='text-center'>Nome terceiro</th>";
                $resultado .="<th class='text-center'>Área</th>";
                $resultado .="<th class='text-center'>Módulo Solicitado</th>";
                $resultado .="<th class='text-center'>Ações</th>";
            $resultado .="</tr>";
        $resultado .="</thead>";
        $resultado .="<tbody>";

        if($retorno != null){

            foreach($retorno as $linha){
                $nome = $linha->no_social == null ? $linha->no_usuario : $linha->no_social;

                $resultado .="<tr>";
                $resultado .="<td class='text-center'> {$linha->nr_matricula} </td>";
                $resultado .="<td class='text-center'> {$nome} </td>";
                $resultado .="<td class='text-center'> {$linha->sg_dependencia} </td>";
                $resultado .="<td class='text-center'> {$linha->ds_modulo} </td>";
                $resultado .="<td class='text-center'><a class='btn btn-primary btn-sm' href='".route('informacoes', $linha->sq_usuario_perfil)."'><i class='fas fa-folder' data-toggle='tooltip' data-placement='top' title='Informações do usuário'></i></a>";
                $resultado .="<a class='btn btn-success btn-sm' href='".route('aprovar', $linha->sq_usuario_perfil)."'><i class='fas fa-thumbs-up' data-toggle='tooltip' data-placement='top' title='Aprovar solicitação de acesso'></i></a>";
                $resultado .="<a class='btn btn-danger btn-sm' href='".route('reprovar', $linha->sq_usuario_perfil)."'><i class='fas fa-thumbs-down' data-toggle='tooltip' data-placement='top' title='Reprovar solicitação de acesso'></i></a></td>";
                $resultado.="</tr>";
            }

        } else {

          $resultado.="<tr>";
          $resultado.="<th>";
          $resultado.="<td></td>";
          $resultado.="<td class='text-center'>Nenhum registro encontrado</td>";
          $resultado.="<td></td>";
          $resultado.="<td></td>";
          $resultado.="<td></td>";
          $resultado.="</th>";
          $resultado.="</tr>";

        }
        $resultado .="</tbody>";
        $resultado .="</table>";

        echo $resultado;


    }



    public function searchAcessoTerceiros(Request $request)
    {

        $retorno = $this->consulta_parametrizada_acesso_terceiros($request);
        $resultado = "";
        $resultado .="<table class='table table-striped projects' id='minhaTabelaTerceirosAcesso'>";
        $resultado .="<thead class='bg-primary'>";
            $resultado .="<tr>";
                $resultado .="<th class='text-center'>Matrícula</th>";
                $resultado .="<th class='text-center'>Nome terceiro</th>";
                $resultado .="<th class='text-center'>Área</th>";
                $resultado .="<th class='text-center'>Ações</th>";
            $resultado .="</tr>";
        $resultado .="</thead>";
        $resultado .="<tbody>";

        if($retorno != null){

            foreach($retorno as $linha){
                $resultado .="<tr>";
                $resultado .="<td class='text-center'> {$linha->nr_matricula} </td>";
                $resultado .="<td class='text-center'> {$linha->no_usuario} </td>";
                $resultado .="<td class='text-center'> {$linha->sg_dependencia} </td>";
                $resultado .="<td class='text-center'><a class='btn btn-primary btn-sm' href='".route('informacoesPerfis', $linha->nr_matricula . '/1')."'><i class='fas fa-folder' data-toggle='tooltip' data-placement='top' title='Informações do usuário'></i></a>";
                $resultado .="<a class='btn btn-success btn-sm' href='".route('adicionarPerfil', $linha->nr_matricula)."'><i class='fas fa-user-plus' data-toggle='tooltip' data-placement='top' title='Adicionar perfil'></i></a></td>";
                $resultado.="</tr>";
            }

        } else {

          $resultado.="<tr>";
          $resultado.="<th>";
          $resultado.="<td></td>";
          $resultado.="<td class='text-center'>Nenhum registro encontrado</td>";
          $resultado.="<td></td>";
          $resultado.="<td></td>";
          $resultado.="<td></td>";
          $resultado.="</th>";
          $resultado.="</tr>";

        }
        $resultado .="</tbody>";
        $resultado .="</table>";

        return array("table" => $resultado, "usuarios" => $retorno);

    }


    public function consulta_inicial_acesso_brb()
    {
        $usuarios = DB::table('prh.tb_usuario_perfil up')

        ->Join('prh.tb_dado_usuario_benner da', function ($join) {
            $join->on('up.nr_matricula', '=', 'da.nr_matricula')
             ->where('da.st_registro_ativo', '=', 'S');
        });

    $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
        ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
        ->join('prh.tb_perfil_acesso pe', 'p.cd_perfil_acesso', 'pe.cd_perfil_acesso')
        ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo');

       $usuarios->where('u.st_primeiro_acesso', 'N');
       $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_APROVADA);
       $usuarios->orderBy('u.nr_matricula');
       $usuarios->selectRaw('distinct u.nr_matricula, u.no_usuario, da.ds_area_benner');
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


       return $results;

    }

    public function consulta_parametrizada_controle_brb($request)
    {
        $usuarios = DB::table('prh.tb_usuario_perfil up')

            ->Join('prh.tb_dado_usuario_benner da', function ($join) {
                $join->on('up.nr_matricula', '=', 'da.nr_matricula')
                 ->where('da.st_registro_ativo', '=', 'S');
            });

        $usuarios->join('prh.tb_permissao p', 'up.sq_permissao', 'p.sq_permissao')
            ->join('prh.tb_usuario u', 'up.nr_matricula', 'u.nr_matricula')
            ->join('prh.tb_perfil_acesso pe', 'p.cd_perfil_acesso', 'pe.cd_perfil_acesso')
            ->join('prh.tb_modulo m', 'p.cd_modulo', 'm.cd_modulo');

        if ($request->filled('cd_modulo')) {
            $usuarios->where('M.cd_modulo', $request->cd_modulo);
        }

        if ($request->filled('ds_area')) {
            $usuarios->where('da.ds_area_benner', $request->get('ds_area'));
        }

        if ($request->filled('nr_matricula')) {
            $usuarios->where('u.nr_matricula', $request->nr_matricula);
        }
        $usuarios->where('u.st_primeiro_acesso', 'N');
        $usuarios->where('up.st_solicitacao', UsuarioPerfil::SOLICITACAO_STATUS_APROVADA);
        $usuarios->orderBy('u.nr_matricula');
        $usuarios->selectRaw('distinct u.nr_matricula, u.no_usuario, da.ds_area_benner');
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

        return $results;

    }


    public function search_controle_brb(Request $request)
    {
        $retorno = $this->consulta_parametrizada_controle_brb($request);
        $resultado = "";
        $resultado .="<table class='table table-striped projects' id='tabela_controle_parametrizado_brb'>";
        $resultado .="<thead>";
            $resultado .="<tr>";
                $resultado .="<th class='text-center'>Matrícula</th>";
                $resultado .="<th class='text-center'>Nome</th>";
                $resultado .="<th class='text-center'>Área</th>";
                $resultado .="<th class='text-center'>Ações</th>";
            $resultado .="</tr>";
        $resultado .="</thead>";
        $resultado .="<tbody>";

        if($retorno != null){

            foreach($retorno as $linha){
                $resultado .="<tr>";
                $resultado .="<td class='text-center'> {$linha->nr_matricula} </td>";
                $resultado .="<td class='text-center'> {$linha->no_usuario} </td>";
                $resultado .="<td class='text-center'> {$linha->ds_area_benner} </td>";
                $resultado .="<td class='text-center'><a class='btn btn-primary btn-sm' href='".route('informacoesPerfis', $linha->nr_matricula . '/1')."'><i class='fas fa-folder' data-toggle='tooltip' data-placement='top' title='Informações do usuário'></i></a>";
                $resultado .="<a class='btn btn-success btn-sm' href='".route('adicionarPerfil', $linha->nr_matricula)."'><i class='fas fa-user-plus' data-toggle='tooltip' data-placement='top' title='Adicionar perfil'></i></a></td>";
                $resultado.="</tr>";
            }

        } else {

          $resultado.="<tr>";
          $resultado.="<th>";
          $resultado.="<td></td>";
          $resultado.="<td class='text-center'>Nenhum registro encontrado</td>";
          $resultado.="<td></td>";
          $resultado.="<td></td>";
          $resultado.="<td></td>";
          $resultado.="</th>";
          $resultado.="</tr>";

        }
        $resultado .="</tbody>";
        $resultado .="</table>";

        return array("table" => $resultado, "usuarios" => $retorno);

    }






}
