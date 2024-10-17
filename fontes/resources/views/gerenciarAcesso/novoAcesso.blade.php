@extends('layout.main')

@section('content')
    <section class = "content-header">
        <div class = "container-fluid">
            <div class = "row mb-2">
                <div class = "col-sm-6">
                    <h1>Gerenciar Acesso</h1>
                </div>
                <div class = "col-sm-6">
                    <ol class = "breadcrumb float-sm-right">
                        <li class = "breadcrumb-item"><a href = "/">Inicial</a></li>
                        <li class = "breadcrumb-item active">Solicitar Acesso</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class = "content">
        <div class = "card card-outline card-primary">
            <div class = "card-header">
                <h3 class = "card-title">Solicitar Acesso</h3>
            </div>
            <div class = "card-body">
                {!! Form::open(['route' => 'novoAcesso.solicitaNovo', 'novalidate']) !!}
                @if ($errors->any())
                    <div class = "alert alert-danger" role = "alert">
                        Por favor corrija os seguintes erros
                    </div>
                @endif
                <div class = "row">
                    <div class = "col-md-4">
                        <div class = "form-group {{ $errors->has('cd_modulo') ? ' has-error' : '' }}">
                            {{ Form::label('cd_modulo', 'Módulo', array('class' => 'control-label')) }}<span style = 'color:#FF1A1A'>*</span>
                            {{ Form::select('cd_modulo', $modulos, [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione', 'required','data-href' => route('perfil.getByModulo')]) }}
                            @if($errors->has('cd_modulo'))
                                <span class = "help-block">{{ $errors->first('cd_modulo') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class = "col-md-4">
                        <div class = "form-group{{ $errors->has('cd_perfil') ? ' has-error' : '' }}">
                            {{ Form::label('cd_perfil', 'Perfil') }}<span style = 'color:#FF1A1A'>*</span>
                            {{ Form::select('cd_perfil', [], [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione', 'required']) }}
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
                                   href = "{{ route('home') }}">Voltar</a>
                                <button class = "btn btn-primary">Solicitar Acesso</button>
                            </div>
                        </div>
                    </div>
                </div>
        {!! Form::close() !!}
    </section>
@endsection
