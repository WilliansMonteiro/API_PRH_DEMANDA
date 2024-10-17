<footer class="main-footer" style="background: #1376e0;">
    <strong style="color: white;">&copy; <?php echo date('Y'); ?> <a href="https://www.brb.com.br" style="color: white;">BRB - Banco de Brasília</a>.</strong>
    <div class="float-right d-none d-sm-inline-block">
        <b style="color: white;">Versão <?php echo version('pom.xml'); ?></b>
    </div>
</footer>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- select2 -->
<script src=" {{ asset('plugins/select2/js/select2.js')}} "></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<!-- <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> -->
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- jQuery UI -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/adminlte.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('js/demo.js') }}"></script>
<!-- jQuery Validation -->
<script src="{{ asset('js/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/jquery-validation/localization/messages_pt_BR.min.js') }}"></script>
<!-- jQuery Mask Plugin -->
<script src="{{ asset('js/jquery.mask.min.js') }}"></script>
<!-- jQuery Iconpicker Plugin -->
<script src="{{ asset('plugins/bootstrap-iconpicker-1.10.0/dist/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
<!-- jQuery Datatables Plugin -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src=" {{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}} "></script>
<script src=" {{ asset('plugins/select2/js/select2.full.js')}} "></script>
<!-- funcoes -->
<script src="{{ asset('js/funcoes.js') }}"></script>
<script src="{{ asset('js/helper.js') }}"></script>
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('js/cryptojs-aes-format.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/cryptojs-aes.min.js') }}"></script>

@yield('scripts')
@include('sweetalert::alert')
