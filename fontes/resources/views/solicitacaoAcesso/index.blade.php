@extends('layout.main')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Solicita Acesso</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class = "breadcrumb-item"><a href = "{{ route('login') }}">Inicial</a></li>
                        <li class="breadcrumb-item active" ><a href="{{ route('solicitaAcesso') }}">Solicita Acesso</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
        <section class = "content">
        <div class = "card card-outline card-primary">
            <form id="formularioSolicitacaoAcesso" name="formularioSolicitacaoAcesso" method="post" action="{{route('saveSolicitaAcesso')}}">
                @csrf
            <div class = "card-header">
                <h4 class = "card-title">Solicitar Acesso</h4>
            </div>
            @if($usuario->st_primeiro_acesso == 'S')
            <div class = "card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('no_usuario') ? ' has-error' : '' }}">
                            {{ Form::label('no_usuario', 'Nome', ['class' => 'control-label']) }}
                            {{ Form::text('no_usuario', $usuario->no_usuario, ['class' => 'form-control', 'placeholder' => 'Nome do usuário', 'readonly']) }}

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('ds_area') ? ' has-error' : '' }}">
                            {{ Form::label('ds_area', 'Área', ['class' => 'control-label']) }}
                            {{ Form::text('ds_area', $usuario->areaBenner ? $usuario->areaBenner->ds_area_benner : $ds_area_terceiro, ['class' => 'form-control', 'placeholder' => 'Área', 'readonly']) }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('ds_email') ? ' has-error' : '' }}">
                            {{ Form::label('ds_email', 'E-mail', ['class' => 'control-label']) }}<span style='color:#FF1A1A'>*</span>
                            {{ Form::text('ds_email',  $usuario->ds_email, ['class' => 'form-control', 'placeholder' => 'E-mail do usuário']) }}
                            @if($errors->has('ds_email'))
                                <span class="help-block">{{ $errors->first('ds_email') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('ds_telefone') ? ' has-error' : '' }}">
                            {{ Form::label('ds_telefone', 'Telefone', ['class' => 'control-label']) }}<span style='color:#FF1A1A'>*</span>
                            {{ Form::text('ds_telefone', null, ['class' => 'form-control', 'placeholder' => 'Telefone do usuário', 'maxlength' => 15]) }}
                            @if($errors->has('ds_telefone'))
                                <span class="help-block">{{ $errors->first('ds_telefone') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('cd_modulo') ? ' has-error' : '' }}">
                            {{ Form::label('cd_modulo', 'Módulo', ['class' => 'control-label'])}}<span style='color:#FF1A1A'>*</span>
                            {{ Form::select('cd_modulo', $modulo, [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione', 'data-href' => route('perfil.getByModulo')]) }}
                            @if($errors->has('cd_modulo'))
                                <span class="help-block">{{ $errors->first('cd_modulo') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group{{ $errors->has('cd_perfil') ? ' has-error' : '' }}">
                            {{ Form::label('cd_perfil', 'Perfil', ['class' => 'control-label'])}}<span style='color:#FF1A1A'>*</span>
                            {{ Form::select('cd_perfil', [], [null => 'Selecione'], ['class' => 'form-control', 'placeholder' => 'Selecione']) }}
                            @if($errors->has('cd_perfil'))
                                <span class="help-block">{{ $errors->first('cd_perfil') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class = "card-footer">
                <div class = "row">
                    <div class = "col-md-12">
                        <div id = "carregar" class = "float-left"></div>
                        <div class = "form-group float-right">
                            <button type="submit" class="btn btn-salvar btn-fill pull-right btn-marg-left btn-pesquisa" id="btn-incluir-usuario">Solicitar</button>
                        </div>
                    </div>
                </div>
            </div>

                @else
                    <div class = "card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Você já possui uma solicitação de acesso ativa, aguarde a liberação por parte do gestor de acesso!</p>
                            </div>
                        </div>
                    </div>
                @endif

            </form>
        </div>

@endsection
