@extends('layout.main')
@section('scripts')
<script type="text/javascript" src="{{ Module::asset('gerenciaracesso:js/gerenciar-acesso.js') }}"></script>
@endsection

<style> /* Personalize a aparência dos links de guias */
.nav-tabs .nav-link {
    color: black; /* Define a cor do link para preto */ text-decoration: none; /* Remove o sublinhado */
} /* Estilize a guia ativa se necessário */
.nav-tabs .nav-item.active .nav-link {
     color: red; /* Define a cor do link da guia ativa (se desejar) */
    }
 </style>

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gerenciar Acesso</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('login') }}">Inicial</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('solicitaAcesso') }}">Gerenciar Acesso</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="col-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Usuários BRB</a>
            </li>
            <li class="nav-item">
              <a class="nav-link bg-primary" id="profile-tab" data-toggle="tab" href="#perfil" role="tab" aria-controls="profile" aria-selected="false" id="btn-menu-nav">Usuários Terceirizados <i class="fas fa-bell" style="color: white;"> <span>{{$terceiros_pendentes->count()}}</span></i></a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">@include('gerenciarAcesso.index-brb')</div>
            <div class="tab-pane fade" id="perfil" role="tabpanel" aria-labelledby="profile-tab">
                <section class="content">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Pesquisar</h3>
                        </div>
                        <div class="card-body">
                            {!! Form::open(['id' => 'formularioSolicitacaoAcessoPendenteTerceiros']) !!}
                            <input type="hidden" id="rotaControleAcessoTerceiros" value="{{ route('getControleAcessoTerceiros') }}">
                            {{-- <input type="hidden" id="rotaSolicitacoesPendentesTerceiros" value="{{ route('getSolicitacoesPendentesTerceiros') }}"> --}}

                            <div class="row">
                                @if (Auth::check() && Auth::user()->isSuperAdmin())
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('cd_modulo_terceiros', 'Módulo', ['class' => 'control-label']) }}
                                            {{ Form::select('cd_modulo_terceiros', $modulo, [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione']) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('cd_area_usuario_terceiros', 'Área', ['class' => 'control-label']) }}
                                        {{-- {{ Form::select('cd_area_usuario_terceiros', $area, [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione']) }} --}}
                                        <select name="cd_area_usuario_terceiros" id="" class="form-control">
                                          <option value="">Selecione</option>
                                          @foreach ($area_terceiros as $item)
                                              <option value="{{$item->cd_empresa_dependencia}}">{{$item->sg_dependencia}}</option>
                                          @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('nr_matricula_terceiros', 'Matrícula', ['class' => 'control-label']) }}
                                        {{ Form::text('nr_matricula_terceiros', null, ['class' => 'form-control', 'placeholder' => 'Número da matrícula', 'maxlength' => 8]) }}
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        {{ Form::label('tp_consulta', 'Consulta', ['class' => 'control-label']) }}
                                        <select id="tp_consulta_terceiros" name="tp_consulta_terceiros" class='form-control'>
                                            <option value="1" selected>Solicitações Pendentes</option>
                                            <option value="2">Controle Acesso</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="carregar-terceiros" class="float-left"></div>
                                    <div class="form-group float-right">
                                        {{ Form::reset('Limpar', ['class' => 'btn pull-right btn-default']) }}
                                        {{ Form::button('Pesquisar', ['class' => 'btn btn-marg-left btn-pesquisa', 'id' => 'btnPesquisarSolicitacoesAcessoPendentesTerceiros', 'data-href' => route('pesquisarGerenciarAcessoTerceiros')]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Solicitações de Acesso Pendentes</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body" id="pendentes-terceiros-div">
                                    <table class='table table-striped projects' id='tbSolicitacoesPendentesTerceiros'>
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-center">Matrícula</th>
                                                <th class="text-center">Nome</th>
                                                <th class="text-center">Área</th>
                                                <th class="text-center">Módulo Solicitado</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($terceiros_pendentes as $item)
                                            <tr>
                                                 <td class="text-center">{{$item->nr_matricula}}</td>
                                                 <td class="text-center">{{$item->no_social == null ? $item->no_usuario : $item->no_social}}</td>
                                                 <td class="text-center">{{$item->sg_dependencia}}</td>
                                                 <td class="text-center">{{$item->ds_modulo}}</td>
                                                 <td class="text-center">
                                                    <a class="btn btn-primary btn-sm" href="{{route('informacoes', $item->sq_usuario_perfil)}}"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Informações do Usuário"></i></a>
                                                    <a class="btn btn-success btn-sm" href="{{route('aprovar', $item->sq_usuario_perfil)}}"><i class="fas fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="Aprovar Solicitação de Acesso"></i></a>
                                                    <a class="btn btn-danger btn-sm" href="{{route('reprovar', $item->sq_usuario_perfil)}}"><i class="fas fa-thumbs-down" data-toggle="tooltip" data-placement="top" title="Reprovar  Solicitação de Acesso"></i></a>
                                                 </td>
                                            </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                                <div id="retorno-pendentes-terceiros-div-resultado"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Controle de Acesso</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-body" id="acesso-terceiros-div">
                                    <table class='table table-striped projects' id='tbControleAcessoTerceiros'>
                                        <thead class="bg-primary">
                                            <tr>
                                                <th class="text-center">Matrícula</th>
                                                <th class="text-center">Nome</th>
                                                <th class="text-center">Área</th>
                                                <th class="text-center">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($terceiros_acesso as $item)
                                                <tr>
                                                    <td class="text-center">{{$item->nr_matricula}}</td>
                                                    <td class="text-center">{{$item->no_usuario}}</td>
                                                    <td class="text-center">{{$item->sg_dependencia}}</td>

                                                    <td class="text-center">
                                                        <a class="btn btn-primary btn-sm" href="{{route('informacoesPerfis', $item->nr_matricula . '/1')}}"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Informações do Usuário"></i></a>
                                                        <a class="btn btn-success btn-sm" href="{{route('adicionarPerfil', $item->nr_matricula)}}"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Adicionar perfil"></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="retorno-acesso-terceiros-div-resultado"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group float-right">
                                        <input type="hidden" id="infoAcessosUsuarioTerceiro" value="{{ route('infoAcessosUsuarios') }}">
                                        <button id="btnUsuarioTerceiros2Csv" class="btn pull-right btn-success">Exportar CSV</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <script>
                    var primeiraPesquisaUsuTerceirizados = @json($terceiros_acesso);  
                </script>
            </div>
          </div>
    </div>
@endsection
