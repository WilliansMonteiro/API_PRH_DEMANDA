<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Area\Entities\VwEmpresaRH;
use Modules\SAA\Entities\Fundesec;
use App\Entities\Benner\Funcionario;
use Adldap\Laravel\Traits\HasLdapUser;
use Illuminate\Support\Facades\DB;


class SaaApiController extends Controller
{

    public function search(Request $request)
    {
        $nome = null;
        $empresa = null;
        $situacao = null;
        $empresa_brb = null;
        $empresa_prestadora = null;
        $matricula = null;
        $area = null;
        $cargo = null;
        $tipo = null;
       
        if($request->filled('nome')){
            $nome = $request->nome;
        }
        
        if($request->filled('empresa')){
            $empresa = $request->empresa;
        }
        
        if($request->filled('situacao')){
            $situacao = $request->situacao;
        }
        
        if($request->filled('empresa_brb')){
            $empresa_brb = $request->empresa_brb;
        }
        
        if($request->filled('empresa_prestadora')){
            $empresa_prestadora = $request->empresa_prestadora;
        }
        
        if($request->filled('matricula')){
            $matricula = $request->matricula;
        }
        
        if($request->filled('area')){
            $area = $request->area;
        }

        if($request->filled('cargo')){
            $cargo = $request->cargo;
        }
        
        if($request->filled('tipo')){
            $tipo = $request->tipo;
        }
        
        $pesquisa_api_saa = new Fundesec();
        $consulta =  $pesquisa_api_saa->consultarUsuarioApi($nome = $nome, $situacao = $situacao, $empresa = $empresa, $empresa_brb = $empresa_brb, $empresa_prestadora = $empresa_prestadora, $matricula = $matricula, $area = $area, $cargo = $cargo, $tipo = $tipo);
        
        
        
        if(empty($consulta)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => collect($consulta)]);

    }


    public function searchCpfMatricula(Request $request)
    {
        $matricula = null;
        $cpf       = null;

        $array = null;
        

        if($request->filled('matricula') && !$request->filled('cpf'))
        {
            $matricula = $request->matricula;
            
            $matricula_editada = substr($matricula, 0, -1);

            $sql = "SELECT * FROM SAA.funcdesec WHERE fdematric  = " . $matricula_editada;            
            $usuario_saa = DB::select($sql);
           
            $funcionarioBenner = new Funcionario();
            $consultaBenner = $funcionarioBenner->consultaDadosFuncionario($matricula);

            $userLdap = \Adldap::search()->users()->find('u' . $matricula);
            $array = ["BENNER" => $consultaBenner, "SAA" => $usuario_saa, "AD" => $userLdap];

        }
        if($request->filled('cpf') && !$request->filled('matricula'))
        {
            $request->cpf;
            $sql = "";
            $sql .= " SELECT * FROM SAA.funcdesec WHERE 1=1 ";    
            $sql .= " AND UPPER(fdecpf) like UPPER('%" . $request->cpf . "%') ";
       
            $usuario_saa = DB::select($sql);
            $array = ["SAA" => $usuario_saa];

        }

        
        if(empty($array)){
            return response()->json(['success' => true, 'message' => 'Nenhum registro foi encontrado!']);
        }

        return response()->json(['success' => true, 'data' => $array]);


      
    }


}
