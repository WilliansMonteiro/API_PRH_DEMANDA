// btn-historico-tramitacao
// (function () {

//     var nr_matricula = $("#nr_matricula").val();

//     function init() {
//         events();
//         config();
//     }

//     function events() {
//         $(document).on('click', "#btn-historico-tramitacao", loadModalHistoricoTramitacao);
//     }

//     function config() {
//         $('#tbHistoricoTramitacao').DataTable({});
//     }

//     function loadModalHistoricoTramitacao(){
//         var rota        = $(this).attr('data-href');

//         $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//         });
//         $.ajax({
//             type	: "GET",
//             url		: rota,
//             dataType : 'html',
//             data	: {
//                 sq_processo_seletivo: $('#sq_processo_seletivo').val(),
//                 nr_matricula: nr_matricula
//             },
//             success	: function(retorno) {
//                 $('.loading-historico-tramitacao').html('');
//                 $('.loading-historico-tramitacao').html(retorno);
//             },
//             beforeSend: function() {
//                 $('.loading-historico-tramitacao').html('<img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO');
//             }
//         });
//     }

//     init();
// })();
(function () {

    $(document).on('click', '#btn-historico-tramitacao', function(e) {

        $('#modal-historico-tramitacao').modal('show');
        // let url = $(this).data('href');

        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        // $.ajax({
        //     type   : 'POST',
        //     url    : url,
        //     success: function(retorno) {
        //         $.each(retorno.data, function(key, value) {
        //             var numero_processo_seletivo = value.dn_processo_seletivo;
        //             var nome_processo_seletivo = value.no_processo_seletivo;
        //             var sq_processo_seletivo = value.sq_processo_seletivo;

        //             $('#inativarNumero').html(numero_processo_seletivo);
        //             $('#inativarNome').html(nome_processo_seletivo);
        //             $('#modal-inativar-processo-seletivo').modal('show');
        //             $('#inputInativar').val(sq_processo_seletivo);
        //         });
        //     }
        // });
    });

});
