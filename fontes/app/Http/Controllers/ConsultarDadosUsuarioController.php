<?php

namespace App\Http\Controllers;

use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Usuario\Entities\Usuario;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Modules\Perfil\Entities\PerfilAcesso;
use Modules\Perfil\Entities\Permissao;
use Modules\Modulo\Entities\Modulo;
use Modules\SAA\Entities\VwDependencia;
use App\Http\Services\ConsultarDadosUsuarioService;



class ConsultarDadosUsuarioController extends Controller
{

    private $service;

    public function __construct(ConsultarDadosUsuarioService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $permissao_adm_nuadm        = Permissao::IsAdministradorNUADM();
        $permissao_adm_saa          = Permissao::IsAdministradorSAA();
        $permissao_aprovador_gerit  = Permissao::IsAprovadorGerit();
        $permissao_solicitante      = Permissao::IsSolicitanteSAA();
        $permissao_gestor_saa       = Permissao::IsGestorSAA();
        $permissao_portaria_cms     = Permissao::IsPortariaCMS();
        $permissao_consulta_global  = Permissao::IsConsultaGlobal();
        $lotacao                    = VwDependencia::all();

        // dd($permissao_adm_nuadm, $permissao_adm_saa, $permissao_aprovador_gerit, $permissao_solicitante);
        return view('consultar-dados-usuario.index', compact('permissao_adm_nuadm', 'permissao_adm_saa', 'permissao_aprovador_gerit', 'permissao_solicitante', 'permissao_gestor_saa', 'permissao_portaria_cms', 'lotacao', 'permissao_consulta_global'));
    }


    public function search(Request $request)
    {
        return $this->service->search_dados_usuarios($request);
    }






}
