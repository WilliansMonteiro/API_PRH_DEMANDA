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
                        <li class = "breadcrumb-item active">Informações da Solicitação de Acesso</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class = "content">
        <div class = "card card-outline card-primary">
            <div class = "card-header">
                <h3 class = "card-title">Informações da Solicitação de Acesso</h3>
            </div>
            <div class = "card-body">
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('nr_matricula', 'Matrícula') }}
                            {{ Form::text('nr_matricula', $usuario->usuario->nr_matricula, array_merge(['class' => 'form-control', 'placeholder' => 'Matrícula', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('no_usuario', 'Usuário') }}
                            {{ Form::text('no_usuario', $usuario->usuario->no_usuario, array_merge(['class' => 'form-control', 'placeholder' => 'Nome do Usuário', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('ds_area', 'Área') }}
                            {{ Form::text('ds_area', $dadosBenner ? $dadosBenner->ds_area_benner : $ds_area_terceiro, array_merge(['class' => 'form-control', 'placeholder' => 'Área', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('ds_modulo', 'Módulo Solicitado') }}
                            {{ Form::text('ds_modulo', $usuario->permissao->modulo->ds_modulo, array_merge(['class' => 'form-control', 'placeholder' => 'Módulo Solicitado', 'readonly'])) }}
                        </div>
                    </div>
                </div>
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group">
                            {{ Form::label('ds_perfil', 'Perfil Solicitado') }}
                            {{ Form::text('ds_perfil', $usuario->permissao->perfil->ds_perfil_acesso, array_merge(['class' => 'form-control', 'placeholder' => 'Perfil Solicitado', 'readonly'])) }}
                        </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection
