<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PRH - Plataforma de RH</title>

    <!-- Favicon -->
    <!-- <link rel="shortcut icon"   type="image/x-icon" href="{{ env('APP_URL') }}/img/icone_brb.ico"/> -->
    

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="{{asset('css/googleapis.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <!-- <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <!-- jQuery UI -->
    <link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-iconpicker-1.10.0/dist/css/bootstrap-iconpicker.min.css') }}">
    <!-- Custom Style - PRH -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="stylesheet" href=" {{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}} ">
    <link rel="stylesheet" href=" {{asset('plugins/select2/css/select2.css')}} ">
    <link rel="stylesheet" href=" {{asset('plugins/select2/css/select2.min.css')}} ">
    <link rel="stylesheet" href=" {{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.css')}} ">
   



    @yield('css')
</head>
