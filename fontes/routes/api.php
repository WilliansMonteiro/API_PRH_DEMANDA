<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Grupo de rotas de autenticação de usuário na API */
Route::group(['prefix' => 'auth'], function() {
    Route::post('/login', 'Api\AuthController@login');
    Route::post('/logout', 'Api\AuthController@logout')->middleware('apiJwt');
});

/* APIs utilizadas pela Docspider */
Route::group(['middleware'=> ['apiJwt']], function(){
    Route::get('/consultaFuncionario', 'Api\FuncionarioController@search');
    Route::get('/consultaDependencia', 'Api\DependenciaController@search');
    Route::get('/consultaEmpresa', 'Api\EmpresaController@search');
});

Route::group(['prefix' => 'funcionario','middleware'=> ['apiJwt']], function(){
    Route::get('/consultaAfastamentos', 'Api\FuncionarioController@searchDadosAfastamento');
});

Route::group(['prefix' => 'prestador','middleware'=> ['apiJwt']], function(){
    Route::get('/search', 'Api\SaaApiController@search');
    Route::get('/searchFuncionario', 'Api\SaaApiController@searchCpfMatricula');
});


/* Grupo de rotas de usuário */
Route::group(['prefix' => 'usuario','middleware'=> ['apiJwt']], function(){
    Route::get('/search', 'Api\UsuarioController@search');
    Route::put('/changePassword', 'Api\UsuarioController@update');
    Route::post('/create', 'Api\UsuarioController@store');
});

Route::group(['prefix' => 'api-processo-seletivo'], function(){
    Route::get('/search-processo-seletivo', 'Api\ProcessoSeletivo\ProcessoSeletivoController@search');
    Route::get('/get-processo-seletivo', 'Api\ProcessoSeletivo\ProcessoSeletivoController@getProcessoSeletivo');
    Route::get('/get-formulario-inscricao', 'Api\ProcessoSeletivo\InscricaoController@getFormularioInscricao');
    Route::post('/create-inscricao', 'Api\ProcessoSeletivo\InscricaoController@store');
    Route::get('/get-areas', 'Api\ProcessoSeletivo\TabelasDominioPRHController@getAreas');
    Route::get('/get-funcoes', 'Api\ProcessoSeletivo\TabelasDominioPRHController@getFuncoes');
    Route::get('/get-status-inscricoes', 'Api\ProcessoSeletivo\TabelasDominioPRHController@getStatusInscricoes');
    // Route::get('/search-inscricoes-usuario', 'Api\ProcessoSeletivo\InscricaoController@search');
    Route::post('/search-inscricoes-usuario', 'Api\ProcessoSeletivo\InscricaoController@search');
    Route::get('/get-inscricao', 'Api\ProcessoSeletivo\InscricaoController@getInscricao');
    Route::get('/compara-dados-benner-inscricao', 'Api\ProcessoSeletivo\InscricaoController@comparaDadosBannerInscricao');
    Route::get('/get-formulario-inscricao-respostas', 'Api\ProcessoSeletivo\InscricaoController@formularioRespondido');
    Route::post('/get-inscricoes-ativas-usuario', 'Api\ProcessoSeletivo\ProcessoSeletivoController@getInscricoesAtivasUsuario');
    Route::post('/cancela-inscricao', 'Api\ProcessoSeletivo\InscricaoController@cancel');
    Route::post('/get-area-usuario-benner', 'Api\ProcessoSeletivo\ProcessoSeletivoController@getAreaUsuarioBenner');
    Route::post('/solicita-recurso-inscricao', 'Api\ProcessoSeletivo\RecursoInscricaoController@solicitaRecursoInscricao');
});

Route::group(['prefix' => 'usuario-api'], function(){
    Route::post('/valida-usuario-ldap', 'Api\LdapAuthController@authLdapUser');
});


Route::group(['prefix' => 'api-usuario-mgc'], function(){
    Route::get('/search-usuario', 'Api\PAF\CorregedoriaController@search');
});


Route::group(['prefix' => 'api-paf-adm'], function(){
    Route::get('/search-usuario', 'Api\PAF\AdmController@search');
});

Route::group(['prefix' => 'contra-cheque', 'middleware' => ['apiJwt']], function(){
    Route::get('/search-pdf', 'Api\ContraCheque\ContraChequeController@search');
    Route::get('/search-dados', 'Api\ContraCheque\ContraChequeController@searchDados');

});


Route::group(['prefix' => 'gestor-lotacao','middleware'=> ['apiJwt']], function(){
    Route::get('/search', 'Api\GestorLotacaoController@search');
});
