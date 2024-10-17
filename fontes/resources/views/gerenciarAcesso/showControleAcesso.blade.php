@extends('layout.main')

@section('scripts')
    <script type="text/javascript" src="{{ Module::asset('gerenciaracesso:js/show-controle-acesso.js') }}"></script>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manter Acesso</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Inicial</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('gerenciarAcesso') }}">Gerenciar Acesso</a>
                        </li>
                        <li class="breadcrumb-item active">Informações da Solicitação de Acesso</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="card card-outline card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title">Informações da Solicitação de Acesso</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('nr_matricula', 'Matrícula') }}
                            {{ Form::text('nr_matricula', $usuario->nr_matricula, array_merge(['class' => 'form-control', 'placeholder' => 'Matrícula', 'readonly'])) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('no_usuario', 'Usuário') }}
                            {{ Form::text('no_usuario', $usuario->no_usuario, array_merge(['class' => 'form-control', 'placeholder' => 'Nome do Usuário', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('ds_area', 'Área') }}
                            {{ Form::text('ds_area', $dadosBenner ? $dadosBenner->ds_area_benner : $ds_area_terceiro, array_merge(['class' => 'form-control', 'placeholder' => 'Área', 'readonly'])) }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('ds_email', 'E-mail') }}
                            {{ Form::text('ds_email', $usuario->ds_email, array_merge(['class' => 'form-control', 'placeholder' => 'Módulo Solicitado', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Perfis de Acesso</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <table class='table table-striped projects' id='tbPerfil'>
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-center">Módlo</th>
                                            <th class="text-center">Perfil</th>
                                            @if ($action == 'deletar')
                                                <th class="text-center">Ação</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($usuario->usuarioPerfis as $perfil)
                                            {{-- @if ($perfil->st_solicitacao == 'A') --}}
                                                <tr>
                                                    <td class="text-center">{{ $perfil->permissao->modulo->ds_modulo }}</td>
                                                    <td class="text-center">{{ $perfil->permissao->perfil->ds_perfil_acesso }}</td>
                                                    @if ($action == 'deletar')
                                                        <td align="center">
                                                            @foreach ($arrModuloPermissao as $modulo)
                                                                @if ($perfil->permissao->modulo->cd_modulo == $modulo)
                                                                    @if ($perfil->st_solicitacao == 'A')
                                                                    <a class="btn btn-danger btn-sm" href=""
                                                                        id="btnPerfilExcluir"
                                                                        data-href="{{ route('deletarPerfil', $perfil->sq_usuario_perfil) }}">
                                                                        <i class="fas fa-trash" data-toggle="tooltip"
                                                                            data-placement="top"
                                                                            title="Inativar perfil do Usuário">
                                                                        </i>
                                                                    </a>
                                                                    @elseif($perfil->st_solicitacao == 'R')
                                                                    <a class="btn btn-success btn-sm" href=""
                                                                        id="btnPerfilExcluir"
                                                                        data-href="{{ route('deletarPerfil', $perfil->sq_usuario_perfil) }}">
                                                                        <i class="fas fa-trash" data-toggle="tooltip"
                                                                            data-placement="top"
                                                                            title="Inativar perfil do Usuário">
                                                                        </i>
                                                                    </a>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    @endif
                                                </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($action == 'visualizar')


                <div class="card card-outline card-primary">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Informações do histórico de acesso</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($dadosHistorico as $hist)
                                <div class="time-label">
                                    <span class="{{ $hist->cd_acao_hist_acesso == 1 ? 'bg-green' : 'bg-gray' }}">{{ Carbon\Carbon::createFromDate($hist->dt_inclusao)->format('d/m/Y') }}</span>
                                </div>

                                <div>
                                    @if ($hist->cd_acao_hist_acesso == 6)
                                        <i class="fas fa-clock bg-yellow"></i>
                                    @elseif($hist->cd_acao_hist_acesso == 1 || $hist->cd_acao_hist_acesso == 3 || $hist->cd_acao_hist_acesso == 5)
                                        <i class="fas fa-check bg-green"></i>
                                    @elseif($hist->cd_acao_hist_acesso == 2 || $hist->cd_acao_hist_acesso == 4)
                                        <i class="fas fa-undo bg-red"></i>
                                    @endif
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-clock"></i> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $hist->dt_inclusao)->format('H:i:s') }}</span>
                                        <div class="timeline-body">
                                            @if($hist->cd_acao_hist_acesso == 6)
                                                <b>Status : </b>Solicitado<br />
                                                <b>Solicitado por: </b> {{$hist->nr_matricula}} - {{$usuario->no_usuario}}
                                                 @elseif($hist->cd_acao_hist_acesso == 1) <b>Status : </b>Aprovado<br />
                                            <b>Aprovado por : </b> {{$hist->nr_matricula_gestor_acesso }} - {{$hist->no_usuario}}
                                             @elseif($hist->cd_acao_hist_acesso == 2) <b>Status : </b>Reprovado<br />
                                            <b>Reprovado por : </b> {{$hist->nr_matricula_gestor_acesso }} - {{$hist->no_usuario}}
                                            @elseif($hist->cd_acao_hist_acesso == 3) <b>Status : </b>Aprovado<br />
                                            <b>Aprovado por : </b> {{$hist->nr_matricula_gestor_acesso }} - {{$hist->no_usuario}}
                                            @elseif($hist->cd_acao_hist_acesso == 4) <b>Status : </b>Inativado<br />
                                            <b>Inativado por : </b> {{$hist->nr_matricula_gestor_acesso }} - {{$hist->no_usuario}}
                                            @elseif($hist->cd_acao_hist_acesso == 5) <b>Status : </b>Acesso concedido<br />
                                            <b>Acesso concedido por : </b> {{$hist->nr_matricula_gestor_acesso }} - {{$hist->no_usuario}}

                                            @endif
                                        </div>
                                        <div class="timeline-footer">
                                            @if($hist->ds_modulo) {{ Form::label('ds_justificativa', 'Observações', ['class' => 'control-label']) }}
                                             {{ Form::textarea('ds_justificativa', "A AÇÃO $hist->no_acao_hist_acesso FOI REALIZADA PARA O PERFIL $hist->ds_perfil_acesso NO MÓDULO $hist->ds_modulo", [ 'class' => 'form-control',
                                        'rows' => 3, 'name' => 'ds_justificativa', 'id' => 'ds_justificativa' ])}}

                                            <br />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>Não existe histórico</p>
                            @endforelse
                            <div>
                                <i class="fas fa-play-circle bg-green"></i>
                            </div>
                        </div>

                    </div>
                </div>

                @endif


            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div id="carregar" class="float-left"></div>
                        <div class="form-group float-right">
                            <a class="btn btn-cancelar btn-fill pull-right btn-marg-left"
                                href="{{ route('gerenciarAcesso') }}">Voltar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
