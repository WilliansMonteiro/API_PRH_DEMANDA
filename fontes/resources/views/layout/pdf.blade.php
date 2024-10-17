<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">
    <link rel="stylesheet" href=" {{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}} ">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="content">
    @yield('content')

</div>
</body>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</html>