@extends('layout.main')

@section('scripts')
<script type="text/javascript">
    $.ajaxSetup({headers: {'csrftoken' : '{{csrf_token()}}'}});

    $('#btnExecutar').click(function(){
        let url = $(this).data("href");
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioManutencaoSistema").serializeArray(),
            success: function (retorno) {               
                $('#divResultado').html(retorno);
            }
        });
    });
</script>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manutenção do Sistema</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('login') }}">Inicial</a></li>
                        <li class="breadcrumb-item active"><a href="{{ route('manutencaoSistema') }}">Manutenção do Sistema</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Executar Comando Artisan</h3>
            </div>
            <div class="card-body">
                {!! Form::open(['id' => 'formularioManutencaoSistema']) !!}               
                <div class="row">                                           
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('Comando', 'Comandos', ['class' => 'control-label']) }}
                            {{ Form::text('ds_comando', null, ['class' => 'form-control', 'placeholder' => 'Comando']) }}                            
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
                            {{ Form::button('Executar', ['class' => 'btn btn-marg-left btn-primary', 'id' => 'btnExecutar', 'data-href' => route('executarComando')]) }}
                        </div>
                    </div>
                </div>
            </div>            
            {!! Form::close() !!}
        </div>
        <div class = "card card-outline card-primary">
        <div class = "card-header">
            <h3 class = "card-title">Retorno do comando</h3>           
        </div>
        <div id="divResultado"></div>        
    </div>        
    </section>
@endsection
