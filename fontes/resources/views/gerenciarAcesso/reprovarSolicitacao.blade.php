@extends('layout.main')

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
                        <li class = "breadcrumb-item active">Reprovar Solicitação</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class = "content">
        <div class = "card card-outline card-primary">
            <div class = "card-header">
                <h3 class = "card-title">Reprovar Solicitação</h3>
            </div>
            <div class = "card-body">
                {!! Form::open(['route' => 'saveReprovar', 'novalidate']) !!}
                <input type = "hidden" id = "sq_usuario_perfil" , name = "sq_usuario_perfil"
                        value = "{{$usuario->sq_usuario_perfil}}">
                @if ($errors->any())
                    <div class = "alert alert-danger" role = "alert">
                        Por favor corrija os seguintes erros
                    </div>
                @endif
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('no_usuario', 'Usuário') }}
                            {{ Form::text('no_usuario', $usuario->usuario->no_usuario, array_merge(['class' => 'form-control', 'placeholder' => 'Código', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('ds_modulo', 'Módulo Solicitado') }}
                            {{ Form::text('ds_modulo', $usuario->permissao->modulo->ds_modulo, array_merge(['class' => 'form-control', 'placeholder' => 'Código', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('ds_perfil', 'Perfil Solicitado') }}
                            {{ Form::text('ds_perfil', $usuario->permissao->perfil->ds_perfil_acesso, array_merge(['class' => 'form-control', 'placeholder' => 'Código', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-8">
                        <div class = "form-group{{ $errors->has('ds_justificativa') ? ' has-error' : '' }}">
                            {{ Form::label('ds_justificativa', 'Justificativa', array('class' => 'control-label')) }}
                            <span style = 'color:#FF1A1A'>*</span>
                            {{ Form::textarea('ds_justificativa', null, ['class' => 'form-control', 'placeholder' => 'Justificativa', 'maxlenght' => '400']) }}
                        </div>

                        @if($errors->has('ds_justificativa'))
                            <span class = "help-block">{{ $errors->first('ds_justificativa') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div id="carregar" class="float-left"></div>
                        <div class="form-group float-right">
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
        </div>
        </div>
    </section>
@endsection
