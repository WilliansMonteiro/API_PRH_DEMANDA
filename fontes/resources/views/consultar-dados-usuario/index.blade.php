@extends('layout.main')

@section('scripts')
<script type="text/javascript" src="{{ Module::asset('consultardados:js/consultar-dados-usuarios.js') }}"></script>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Consultar dados dos usuários</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class = "breadcrumb-item"><a href = "{{ route('login') }}">Home</a></li>
                        <li class="breadcrumb-item active" ><a href="{{ route('consultarDadosUsuarioPRH') }}">Consultar dados dos usuários</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


    <section class="content">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Pesquisar usuários</h3>
            </div>

            <div class="card-body">
                {!! Form::open(['id' => 'formulario-pesquisar-usuarios']) !!}

                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">Matrícula - (DV)</label>
                            <input type="text" id="input_matricula" name="matricula" class="form-control" />
                        </div>
                    </div>


                    <div class="col-4">
                        <div class="form-gcroup">
                            <label for="">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control">
                        </div>
                    </div>
                    @if($permissao_consulta_global == 1 || $permissao_adm_nuadm == 1 || $permissao_adm_saa == 1 || $permissao_aprovador_gerit == 1 || $permissao_portaria_cms == 1)
                    <div class="col-4">
                        <div class="form-gcroup">
                            <label for="">Lotação</label>
                            <select name="cd_dependencia" id="" class="form-control">
                                <option value="">Selecione</option>
                                @foreach ($lotacao as $item)
                                    <option value="{{$item->cd_dependencia}}">{{$item->sg_dependencia}} - {{$item->nm_dependencia}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="col-2">
                        <div class="form-gcroup">
                            <label for="">Situação</label>
                            <select name="st_situacao" id="" class="form-control">
                                <option value="">Selecione</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>


                </div>


                <div class="row">
                    <div class="col-12">
                        <div id="carregar" class="float-left"></div>
                        <div class="form-group float-right">
                            {{ Form::reset('Limpar', ['class' => 'btn btn-marg-left btn-default', 'id' => 'btnLimpar']) }}
                            {{-- {{ Form::button('Limpar', ['class' => 'btn btn-outline-secondary', 'id' => 'btnLimpar']) }} --}}
                            {{ Form::button('Pesquisar', ['class' => 'btn btn-marg-left btn-pesquisa', 'id' => 'btn-pesquisar-usuarios', 'data-href' => route('search-dados-usuarios-prh')]) }}
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Resultado da consulta</h3>
            </div>
            <div id="retorno" class="card-body">
             {{-- <div class="table-responsive"> --}}
                <table class='table table-striped' id='tabela-usuarios'>
                    <thead class="bg-primary">
                        <tr>
                            @if($permissao_consulta_global == 1 || $permissao_adm_nuadm == 1 || $permissao_adm_saa == 1 || $permissao_aprovador_gerit == 1 && $permissao_gestor_saa == 0)
                            <th class="text-center">Matrícula</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">CPF</th>
                            <th class="text-center">Nascimento</th>
                            <th class="text-center">Mãe</th>
                            <th class="text-center">Cargo</th>
                            <th class="text-center">Função</th>
                            <th class="text-center">Lotação</th>
                            <th class="text-center">Situação</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Empresa</th>
                            @elseif($permissao_gestor_saa == 1 && $permissao_consulta_global == 0 && $permissao_adm_nuadm == 0 && $permissao_adm_saa == 0 && $permissao_aprovador_gerit == 0)
                            <th class="text-center">Matrícula</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Cargo</th>
                            <th class="text-center">Função</th>
                            <th class="text-center">Lotação</th>
                            @elseif($permissao_portaria_cms == 1)
                            <th class="text-center">Matrícula</th>
                            <th class="text-center">Nome</th>
                            <th class="text-center">Lotação</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="text-center">

                    </tbody>
                </table>
            {{-- </div> --}}

            </div>
        </div>

        {{-- <input type="hidden" value="{{$permissao_solicitante}}" id="permissao_solicitante"> --}}
        <input type="hidden" value="{{$permissao_consulta_global}}" id="permissao_consulta_global">
        <input type="hidden" value="{{$permissao_adm_nuadm}}" id="permissao_adm_nuadm">
        <input type="hidden" value="{{$permissao_adm_saa}}" id="permissao_adm_modulo_saa">
        <input type="hidden" value="{{$permissao_gestor_saa}}" id="permissao_gestor_modulo_saa">
        <input type="hidden" value="{{$permissao_aprovador_gerit}}" id="permissao_aprovador_gerit">
        <input type="hidden" value="{{$permissao_portaria_cms}}" id="permissao_portaria_cms">



    </section>



@endsection
