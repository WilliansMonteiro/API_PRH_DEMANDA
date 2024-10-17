@extends('layout.main')


@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-tachometer-alt"></i> Dashboard do SAA</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="nav-icon fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item active">SAA</li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Módulo SAA</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        @if($valorperfil)

                            <div class="card-body" style="display: block;">
                                <div class="col-md-3">
                                    <!-- <li class="breadcrumb-item"><a href="{{route('saa.gerenciar-solicitacoes-index','solicitacoes')}}"><i class="fas fa-exclamation-circle"></i> Solicitações pedentes de aprovação, verifique aqui. </a></li> -->
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-exclamation"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Novas Solicitações pendentes de aprovação!</span>
                                                <span class="info-box-number">
                                                <a href="{{route('saa.gerenciar-solicitacoes-index')}}">  consulte aqui </a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
