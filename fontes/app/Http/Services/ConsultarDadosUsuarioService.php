<?php

namespace App\Http\Services;

use App\Entities\Benner\DadoUsuarioBenner;
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
use Modules\SAA\Entities\Fundesec;
use Modules\Usuario\Entities\DadoUsuarioTerceiro;




class ConsultarDadosUsuarioService
{



    public function search_dados_usuarios(Request $request)
    {

        $cd_dependencia = null;
        $permissao_gestor_saa = Permissao::IsGestorSAA();
        if($permissao_gestor_saa === 1)
        {
            $matriculaFormatada   = substr(Auth::user()->nr_matricula, 0, -1);
            $usuario_saa          = Fundesec::where('fdematric', '=', $matriculaFormatada)->first();
            $cd_dependencia       = $usuario_saa->dep_sequencial;
        }


        $retorno = null;
        $sql = "";
        $sql.=" SELECT f.fdenome,
        f.fdematric,
        f.fdedgv,
        SUBSTR(fdecpf, 1, 3) || '.' ||  SUBSTR(fdecpf, 4, 3) || '.' || SUBSTR(fdecpf, 7, 3) || '-' || SUBSTR(fdecpf, 10) AS fdecpf_formatado,
        f.fdecpf,
        f.fdesituacao,
        f.fdefiliacaomae,
        r.cardescricao,
        c.fncdescricao,
        p.pstnome,
        emp.nm_empresa,
        dep.sg_dependencia,
        tp.ds_tipo_empregado,
        f.dt_nascimento,
        dep.nm_dependencia
        FROM SAA.funcdesec f
        left join SAA.funcdesecbrb b
        on f.fdematric = b.fdematric
        left join SAA.funcoes c
        on b.fnccodigo = c.fnccodigo
        left join SAA.cargos r
        on f.carcodigo = r.carcodigo
        left join SAA.fundesecpres d
        on f.fdematric = d.fdematric
        left join SAA.prestadora p
        on d.pstcodigo = p.pstcodigo
        left join SAA.vw_empresa_brb emp
        on f.dep_empresa = emp.cd_empresa
        left join ODS.tb_dependencia dep
        on f.dep_empresa = dep.cd_empresa
        and f.dep_sequencial = dep.cd_dependencia
        left join SAA.tb_tipo_empregado tp
        on f.fdetipo = tp.cd_tipo_empregado
        where 1=1 ";

        if($request->filled('cd_dependencia')){
            $sql.= "AND f.dep_sequencial = ". $request->cd_dependencia;
        }

        if($permissao_gestor_saa > 0) {
            $sql.= "AND f.dep_sequencial = ". $cd_dependencia;
        }

        if($request->filled('nome')) {
            $nome = $request->nome;
            $fdenome = " ('%$nome%') ";
            $sql.= "AND UPPER(f.fdenome) LIKE UPPER" . $fdenome;
        }

        if($request->filled('st_situacao')) {
            $situacao = $request->st_situacao;
            $sql.= "AND f.fdesituacao = " .$situacao;
        }

        if($request->filled('matricula')) {
            $matricula              = $request->matricula;
            $matriculaFormatada     = substr($matricula, 0, -1);
            $ultimo_digito          = substr($matricula, -1);
            $sql.= "AND f.fdematric = " .$matriculaFormatada;
            $sql.= "AND f.fdedgv    = " .$ultimo_digito;
        }

        $retorno = DB::select($sql);
        return $retorno;
    }




}
