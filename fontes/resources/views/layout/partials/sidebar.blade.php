<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: #1376e0">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link logo-switch">
        <img src="{{ asset('img/logo_banco.png') }}" alt="BRB - Banco de Brasília" style="margin-top: -10px;max-height: 70px;" class="brand-image-xl logo-xl">
        <img src="{{ asset('img/logo_branco.png') }}" alt="BRB - Banco de Brasília" class="brand-image-xs logo-xs">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        @if (Auth::check() && (Auth::user()->st_primeiro_acesso == 'N' && Auth::user()->hasAcessoAprovado()))

            <!-- DADOS USUÁRIO -->
            <div class="user-panel mt-3 pb-3 mb-3 tipo-display">
                    @if (!is_null(Auth::user()->cd_anexo_brs))
                        <img
                            src ="{{ \Modules\Usuario\Entities\Usuario::imgUsuarioBRS(Auth::user()->cd_anexo_brs) }}"
                            class="imagem-usuario-perfil img-responsive img-circle"
                            alt="Foto de perfil do usuário">
                    @elseif (Illuminate\Support\Str::contains(Auth::user()->no_arquivo_foto, 'imagem_perfil'))
                        <img src = "{{ asset('storage/'.Auth::user()->no_arquivo_foto) }}" class="imagem-usuario-perfil img-responsive img-circle">
                    @else
                        <img src = "{{ asset('img/'.Auth::user()->no_arquivo_foto) }}" class="imagem-usuario-perfil img-responsive img-circle">
                    @endif
                <div class="info text-center pl-0">
                    <a href="{{route('usuario.meu.perfil.alterar')}}"><div class="d-block text-center text-white">{{Auth::user()->no_usuario}}</div></a>
                    @if(Auth::user()->dadosBennerAtivo()->count() > 0 )
                        <a href="{{route('usuario.meu.perfil.alterar')}}"> <div class="d-block text-center text-white">{{ Auth::user()->dadosBennerAtivo()[0]->ds_area_benner }}</div></a>
                    @else
                    <a href="{{route('usuario.meu.perfil.alterar')}}"> <div class="d-block text-center text-white">{{ \Modules\SAA\Entities\VwDependencia::retorna_sigla_lotacao() }}</div></a>
                    @endif
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">

                <ul id="ul-modulo" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview"
                    role="menu"
                    data-accordion="false">
                    @php
                        $statusHome = '';
                        if($uri == 'home'){
                        $statusHome = 'menu-open';
                        }
                    @endphp

                    <li class="nav-item has-treeview {{ $statusHome }}">
                        <a href="{{ route('home') }}"
                           class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p class="text-white">
                                Home
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa fa-user"></i>
                            <p class="text-white">
                                Minha Conta
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: none;">
                            <li class="nav-item">
                                <a href="{{route('usuario.meu.perfil.alterar')}}" class="nav-link">
                                    <i class="fa fa-address-card nav-icon"></i>
                                    <p class="text-white">Meu Usuário</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('usuario.treinamento.visualizar')}}" class="nav-link">
                                    <i class="fa fa-file nav-icon"></i>
                                    <p class="text-white">Treinamento</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    @if($dsModuloDashboard != null && $routedashboard != null)
                        @php
                            $statusDashboard = '';
                            if(Str::contains($uri, ['dashboard'])){
                            $statusDashboard = 'menu-open';
                            }
                        @endphp
                        <li class="nav-item has-treeview {{ $statusDashboard }}">
                            <a href="{{ $routedashboard }}"
                               class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p class="text-white">
                                    {{$dsModuloDashboard}}
                                </p>
                            </a>
                        </li>
                        @forelse($moduloMenuDinamico as $modulo)
                            @php
                                $statusModulo = '';
                                if($cdModulo == $modulo->cd_modulo){
                                $statusModulo = 'menu-open';
                                }
                            @endphp
                            <li id="li-modulo-{{$modulo->ds_modulo}}" class="nav-item has-treeview {{$statusModulo}}">
                                <a href="#"
                                   class="nav-link"  style="background-color: #1376e0">
                                    <i class="nav-icon"></i>
                                    <p class="text-white">
                                        Menu de {{$modulo->ds_modulo}}
                                    </p>
                                </a>
                                @foreach ($menuDinamicoNivel1 as $nivel1)
                                    @if($modulo->cd_modulo == $nivel1->cd_modulo)
                                        @php
                                            $rotaNivel1 = explode('/',$nivel1->ds_rota);
                                            $statusLiNivel1 = '';
                                            $statusUlNivel1 = '';

                                            if($rotaNivel1[2] ==  $uriRotaNivel1){
                                                $statusLiNivel1 = 'menu-open';
                                            }
                                        @endphp

                                        <ul id="ul-nivel1" class="nav nav-treeview">
                                            @if($nivel1->total_filho > 0)
                                                <!-- Menu com submenu-->
                                                <li id="li-nivel1" style="background: #1376e0"
                                                    class="nav-item has-treeview  {{ $statusLiNivel1 }}">
                                                    <a href="#"
                                                       class="nav-link" style="background: #1376e0">
                                                        <i class="nav-icon {{$nivel1->no_icone != '' ? $nivel1->no_icone : 'far fa-circle'}}"></i>

                                                        <p class="text-white">
                                                            {{$nivel1->no_item_menu}}
                                                            <i class="fas fa-angle-left right"></i>
                                                        </p>
                                                    </a>
                                                    <ul id="ul-nivel2" class="nav nav-treeview">

                                                        @foreach($menuDinamicoNivel2 as $nivel2)
                                                            @php
                                                                $rotaNivel2 = explode('/',$nivel2->ds_rota);
                                                                $statusLiNivel2 = '';

                                                                if(isset($rotaNivel2[3]) && $rotaNivel2[3] ==  $uriRotaNivel2){
                                                                $statusLiNivel2 = 'menu-open';
                                                                }
                                                            @endphp
                                                            @if($nivel2->sq_item_menu_pai == $nivel1->sq_item_menu)
                                                                @if($nivel2->total_filho > 0)
                                                                    <!-- Menu com submenu-->
                                                                    <li id="li-nivel2 {{$nivel2->no_icone != '' ? $nivel2->no_icone : 'far fa-circle'}}"
                                                                        style="background-color: #1376e0"
                                                                        class="nav-item has-treeview {{ $statusLiNivel2 }}">
                                                                        <a href="#" class="nav-link" style="background: #1376e0">
                                                                            <i class="nav-icon {{$nivel2->no_icone}}"></i>

                                                                            <p class="text-white">{{$nivel2->no_item_menu}}<i
                                                                                        class="fas fa-angle-left right"></i></p>
                                                                        </a>
                                                                        <ul id="ul-nivel3" class="nav nav-treeview">
                                                                            @foreach($menuDinamicoNivel3 as $nivel3)
                                                                                @php
                                                                                    $rotaNivel3 = explode('/',$nivel3->ds_rota);
                                                                                    $statusLiNivel3 = '';

                                                                                    if(isset($rotaNivel3[4]) && $rotaNivel3[4] ==
                                                                                    $uriRotaNivel3){
                                                                                    $statusLiNivel3 = 'active';
                                                                                    }
                                                                                @endphp
                                                                                @if($nivel3->sq_item_menu_pai == $nivel2->sq_item_menu)
                                                                                    <li id="li-nivel3 {{$nivel3->no_icone != '' ? $nivel3->no_icone : 'far fa-circle'}}"
                                                                                        class="nav-item">
                                                                                        <a class="nav-link  {{$statusLiNivel3}}"
                                                                                           href="{{ $nivel3->ds_rota }}">
                                                                                            <i class="nav-icon {{$nivel3->no_icone}}"></i>
                                                                                            <p class="text-white">
                                                                                                {{$nivel3->no_item_menu}}
                                                                                            </p>

                                                                                        </a>
                                                                                    </li>
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @else
                                                                    @php
                                                                        $rotaNivel2 = explode('/',$nivel2->ds_rota);
                                                                        $statusLiNivel2 = '';

                                                                        if(isset($rotaNivel2[3]) && $rotaNivel2[3] ==  $uriRotaNivel2){
                                                                        $statusLiNivel2 = 'active';
                                                                        }
                                                                    @endphp   <!-- Menu Simples-->
                                                                    <li id="li-nivel2"
                                                                        class="nav-item {{ $statusLiNivel2 }}" style="background: #1376e0"
                                                                        style="background-color: #1376e0">
                                                                        <a class="nav-link  {{ $statusLiNivel2}}"
                                                                           href="{{ $nivel2->ds_rota }}">
                                                                            <i class="nav-icon {{$nivel2->no_icone != '' ? $nivel2->no_icone : 'far fa-circle'}}"></i>
                                                                            <p class="text-white">
                                                                                {{$nivel2->no_item_menu}}
                                                                            </p>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endif

                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else

                                                <li style="background-color: #1376e0" class="nav-item">
                                                    <a class="nav-link {{ ($nivel1->ds_rota == $uri || $rotaNivel1[2] ==  $uriRotaNivel1) ? 'active' : ''}}"
                                                       href="{{ $nivel1->ds_rota }}">
                                                        <i class="nav-icon {{$nivel1->no_icone}}"></i>

                                                        <p class="text-white">{{$nivel1->no_item_menu}}</p>
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                @endforeach
                            </li>
                        @empty

                        @endforelse
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        @endif

        <!-- /.sidebar -->
</aside>


