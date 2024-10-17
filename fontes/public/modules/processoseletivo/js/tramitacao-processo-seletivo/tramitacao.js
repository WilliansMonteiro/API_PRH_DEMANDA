(function () {

    function init() {
        events();
        // search();
    }

    function events() {
        // $(document).on('click', '#btn-pesquisar-pccr', search)
        $(document).on('click', '#btn-inativar, #btn-ativar', handleStatus)
    }

    // function search() {
    //     var url = $("#inputRotaPesquisar").val();
    //     $.ajax({
    //         type: 'POST',
    //         url: url,
    //         data: $('#form-pesquisar-pccr').serializeArray(),
    //         success: function (retorno) {

    //             $('#retorno').html(retorno);
    //             $.fn.dataTable.moment('DD/MM/YYYY');
    //             $('#table-pccr').DataTable({
    //                 language: {
    //                     url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
    //                 },
    //             });
    //             $("#resultadoConsulta").show("speed,callback");
    //         },
    //         beforeSend: function () {
    //             $('#retorno').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    //             $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    //         },
    //         complete: function () {
    //             $('#carregar').html("");
    //         },
    //         error: function (XMLHttpRequest, textStatus, errorThrown) {
    //             helper.alertError('Desculpe! Ocorreu um erro.');
    //         }
    //     });
    // }

    function handleEnvio(e) {
        let url = $(this).data('href');

        let msgTitle = 'Deseja enviar para aprovação?'

        e.preventDefault();
        Swal.fire({
            title: msgTitle,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#dc3c45',
            confirmButtonColor: '#55a846',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if (result.value) {
                let url = $(this).data('href');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "POST",
                    url: url,
                    success: function (retorno) {
                        if (retorno.status) {
                            helper.alertSuccess(retorno.msg);
                        } else {
                            helper.alertError(retorno.msg);
                        }
                        search();
                    },
                    beforeSend: function () {
                        $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                    },
                    complete: function () {
                        $('#carregar').html("");
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }

                });
            }
        });
    }


    init();
})();
