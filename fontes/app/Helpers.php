<?php

use Carbon\Carbon;
use App\Entities\Benner\AreaHierarquica;
use Illuminate\Support\Facades\Auth;
use Modules\Perfil\Entities\Permissao;
use Modules\Atividade\Entities\Atividade;
use Modules\EquipeAvaliacao\Entities\MembroEquipeAvaliacao;

if (!function_exists('version')) {
    function version($file = null) {
        if ($file === null) {
            $file = "pom.xml";
        }
        $xml_path = base_path($file);
        $xml = simplexml_load_file($xml_path);
        $version = str_replace("-SNAPSHOT", "", ($xml->version));
        return $version;
    }
}

if (!function_exists('retornaData')) {
    function retornaData($days) {

        $years = intval($days / 365);
        $days = $days % 365;

        $months = intval($days / 30);
        $days = $days % 30;

        if($years > 0 && $months > 0){
            return "{$years} Ano(s) e {$months} Meses";
        }elseif($months > 0 && $years == 0){
            if($months == 1){
                return "{$months} Mes";
            }else{
                return "{$months} Meses";
            }

        }elseif($days > 0){
            return "{$days} Dias";
        }else{
            return "Valor Indisponivel";
        }


    }
}

if(!function_exists('dataInicioReplica')){
    function dataInicioReplica($data){

        $data = Carbon::createFromFormat('d/m/Y', $data)->format('Y-m-d');
        $data = Carbon::parse($data)->addYears(5)->format('d/m/Y');
        return $data;
    }
}


if(!function_exists('daysBetweenDates')){
    function daysBetweenDates($dataFim){

        $dataFim = explode(" ",$dataFim)[0];
        $dataInicio = explode(" ",Carbon::now())[0];
        $date = Carbon::parse($dataFim);
        $diasFimLicenca = $date->diffInDays($dataInicio);
        return $diasFimLicenca;

    }
}

if(!function_exists('daysBetweenTwoDates')){
    function daysBetweenTwoDates($dataInicio, $dataFim){
        
        $dataFim = explode(" ",$dataFim)[0];
        $dataInicio = explode(" ",$dataInicio)[0];
        $date = Carbon::parse($dataFim);
        $dias = $date->diffInDays($dataInicio);
        return $dataFim == "" ? 0 : $dias;

    }
}

if(!function_exists('recuperaEscala')){
    function recuperaEscala($id,$perspectiva){

       $escala = \Modules\CicloAvaliativo\Entities\CicloAvaliativoPerspectiva::where('sq_ciclo_avaliativo',$id)->where('cd_perspectiva',$perspectiva)->first();
       if(!empty($escala)){
            return $escala->sq_escala;
       } 

    }
}

if(!function_exists('permiteUpload')){
    function permiteUpload($solicitacao,$perfil){

       $permissao = Permissao::SOLICITACAO_APROVADOR_CESEP;
       if(($solicitacao->cd_dependencia_empresa_rh == AreaHierarquica::CESEP && in_array($permissao,$perfil) && $solicitacao->sq_permissao == $permissao )
        ||($solicitacao->cd_dependencia_empresa_rh == AreaHierarquica::GEREP && in_array(264,$perfil))){
            return true;
       }else{
            return false;
       } 

    }
}

if(!function_exists('reduzTamanhoNome')){
    function reduzTamanhoNome($nome){

        if(strlen($nome) > 15){
            return substr($nome, 0, 15) . "...";
        }
        // Senão exibi o texto completo
        else{
            return $nome;
        }

    }
}

if(!function_exists('formataDataHora')){
    function formataDataHora($data){
        return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('d/m/Y - H:i:s');
    }
}

if(!function_exists('retornaEmailUsuarioLogado')){
    function retornaEmailUsuarioLogado()
    {
        return 'u' . Auth::user()->nr_matricula . '@brb.com.br';
    }
}

if(!function_exists('formataData')){
    function formataData($data){
        return Carbon::createFromFormat('Y-m-d H:i:s', $data)->format('d/m/Y');
    }
}

if(!function_exists('verificaMembroAtivo')){
    function verificaMembroAtivo($sq_equipe_avaliacao,$membro,$tipoAvaliacao){
        $nr_matricula = 0;

        if($tipoAvaliacao != 3){
            $nr_matricula = $membro->nr_matricula;
        }else{
            $nr_matricula = $membro->nr_matricula_gestor;
        }
        return MembroEquipeAvaliacao::where('nr_matricula',$nr_matricula)->where('sq_equipe_avaliacao',$sq_equipe_avaliacao)->where('st_registro_ativo','S')->get()->count();

    }
}

if(!function_exists('verificaTipoAtividade')){
    function verificaTipoAtividade($tipo_atividade){
        
        if($tipo_atividade == Atividade::ATIVIDADE_VINCULADA){
            return "Estratégica";
        }else{
            return "Operacional";
        }
    }
}




