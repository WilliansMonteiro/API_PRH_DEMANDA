<?php

namespace App\Http\Controllers;

use App\Entities\Benner\Funcionario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Usuario\Entities\Usuario;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class ImportUsuarioController extends Controller
{
    public function index()
    {

        $response = new \stdClass();

        if(Auth::user()->isSuperAdmin()){
            $usuario = new Usuario();
            $import = $usuario->saveImportacaoBaseBenner();

            if($import){
                if(count($import->matriculasImportadas) == 0){
                    $response->status = true;
                    $response->msg = 'Nenhum registro a ser importado!';
                }else{
                    $response->status = true;
                    $response->msg = 'Importação realizada com sucesso. Total de registros importados: '.count($import->matriculasImportadas);
                }
            }else{
                $response->status = false;
                $response->msg = 'Erro ao tentar importar. Tente novamente!';
            }
        }else{
            $response->status = false;
            $response->msg = 'Você não possui permissão para executar a importação!';
        }

        return response()->json($response);

    }

    public function cronImport()
    {
        (new Usuario())->saveImportacaoBaseBenner();
    }
}
