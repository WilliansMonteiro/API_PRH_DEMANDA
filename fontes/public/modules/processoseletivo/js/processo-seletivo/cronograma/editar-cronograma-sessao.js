$(document).ready(function() {
    // Exclui a etapa do cronograma contido na sessão
    $(document).on('click', '#remove_etapa_cronograma', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja remover a etapa?',
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
                data    : {
                    'indice_grupo': $(this).attr('data-indice-grupo'),
                    'chave_etapa': $(this).attr('data-chave-etapa')
                },
                success : function(retorno) {
                    if (retorno)
                    // console.log('chegou', retorno);
                    Swal.fire({
                        title: 'Etapa removida com sucesso.',
                        text: '',
                        icon: 'success',
                        showCancelButton: false
                    })
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
