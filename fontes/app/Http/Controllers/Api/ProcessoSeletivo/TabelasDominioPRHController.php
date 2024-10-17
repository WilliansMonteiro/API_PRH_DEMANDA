<?php

namespace App\Http\Controllers\Api\ProcessoSeletivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProcessoSeletivo\Http\Services\TabelasDominioPRHService;

class TabelasDominioPRHController extends Controller
{
    private $service;

    public function __construct(TabelasDominioPRHService $service)
    {
        $this->service = $service;
    }

    public function getAreas()
    {
        $areas = $this->service->areas();
        return $areas;
    }

    public function getFuncoes()
    {
        $funcoes = $this->service->funcoes();
        return $funcoes;
    }

    public function getStatusInscricoes()
    {
        $status = $this->service->statusInscricoes();
        return $status;
    }
}
