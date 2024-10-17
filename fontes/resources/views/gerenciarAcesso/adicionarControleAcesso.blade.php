@extends('layout.main')


@section('scripts')
    <script type="text/javascript" src="{{ Module::asset('gerenciaracesso:js/show-controle-acesso.js') }}"></script>
@endsection

@section('content')
    <section class = "content-header">
        <div class = "container-fluid">
            <div class = "row mb-2">
                <div class = "col-sm-6">
                    <h1>Manter Acesso</h1>
                </div>
                <div class = "col-sm-6">
                    <ol class = "breadcrumb float-sm-right">
                        <li class = "breadcrumb-item"><a href = "/">Inicial</a></li>
                        <li class = "breadcrumb-item"><a href = "{{ route('gerenciarAcesso') }}">Gerenciar Acesso</a>
                        </li>
                        <li class = "breadcrumb-item active">Adicionar Perfil de Acesso</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class = "content">
        <div class = "card card-outline card-primary">
            <div class = "card-header">
                <h3 class = "card-title">Adicionar Perfil de Acesso</h3>
            </div>
            {!! Form::open(['route' => 'saveAdicionarAcesso', 'novalidate']) !!}
            <div class = "card-body">
                @if ($errors->any())
                    <div class = "alert alert-danger" role = "alert">
                        Por favor corrija os seguintes erros
                    </div>
                @endif
                <div class = "row">
                    <div class = "col-md-6">
                        <div class = "form-group">
                            {{ Form::label('nr_matricula', 'Matrícula') }}
                            {{ Form::text('nr_matricula', $usuario->nr_matricula, array_merge(['class' => 'form-control', 'placeholder' => 'Matrícula', 'readonly'])) }}
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "form-group">
                            {{ Form::label('no_usuario', 'Usuário') }}
                            {{ Form::text('no_usuario', $usuario->no_usuario, array_merge(['class' => 'form-control', 'placeholder' => 'Nome do Usuário', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-6">
                        <div class = "form-group">
                            {{ Form::label('ds_area', 'Área') }}
                            {{ Form::text('ds_area', $usuario->areaBenner ? $usuario->areaBenner->ds_area_benner : $ds_area_terceiro, array_merge(['class' => 'form-control', 'placeholder' => 'Área', 'readonly'])) }}
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "form-group">
                            {{ Form::label('ds_email', 'E-mail') }}
                            {{ Form::text('ds_email', $usuario->ds_email, array_merge(['class' => 'form-control', 'placeholder' => 'Módulo Solicitado', 'readonly'])) }}
                        </div>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-md-6">
                        <div class = "form-group {{ $errors->has('cd_modulo') ? ' has-error' : '' }}">
                            {{ Form::label('cd_modulo', 'Módulo') }}<span style = 'color:#FF1A1A'>*</span>
                            {{ Form::select('cd_modulo', $modulo, ['Selecione' => null], ['class' => 'form-control', 'placeholder' => 'Selecione', 'required','data-href' => route('perfil.getByModulo')]) }}
                            @if($errors->has('cd_modulo'))
                                <span class = "help-block">{{ $errors->first('cd_modulo') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class = "col-md-6">
                        <div class = "form-group{{ $errors->has('cd_perfil') ? ' has-error' : '' }}">
                            {{ Form::label('cd_perfil', 'Perfil') }}<span style = 'color:#FF1A1A'>*</span>
                            {{ Form::select('cd_perfil', [],  ['Selecione' => null], ['class' => 'form-control', 'placeholder' => 'Selecione', 'required']) }}
                            @if($errors->has('cd_perfil'))
                                <span class = "help-block">{{ $errors->first('cd_perfil') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class = "card-footer">
                    <div class = "row">
                        <div class = "col-md-12">
                            <div id = "carregar" class = "float-left"></div>
                            <div class = "form-group float-right">
                                <a class = "btn btn-cancelar btn-fill pull-right btn-marg-left"
                                        href = "{{ route('gerenciarAcesso') }}">Voltar</a>
                                <button type = "submit"
                                        class = "btn btn-salvar btn-fill pull-right btn-marg-left btn-pesquisa"
                                        id = "btn-incluir-perfil">Salvar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
                <div class = "card card-outline card-primary">
                    <div class = "card-header">
                        <h3 class = "card-title">Perfis de Acesso</h3>
                    </div>
                    <div class = "row">
                        <div class = "col-md-12">
                            <div class = "form-group">
                                <table class = 'table table-striped projects' id = 'tbPerfil'>
                                    <thead>
                                    <tr>
                                        <th class = "text-center">Módulo</th>
                                        <th class = "text-center">Perfil</th>
                                        <th class="text-center"> Situação</th>
                                        <th class = "text-center">Ação</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($usuario->usuarioPerfis as $perfil)
                                        @if($perfil->st_solicitacao == 'A')
                                        <tr>
                                            <td class="text-center">{{$perfil->permissao->modulo->ds_modulo}}</td>
                                            <td class="text-center">{{$perfil->permissao->perfil->ds_perfil_acesso}}</td>
                                            <td class="text-center">Ativo</td>
                                            <td class="text-center">
                                                <a data-href="{{route('remove-perfil-acesso-usuario', $perfil->sq_usuario_perfil )}}" class="btn btn-danger" id="remove-perfil-controle-acesso">
                                                <i class="fas fa-thumbs-down" data-toggle="tooltip" data-placement="top" title="Inativar perfil" style="color: white;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @elseif($perfil->st_solicitacao == 'R')
                                        <tr>
                                            <td class="text-center">{{$perfil->permissao->modulo->ds_modulo}}</td>
                                            <td class="text-center">{{$perfil->permissao->perfil->ds_perfil_acesso}}</td>
                                            <td class="text-center">Inativo</td>
                                            <td class="text-center">
                                                <a data-href="{{route('ativar-perfil-acesso-usuario', $perfil->sq_usuario_perfil )}}" class="btn btn-success" id="ativar-perfil-controle-acesso">
                                                <i class="fas fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="Ativar perfil" style="color: white;"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
