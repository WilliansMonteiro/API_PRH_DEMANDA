$(document).ready(function() {
    // Função que faz a requisição para pesquisa de formulários
    $('#btn-pesquisar-formulario').click(function() {
        let url = $(this).data('href');
        console.log(url);
        $.ajax({
            type   : 'POST',
            url    :  url,
            data   :  $('#form-pesquisar-formulario').serializeArray(),
            success: function(retorno) {
                if(retorno == 1){
                    window.location.reload();
                }
                $('#retorno').html(retorno);
                $('#table-formularios').DataTable({
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
                    },
                });
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function() {
                $('#carregar').html("");
            },
            error   : function(XMLHttpRequest, textStatus, errorThrown) {
                alert('Desculpe! Ocorreu um erro.');
            }
        });
    });

    // Função que dispara a exclusão do formulário
    $(document).on('click', '#btn-excluir-formulario', function(e){
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
                    type    : "POST",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown, ' Status: ', textStatus, ' Request: ', XMLHttpRequest);
                    }

                });
            }
        });
    });
});
