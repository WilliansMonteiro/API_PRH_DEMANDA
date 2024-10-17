@extends('layout.main')


<style type="text/css">
    .container-fluid {
        padding: 0;
    }

    #mainSlider .carousel-inner, #mainSlider .carousel-item {
        height: 120vh;
    }

    #mainSlider .carousel-caption {
        top: 8%;
    }

    #mainSlider .carousel-caption h2 {
        font-size: 50px;
        margin-bottom: 30px;
    }

    #mainSlider .carousel-caption p {
        font-size: 22px;
        font-weight: 300;
        margin-bottom: 100px;
        color: #fff;
    }

    .main-btn {
        background-color: #65daf9;
        color: #fff;
        text-transform: uppercase;
        width: 200px;
        height: 60px;
        padding: 10px 20px;
        border-radius: 30px;
        border: 3px solid transparent;
        transition: .5s;
        margin-top: 20px;
    }

    .main-btn:hover {
        text-decoration: none;
        color: #fff;
        background-color: transparent;
        border-color: #65daf9;
    }

    .carousel-indicators .active {
        background-color: #65daf9;
        margin-bottom: 115px;

    }
</style>





@section('content')



    <main>
        <div class="container-fluid">
            <div id="mainSlider" class="carousel slide" data-ride="carousel" data-interval="10000">
                <ol class="carousel-indicators">
                    <li data-target="#mainSlider" data-slide-to="0" class="active"></li>
                    <li data-target="#mainSlider" data-slide-to="1"></li>

                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{asset('img/modulo_avaliacao_principal.jpg')}}" class="" style="height: 88%; width: 100%;"
                             alt="Projetos de avaliacao">


                    </div>
                    <div class="carousel-item ">
                        <img src="{{asset('img/modulo_avaliacao_1.jpg')}}" class="" style="height: 86%; width: 100%;"
                             alt="Avaliação 360">

                        <div class="carousel-caption d-none d-md-block">

                        </div>
                    </div>
                    <div class="carousel-item active">
                        <img src="{{asset('img/modulo_avaliacao_2.jpg')}}" class="" style="height: 88%; width: 100%;"
                             alt="Projetos de avaliacao">


                    </div>
                    <div class="carousel-item active">
                        <img src="{{asset('img/modulo_avaliacao_3.jpg')}}" class="" style="height: 88%; width: 100%;"
                             alt="Projetos de avaliacao">


                    </div>

                    <a href="#mainSlider" class="carousel-control-prev" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a href="#mainSlider" class="carousel-control-next" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
    </main>










    @if(Auth::check() && Auth::user()->verificarApuracaoAvaliacao())
        <div class="container">
            <h5 style="margin-top: 10px;">Dashboard - Avaliação</h5>
            <h6>Chegou a hora de fazer a sua avaliação 360</h6>

            <div class="row" style="margin-top: 20px;">
                <div class="col-md-3">
                    <div class="card border-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center" style="background: #F4A460;">Auto Avaliação</div>
                        <div class="card-body text-primary">
                            <p class="card-text" style="color: black;">Primeiro você irá se auto avaliar de acordo com
                                as competências comportamentais</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center" style="background: #F4A460;">Avaliação Superior</div>
                        <div class="card-body text-primary">
                            <p class="card-text" style="color: black;">Agora é a sua vez de avaliar o seu superior de
                                acordo com as competências comportamentais</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center" style="background: #F4A460;">Avaliação Pares</div>
                        <div class="card-body text-primary">
                            <p class="card-text" style="color: black;">Agora é a sua vez de avaliar seus pares de acordo
                                com as competências comportamentais</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-primary mb-3" style="max-width: 18rem;">
                        <div class="card-header text-center" style="background: #F4A460;">Avaliação Equipe</div>
                        <div class="card-body text-primary">
                            <p class="card-text" style="color: black;">Agora é a sua vez de avaliar sua equipe de acordo
                                comas competências comportamentais</p>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-outline-secondary">Responda aqui a sua avaliação 360</button>
        </div>
    @endif







    @if(Auth::user()->ConsultaAtividadeFuncionario())
        <div class="container" style="margin-top: 40px;">

            <h5 style="font-family: Arial, Helvetica, sans-serif;">Atividades - {{Auth::user()->no_usuario}}  </h5>
            <h6 style="font-family: Arial, Helvetica, sans-serif;">Acompanhe aqui suas atividades</h6>

            <div class="row">

                <div class="col-3">
                    <form action="{{route('sessao.atividade')}}" method="post" name="naoIniciada">
                        @csrf
                        <input type="hidden" name="cd_situacao_atividade" value="1">
                        <a href='javascript:naoIniciada.submit()' type="submit" style="width: 266px;">
                            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <h5 class="card-title" style="font-size: 30px;">
                                                <strong> {{$consultaNaoIniciada}} </strong></h5>

                                            <p class="card-text" style="font-size: 18px;">Não iniciada</p>
                                        </div>
                                        <div class="col-4">
                                            <p><i class="fas fa-bars fa-3x"></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </form>
                </div>
                <div class="col-3">
                    <form action="{{route('sessao.atividade')}}" method="post" name="EmAtendimento">
                        @csrf
                        <input type="hidden" name="cd_situacao_atividade" value="2">
                        <a href='javascript:EmAtendimento.submit()' type="submit" style="width: 266px;">
                            <div class="card text-white mb-3" style="max-width: 18rem; background: #F4A460;">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <h5 class="card-title" style="font-size: 30px; color: white;">
                                                <strong> {{$consultaEmAtendimento}} </strong></h5>

                                            <p class="card-text" style="font-size: 18px; color: white;"><strong>Em
                                                    atendimento</strong></p>
                                        </div>
                                        <div class="col-4">
                                            <p style="color: white;"><i class="fas fa-user fa-3x"></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </form>
                </div>
                <div class="col-3">
                    <form action="{{route('sessao.atividade')}}" method="post" name="concluidas">
                        @csrf
                        <input type="hidden" name="cd_situacao_atividade" value="3">
                        <a href='javascript:concluidas.submit()' type="submit" style="width: 266px;">
                            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <h5 class="card-title" style="font-size: 30px;">
                                                <strong> {{$consultaConcluida}} </strong></h5>

                                            <p class="card-text" style="font-size: 18px;">concluidas</p>
                                        </div>
                                        <div class="col-4">
                                            <p><i class="fas fa-check fa-3x"></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </form>
                </div>
                <div class="col-3">
                    <form action="{{route('sessao.atividade')}}" method="post" name="canceladas">
                        @csrf
                        <input type="hidden" name="cd_situacao_atividade" value="5">
                        <a href='javascript:canceladas.submit()' type="submit" style="width: 266px;">
                            <div class="card text-white bg-light mb-3" style="max-width: 18rem;">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <h5 class="card-title" style="font-size: 30px;">
                                                <strong> {{$consultaCancelada}} </strong></h5>

                                            <p class="card-text" style="font-size: 18px;">Canceladas</p>
                                        </div>
                                        <div class="col-4">
                                            <p><i class="fas fa-ban fa-3x"></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    @endif





@endsection
