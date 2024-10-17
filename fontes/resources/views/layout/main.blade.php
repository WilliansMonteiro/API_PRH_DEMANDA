<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    @include('layout.partials.head')
    <body class="hold-transition sidebar-mini layout-fixed">
      
        <div class="wrapper">
            @include('layout.partials.sidebar')
            @include('layout.partials.navbar')
            <div class="{{Auth::check() ? 'content-wrapper' : ''}}">
                @yield('content')
      
            </div>
            @include('layout.partials.footer')
        </div>
    </body>
</html>
