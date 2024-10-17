@extends('layout.main')
@section('content')
    <div class="fundo">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"><i class="nav-icon fas fa-home"></i> Home</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><i class="nav-icon fas fa-home"></i> Home</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            @if($modulo_performance[0])
                                <div class="col-lg-6">
                                    <!-- small box -->
                                    <div class="info-box mb-3 modulo-sistema">
                                        <span class="info-box-icon imagem-modulo" id="{{$modulo_performance[1][0]->ds_imagem_brs}}"></span>
                                        <div class="info-box-content">
                                            <h5 class="info-box-number nome-modulo">{{$modulo_performance[1][0]->ds_modulo}}</h5>
                                            <a href="{{route('dashboard.avaliacao')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($modulo_processo_seletivo[0])
                                <div class="col-lg-6">
                                    <div class="info-box mb-3 modulo-sistema">
                                        <span class="info-box-icon imagem-modulo" id="{{$modulo_processo_seletivo[1][0]->ds_imagem_brs}}"></span>
                                        <div class="info-box-content">
                                            <h5 class="info-box-number nome-modulo">{{$modulo_processo_seletivo[1][0]->ds_modulo}}</h5>
                                            <a href="{{route('dashboard.processoSeletivo')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($modulo_admin[0])
                                <!-- ./col -->
                                <div class="col-lg-6">
                                        <div class="info-box mb-3 modulo-sistema" >
                                            <span class="info-box-icon imagem-modulo" id="{{$modulo_admin[1][0]->ds_imagem_brs}}"></span>
                                            <div class="info-box-content">
                                                <h5 class="info-box-number nome-modulo">{{$modulo_admin[1][0]->ds_modulo}}</h5>
                                                <a href="{{route('dashboard.administracao')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                            </div>
                                        </div>
                                </div>
                            @endif
                            @if($modulo_saa[0])
                                <!-- ./col -->
                                <div class="col-lg-6">
                                    <div class="info-box mb-3 modulo-sistema">
                                        <span class="info-box-icon imagem-modulo" id="{{$modulo_saa[1][0]->ds_imagem_brs}}"></span>
                                        <div class="info-box-content">
                                            <h5 class="info-box-number nome-modulo">{{$modulo_saa[1][0]->ds_modulo}}</h5>
                                            <a href="{{route('dashboard.saa')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($modulo_solicitacao[0])
                                <!-- ./col -->
                                <div class="col-lg-6">
                                    <div class="info-box mb-3 modulo-sistema">
                                        <span class="info-box-icon imagem-modulo" id="{{$modulo_solicitacao[1][0]->ds_imagem_brs}}"></span>
                                        <div class="info-box-content">
                                            <h5 class="info-box-number nome-modulo">{{$modulo_solicitacao[1][0]->ds_modulo}}</h5>
                                            <a href="{{route('dashboard.solicitacao')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($modulo_movimentacao[0])
                                <!-- ./col -->
                                <div class="col-lg-6">
                                    <div class="info-box mb-3 modulo-sistema">
                                        <span class="info-box-icon imagem-modulo" id="{{$modulo_movimentacao[1][0]->ds_imagem_brs}}"></span>
                                        <div class="info-box-content">
                                            <h5 class="info-box-number nome-modulo">{{$modulo_movimentacao[1][0]->ds_modulo}}</h5>
                                            <a href="{{route('dashboard.movimentacao')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($modulo_relatorio[0])
                                <div class="col-lg-6">
                                    <div class="info-box mb-3 modulo-sistema">
                                        <span class="info-box-icon imagem-modulo" id="{{$modulo_relatorio[1][0]->ds_imagem_brs}}"></span>
                                        <div class="info-box-content">
                                            <h5 class="info-box-number nome-modulo">{{$modulo_relatorio[1][0]->ds_modulo}}</h5>
                                            <a href="{{route('dashboard.relatorios')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if($modulo_banco_talentos[0])
                                 <div class="col-lg-6">
                                      <div class="info-box mb-3 modulo-sistema">
                                            <span class="info-box-icon imagem-modulo" id="{{$modulo_banco_talentos[1][0]->ds_imagem_brs}}"></span>
                                            <div class="info-box-content">
                                                <h5 class="info-box-number nome-modulo">{{$modulo_banco_talentos[1][0]->ds_modulo}}</h5>
                                                <a href="{{route('dashboard.banco.talentos')}}"><h6 class="info-box-text">Saiba mais</h6></a>
                                            </div>
                                        </div>
                                 </div>
                            @endif
                        </div>
                    </div>

                    <!-- MODULOS ESTÁTICOS/DINÂMICOS -->

                    {{-- <div class="col-lg-6">
                        <div class="row">
                            @foreach($modulos_externos as $modulo)
                            <div class="col-lg-6">
                                <div class="info-box mb-3 modulo-sistema">
                                    <span class="info-box-icon imagem-modulo" id="{{$modulo->ds_imagem_brs}}"></span>
                                    <div class="info-box-content">
                                        <h5 class="info-box-number nome-modulo">{{$modulo->ds_modulo}}</h5>
                                        <a href="{{$modulo->ds_url}}" target="_blank"><h6 class="info-box-text">Saiba mais</h6></a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div> --}}

                </div>
            </div>
        </section>
    </div>
@endsection

