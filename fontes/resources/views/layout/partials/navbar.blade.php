<!-- Navbar -->
<style type = "text/css">
    .testeNavbar {
        width: 580px;
        height: 250px;
        overflow-y: scroll;
    }

    .nomeNavbar {
        font-style: italic;
        font-weight: bold;
        color: lightslategray;
    }

    .textonavbar {
        font-family: 'Times New Roman', Times, serif;
        text-align: left;
    }

    .dropdown {
        position: relative;
        display: inline-block;

    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 580px;
        z-index: 1;
        right: 0;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 5px;
        text-decoration: none;
        display: block;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .cardNavbar:hover {
        background-color: #1376E0;
    }


</style>

<nav class = "main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class = "navbar-nav">
        <li class = "nav-item">
            <a class = "nav-link" data-widget = "pushmenu" href = "#" role = "button"><i class = "fas fa-bars"></i></a>
        </li>
        <li class = "nav-item d-none d-sm-inline-block">
            <a href = "{{route("home")}}" class = "nav-link">Plataforma de Gestão de Recursos Humanos</a>
        </li>
    </ul>
    <ul class = "navbar-nav ml-auto" style = "margin-left: 50px;">

    </ul>

    <ul class = "navbar-nav ml-auto">
        @php
        $titleNovoAcesso = 'Novo Acesso';
        $routeNovoAcesso = route('novoAcesso') ;
        @endphp

        @if(Auth::check() && Auth::user()->canAutorizarAcesso())
            @php
            $title = 'Gerenciar Acesso';
            $route =  route('gerenciarAcesso') ;
            @endphp
            @endif

        @if(Auth::check())

            <li class="nav-item" style="margin-right: 18px;">
                <a href="http://treinamento.brb.com.br/" class = "nav-link" target="_blank">
                    <img src="{{ asset('img/logo-brb-azul.png') }}" alt="" style="max-height: 20px;"> Treinamento
                </a>
            </li>
            <li class="nav-item" style="margin-right: 18px;">
                <a href="https://ead.brb.com.br" class = "nav-link" target="_blank">
                    <img src="{{ asset('img/logo-brb-azul.png') }}" alt="" style="max-height: 20px;"> EAD
                </a>
            </li>
            <li class="nav-item" style="margin-right: 18px; ">
                <a href="https://myplace.brb.com.br/RHWEB/Login" class = "nav-link" target="_blank">
                    <img src="{{ asset('img/logo-brb-azul.png') }}" alt="" style="max-height: 20px;"> My Place
                </a>
            </li>
            <li class="nav-item" style="margin-right: 18px;">
                <a href="https://universidade.brb.com.br/" class = "nav-link" target="_blank">
                    <img src="{{ asset('img/logo-brb-azul.png') }}" alt="" style="max-height: 20px;"> Universidade
                </a>
            </li>
            <li class="nav-item" style="margin-right: 128x;">
                <a href="http://gedep.brb.com.br/aap/index.asp" class = "nav-link" target="_blank">
                    <img src="{{ asset('img/logo-brb-azul.png') }}" alt="" style="max-height: 20px;"> AAP
                </a>
            </li>
            @if(Auth::check() && Auth::user()->canAutorizarAcesso())
                <li class = "nav-item">
                    <a href = "{{ $route }}" title = "{{ $title }}" class = "nav-link">
                        <i class = "fa fa-lock"></i>
                    </a>
                </li>
            @endif


            <li>
                <a href = "{{ $routeNovoAcesso }}" title = "{{ $titleNovoAcesso }}" class = "nav-link">
                    Solicitar
                </a>
            </li>

            <li class = "nav-item">
                <a class = "nav-link" title = "Sair" href = "{{ route('logout') }}"
                        onclick = "event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class = "fa fa-sign-out-alt"></i> {{ __('Sair') }}
                </a>
                <form id = "logout-form" action = "{{ route('logout') }}" method = "POST" style = "display: none;">
                    @csrf
                </form>
            </li>
        @endif
    </ul>


</nav>
<!-- /.navbar -->

