<?php

namespace App\Helpers;
use Carbon\Carbon;
use Modules\Usuario\Entities\Treinamento\SituacaoMatricula;

class Helper
{

    public static function toDateTime($date, $timestamp = false)
    {
        if(!is_null($date)){
            $dateTime = new \DateTime($date);
            if($timestamp){
                $dataFormat = $dateTime->format('d/m/Y H:m:i');
            }else{
                $dataFormat = $dateTime->format('d/m/Y');
            }
            return $dataFormat;
        }else{
            return "-";
        }

    }

    public static function vencimentoTratamento($matricula){
        if(($matricula->situacaoMatricula->idSituacaoMatricula == SituacaoMatricula::APROVADO) && ($matricula->turma->evento->validade != 0)){
            return date('d/m/Y', strtotime($matricula->conclusao . " +1 year") );;
        }else{
            return "-";
        }
    }

    /**
     * @param $date
     * @return string
     * @throws \Exception
     */
    public static function convertSaveDateFormat($date)
    {
        $date = explode('/', $date);
        $dateFormat = $date[2] . '-' . $date[1] . '-' . $date[0];
        $newDateFormat = new \DateTime($dateFormat);
        $saveDateFormat = $newDateFormat->format('Y-m-d');
        return $saveDateFormat;
    }

    /**
     * @param $num
     * @return float
     */
    public static function converterMoedaFormatoOracle($num)
    {

        $dotPos = strrpos($num, '.');
        $commaPos = strrpos($num, ',');
        $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
            ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

        if (!$sep) {
            return floatval(preg_replace("/[^0-9]/", "", $num));
        }

        return floatval(preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' . preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
        );
    }

    /**
     * @param $num
     * @return string
     */
    public static function converterFormatoMoeda($num)
    {
        return number_format($num, 2, ',', '.');
    }

    /**
     * @param $value
     * @return array|string|string[]|null
     */
    public static function converterSomenteNumeros($value)
    {
        return (int)preg_replace("/[^0-9]/", "", $value);
    }

    /**
     * @param $value1
     * @param $value2
     * @param $operation
     * @param false $formatCurrencyBr
     * @return float|string
     * Função que realiza cálculos de valores
     * de acordo com a operação informada
     * Operações disponíveis:
     * (+) Soma
     * (-) Subtração
     * (*) Multiplicação
     * (/) Divisão
     * Caso deseje o retorno formatado em Moeda (R$) passar true no quarto parâmetro
     */
    public static function calcularValoresPorTipoOperacao($value1, $value2, $operation, bool $formatCurrencyBr = false) {

        $v1 = Self::converterMoedaFormatoOracle($value1);
        $v2 = Self::converterMoedaFormatoOracle($value2);

        switch ($operation) {
            case "+":
                $r = $v1 + $v2;
                break;
            case "-":
                $r = $v1 - $v2;
                break;
            case "*":
                $r = $v1 * $v2;
                break;
            case "/":
                if($v2 > 0) {
                    $r = $v1 / $v2;
                }
                break;
        }
        if($formatCurrencyBr){
            $ret = number_format($r,2,",",".");
        }else{
            $ret = round($r, 2);
        }

        return $ret;
    }


    public static function cleanChar($string) {
        return preg_replace('/\D/', '', $string);
    }

    public static function telefoneFormat($tel) {
        $tel = Self::cleanChar($tel);
        if(empty($tel)){
            return " Nenhum";
        }
        $telcheck = substr($tel, 0, 4);
        if (strpos($telcheck, '0300') || strpos($telcheck, '0800')) {
            preg_match('/(\d{4})(\d{3})(\d{4})/', $tel, $matches);

            return $matches[1] . " " . $matches[2] . " " . $matches[3];
        }
        if (strpos($tel, '4007')) {
            preg_match('/(\d{4})(\d{4})/', $tel, $matches);

            return $matches[1] . "-" . $matches[2];
        }
        $telcheck = substr($tel, 2, 1);
        if (strpos($tel, '9')) {
            preg_match('/(\d{2})(\d{5})(\d{4})/', $tel, $matches);
            return "(" . $matches[1] . ") " . $matches[2] . "-" . $matches[3];
        }
        preg_match('/(\d{2})(\d{4})(\d{4})/', $tel, $matches);
        return "(" . $matches[1] . ") " . $matches[2] . "-" . $matches[3];
    }

    public static function formatCnpjCpf($value)
    {
        $cnpj_cpf = preg_replace("/\D/", '', $value);

        if (strlen($cnpj_cpf) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
        }

        return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }


    public static function formatNumberPercentual($value, $decimal = false)
    {
        $number = $value;
        if($decimal){
            $number = $value * 100;
        }

        return Self::converterFormatoMoeda($number);

    }

    public static function formatStringStatus($value)
    {
        if($value == 'N'){
            return 'Não';
        }

        return 'Sim';
    }

    //SOMAR HORAS
    public static function somarHoras($array, $isArray = false){

        $i = 0;

        foreach ($array as $time) {

            if(!$isArray){
                $valor = $time->turma->carga;
            }else{
                $valor = $time;
            }

            sscanf($valor, '%d:%d', $hour, $min);
            $i += $hour * 60 + $min;

        }

        if ($h = floor($i / 60)) {
            $i %= 60;
        }

        return sprintf('%02d:%02d', $h, $i);

    }

    public static function verificaCertificado($matricula){

        if($matricula->turma->evento->tipoEvento->idTipoEvento < 10){
            return "";
        }else{
            return "d-none";
        }

    }

    public static function verificaTipoCertificado($matricula){

        if($matricula->turma->tipoTurma->idtipoTurma == 1 && $matricula->fkSituacaoMatricula == 4){
            return "/certificado";
        }

        if($matricula->turma->tipoTurma->idtipoTurma > 1 && $matricula->fkArquivo != null){
            return "/arquivo";
        }

    }

    public static function converteDataParaDescritiva($data){
        setlocale(LC_TIME, 'ptb');
        return explode(" " , Carbon::createFromFormat('Y-m-d H:i:s',$data)->formatLocalized('%d %B %Y'));
    }

    public static function getConstanteBancoBennerAmbiente()
    {
        $constanteBanco = null;
        $app_env = env('APP_ENV');

        if ($app_env == 'local' || $app_env == 'dsv') {
            $constanteBanco = 'rhdesenvolvimento';
        } elseif ($app_env == 'hmo' || $app_env == 'HMO'){
            $constanteBanco = 'rh_homologacao';
        } else {
            $constanteBanco = 'rhproducao';
        }
        return $constanteBanco;
    }

    public static function getUrlPafAmbiente() {
        $url = null;
        $app_env = env('APP_ENV');

        if ($app_env == 'local' || $app_env == 'dsv' || $app_env == 'DSV') {
            $url = 'http://pafdsv.brb.com.br';
        } elseif ($app_env == 'hmo' || $app_env == 'HMO'){
            $url = 'http://pafhmo.brb.com.br';
        } else {
            $url = 'http://paf.brb.com.br';
        }
        return $url;
    }

}
