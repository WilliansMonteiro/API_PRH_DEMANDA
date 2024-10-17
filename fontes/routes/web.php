<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([
    'reset' => false,
    'verify' => false,
    'register' => false,
]);
Route::get('/', function () {
    return redirect('/login');
});

Route::group(['prefix' => 'developer', 'middleware' => ['auth', 'superadmin']], function () {
    Route::get('/', 'ManutencaoController@index')->name('manutencaoSistema');
    Route::post('/', 'ManutencaoController@exec')->name('executarComando');   
});

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'solicitacaoAcesso', 'middleware' => ['auth']], function () {
    Route::get('/', 'SolicitacaoAcessoController@index')->name('solicitaAcesso');
    Route::post('/saveSolicitaAcesso', 'SolicitacaoAcessoController@saveSolicitaAcesso')->name('saveSolicitaAcesso');
    Route::post('/getPerfilByModulo', 'SolicitacaoAcessoController@getPerfilByModulo')->name('getPerfilByModulo');
});

Route::group(['prefix' => 'consultarDadosUsuario', 'middleware' => ['auth']], function () {
    Route::get('/', 'ConsultarDadosUsuarioController@index')->name('consultarDadosUsuarioPRH');
    Route::post('/search-dados-usuarios', 'ConsultarDadosUsuarioController@search')->name('search-dados-usuarios-prh');

});

Route::group(['prefix' => 'import'], function () {
    Route::post('/usuario', 'ImportUsuarioController@index')->name('importUsuario');
});

Route::group(['prefix' => 'gerenciarAcesso', 'middleware' => ['gestoracesso']], function () {
    Route::get('/', 'GerenciarAcessoController@index')->name('gerenciarAcesso');
    // Route::get('/terceiros', 'GerenciarAcessoController@indexTerceiros')->name('gerenciarAcessoTerceiros');

    Route::post('/pesquisar/{cd_modulo?}/{ds_area?}/{nr_matricula?}', 'GerenciarAcessoController@search')->name('pesquisarGerenciarAcesso');

    Route::post('/pesquisarTerceiros', 'GerenciarAcessoController@searchPendentesTerceiros')->name('pesquisarGerenciarAcessoTerceiros');
    Route::post('/getControleAcessoTerceiros', 'GerenciarAcessoController@searchAcessoTerceiros')->name('getControleAcessoTerceiros');



    Route::post('/getControleAcesso', 'GerenciarAcessoController@getControleAcesso')->name('getControleAcesso');
    Route::post('/getSolicitacoesPendentes', 'GerenciarAcessoController@getSolicitacoesPendentes')->name('getSolicitacoesPendentes');

    // Route::post('/getSolicitacoesPendentesTerceiros', 'GerenciarAcessoController@getSolicitacoesPendentes')->name('getSolicitacoesPendentesTerceiros');


    Route::get('/informacoes/{sq_usuario_perfil?}', 'GerenciarAcessoController@show')->name('informacoes');
    Route::post('/informacoesAcessosUsuarios', 'GerenciarAcessoController@infoAcessosUsuarios')->name('infoAcessosUsuarios');
    Route::get('/informacoesPerfis/{nr_matricula?}/{action?}', 'GerenciarAcessoController@showPerfis')->name('informacoesPerfis');
    Route::get('/adicionarPerfil/{nr_matricula?}', 'GerenciarAcessoController@adicionarPerfil')->name('adicionarPerfil');
    Route::post('/removePerfilAcesso/{sq_usuario_perfil}', 'GerenciarAcessoController@removePerfilAcessoUsuario')->name('remove-perfil-acesso-usuario');
    Route::post('/ativarPerfilAcesso/{sq_usuario_perfil}', 'GerenciarAcessoController@ativarPerfilAcessoUsuario')->name('ativar-perfil-acesso-usuario');
    Route::post('/saveAdicionarAcesso', 'GerenciarAcessoController@saveAdicionarPerfil')->name('saveAdicionarAcesso');
    Route::post('/deletarPerfil/{sq_usuario_perfil?}', 'GerenciarAcessoController@deletarPerfil')->name('deletarPerfil');
    Route::get('/aprovar/{sq_usuario_perfil?}', 'GerenciarAcessoController@aprovarSolicitacao')->name('aprovar');
    Route::post('/saveAprovar', 'GerenciarAcessoController@saveAprovacaoSolicitacao')->name('saveAprovar');
    Route::get('/reprovar/{sq_usuario_perfil?}', 'GerenciarAcessoController@reprovarSolicitacao')->name('reprovar');
    Route::post('/saveReprovar', 'GerenciarAcessoController@saveReprovacaoSolicitacao')->name('saveReprovar');
});

Route::group(['prefix' => 'novoAcesso', 'middleware' => ['auth']], function () {
    Route::get('/', 'GerenciarAcessoController@NovoAcesso')->name('novoAcesso');
    Route::post('/solicitar', 'GerenciarAcessoController@SolicitaNovoAcesso')->name('novoAcesso.solicitaNovo');
    Route::get('/buscar', 'GerenciarAcessoController@buscarPerfilAcesso');
});


Route::group(['prefix' => 'home', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@dashboard')->name('home');
});

Route::group(['prefix' => 'modulo-avaliacao', 'middleware' => ['auth', 'menu']], function () {
    Route::get('/dashboard', 'HomeController@dashboardAvaliacao')->name('dashboard.avaliacao');
});

Route::group(['prefix' => 'modulo-admin', 'middleware' => ['auth', 'menu', 'superadmin']], function () {
    Route::get('/dashboard', 'HomeController@dashboardAdministracao')->name('dashboard.administracao');
});

Route::group(['prefix' => 'modulo-saa', 'middleware' => ['auth', 'menu']], function () {
    Route::get('/dashboard', 'HomeController@dashboardSaa')->name('dashboard.saa');
});

Route::group(['prefix' => 'modulo-solicitacao', 'middleware' => ['auth', 'menu']], function () {
    Route::get('/dashboard', 'HomeController@dashboardSolicitacao')->name('dashboard.solicitacao');
});

Route::group(['prefix' => 'modulo-relatorios', 'middleware' => ['auth', 'menu']], function () {
    Route::get('/dashboard', 'HomeController@dashboardRelatorios')->name('dashboard.relatorios');
});

Route::group(['prefix' => 'modulo-movimentacao', 'middleware' => ['auth', 'menu']], function () {
    Route::get('/dashboard', 'HomeController@dashboardMovimentacao')->name('dashboard.movimentacao');
});

Route::group(['prefix' => 'modulo-banco-talentos', 'middleware' => ['auth', 'menu']], function () {
    Route::get('/dashboard', 'HomeController@dashboardBancoTalentos')->name('dashboard.banco.talentos');
});

Route::post('inserirSessao', 'HomeController@SessaoAtividade')->name('sessao.atividade');

Route::put('cronBenner', 'ImportUsuarioController@cronImport');


