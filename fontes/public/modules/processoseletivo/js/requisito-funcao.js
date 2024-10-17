(function () {

    function init() {
        events();
        search();
    }

    function events() {
        $(document).on('click', '#btn-pesquisar-requisito-funcao', search)
        $(document).on('click', '#btn-inativar, #btn-ativar', handleStatus)
    }

    function search() {
        var url = $("#inputRotaPesquisar").val();
        $.ajax({
            type: 'POST',
            url: url,
            data: $('#form-pesquisar-requisitos').serializeArray(),
            success: function (retorno) {

                $('#retorno').html(retorno);
                $('#table-requisitos').DataTable({
                    language: {
                        lengthMenu: "Mostrando _MENU_ registros por página",
                        zeroRecords: "Nada encontrado",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "Nenhum registro disponível",
                        infoFiltered: "(filtrado de _MAX_ registros no total)",
                        search: "Pesquisar",
                        paginate: {
                            next: "Próximo",
                            previous: "Anterior",
                            first: "Primeiro",
                            last: "Último",
                        },
                    },
                });
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function () {
                $('#retorno').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                helper.alertError('Desculpe! Ocorreu um erro.');
            }
        });
    }

    function handleStatus(e) {
        let url = $(this).data('href');
        let status = $(this).data('status');
        let requisito = $(this).data('requisito');
        let msgStatus = 'reativar';

        if (status == 'I') {
            msgStatus = 'inativar';
        }
        let msgTitle = 'Deseja ' + msgStatus + ' o requisito de função?'

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
                    data: {
                        'sq_requisito_funcao_pccr': requisito,
                        'status': status
                    },
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
