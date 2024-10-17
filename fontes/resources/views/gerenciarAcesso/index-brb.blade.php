    <section class="content">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Pesquisar</h3>
            </div>
            <div class="card-body">
                {!! Form::open(['id' => 'formularioSolicitacaoAcessoPendente']) !!}
                <input type="hidden" id="rotaControleAcesso" value="{{ route('getControleAcesso') }}">
                <input type="hidden" id="rotaSolicitacoesPendentes" value="{{ route('getSolicitacoesPendentes') }}">

                <div class="row">
                    @if (Auth::check() && Auth::user()->isSuperAdmin())
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('cd_modulo', 'Módulo', ['class' => 'control-label']) }}
                                {{ Form::select('cd_modulo', $modulo, [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione']) }}
                            </div>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('ds_area', 'Área', ['class' => 'control-label']) }}
                            {{ Form::select('ds_area', $area, [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione']) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('nr_matricula', 'Matrícula', ['class' => 'control-label']) }}
                            {{ Form::text('nr_matricula', null, ['class' => 'form-control', 'placeholder' => 'Número da matrícula', 'maxlength' => 8]) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('tp_consulta', 'Consulta', ['class' => 'control-label']) }}
                            <select id="tp_consulta" name="tp_consulta" class='form-control'>
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
                        <div id="carregar" class="float-left"></div>
                        <div class="form-group float-right">
                            {{ Form::reset('Limpar', ['class' => 'btn pull-right btn-default']) }}
                            {{ Form::button('Pesquisar', ['class' => 'btn btn-marg-left btn-pesquisa', 'id' => 'btnPesquisarSolicitacoesAcessoPendentes', 'data-href' => route('pesquisarGerenciarAcesso')]) }}
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
                    <div class="card-body">
                        <table class='table table-striped projects' id='tbSolicitacoesPendentes'>
                            <thead>
                                <tr>
                                    <th class="text-center">Matrícula</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Área</th>
                                    <th class="text-center">Módulo Solicitado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Controle de Acesso</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body" id="acesso-brb-div" style="display: none;">
                        <table class='table table-striped projects' id='tb_usuarios_brb_controle'>
                            <thead>
                                <tr>
                                    <th class="text-center">Matrícula</th>
                                    <th class="text-center">Nome</th>
                                    <th class="text-center">Área</th>
                                    {{-- <th class = "text-center">Perfil</th> --}}
                                    {{-- <th class = "text-center">Módulo</th> --}}
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios_brb_controle as $item)
                                <tr>
                                    <td class="text-center">{{$item->nr_matricula}}</td>
                                    <td class="text-center">{{$item->no_usuario}}</td>
                                    <td class="text-center">{{$item->ds_area_benner}}</td>

                                    <td class="text-center">
                                        <a class="btn btn-primary btn-sm" href="{{route('informacoesPerfis', $item->nr_matricula . '/1')}}"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Informações do Usuário"></i></a>
                                        <a class="btn btn-success btn-sm" href="{{route('adicionarPerfil', $item->nr_matricula)}}"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Adicionar perfil"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group float-right">
                            <input type="hidden" id="infoAcessosUsuarioBrb" value="{{ route('infoAcessosUsuarios') }}">
                            <button id="btnUsuarioBrb2Csv" class="btn pull-right btn-success" style="display: none;">Exportar CSV</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        var primeiraPesquisaUsuariosBrb = @json($usuarios_brb_controle);  
    </script>
