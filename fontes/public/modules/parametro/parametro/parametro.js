$(document).ready(function(e){
    $('#cd_modulo').mask('00');

    $('#btnParametroPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarParametro").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    $(document).on('click', '#btnParametroExcluir', function(e){
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
                    type    : "DELETE",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    }

                });
            }
        });
    });
});
