$(document).ready(function(e) {

    $('#btnRelatoriosPesquisar').click(function() {

        let url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarRelatorio").serializeArray(),
            success: function(retorno) {
                $('#retorno').html(retorno);
                $('#minhaTabela').DataTable({
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
                    },'aoColumnDefs': [{
                        'bSortable': false,
                        'aTargets': [-1,-2,-3] 
                    }]

                });
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function() {
                console.log('funcionou');
                $('#carregar').html("");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });



    $(document).on('click', '#btnRelatorioExcluir', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
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
                    type: "DELETE",
                    url: url,
                    success: function(retorno) {

                        if (retorno)
                            window.location.reload();
                    }

                });
            }
        });
    });




});