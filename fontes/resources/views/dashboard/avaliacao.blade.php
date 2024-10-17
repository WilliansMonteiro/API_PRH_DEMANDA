@extends('layout.main')


@section('content')

@section('scripts')
<!-- Slick Css -->
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/slick/slick-theme.css') }}">
<!-- Slick Js -->
<script type="text/javascript" src="{{ Module::asset('avaliacao:js/slick.js') }}"></script>

<script src="{{ asset('plugins/slick/slick.min.js') }}"></script>
<script src="{{ asset('plugins/slick/slick.min.js') }}"></script>
@endsection
<style>
    .banner{
        width:100%;
    }
    
</style>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><i class="nav-icon fas fa-tachometer-alt"></i> BRB In Home</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="nav-icon fas fa-home"></i> Home</a></li>
                        <li class="breadcrumb-item active"><a href="#">BRB In Home</a></li>
                        <!-- <li class="breadcrumb-item active"><a href="#">Dashboard</a></li> -->
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
<section class="content">
    <div class="container-fluid">

    <div class="card">
                  
        <div class="card-body" style="display: block;">
            <section class="lazy slider" data-sizes="50vw">
                
                    <img class="banner" src="{{ asset('img/avaliacao/brb_inhome_1.jpg') }}">
                    <img class="banner" src="{{ asset('img/avaliacao/brb_inhome_2.jpg') }}">

                    <!--<img class="banner" src="{{ asset('img/avaliacao/Módulo - Página Principal.jpg') }}">
                
                    <img class="banner" src="{{ asset('img/avaliacao/img_avaliacao1.jpg') }}">
                
                    <img class="banner" src="{{ asset('img/avaliacao/Módulo - Avaliação 2.jpg') }}">-->
                
             
               
                
        
        </section>

        </div>
    </div>
    </div>
</section>
@endsection

