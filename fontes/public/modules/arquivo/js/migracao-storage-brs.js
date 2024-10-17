$(document).ready(function(){

    $('#btn-migracao').on('click', function(e) {
        e.preventDefault();
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#form-migracao-storage-brs").serializeArray(),
            success	: function(retorno) {
                console.log(retorno);
                if (retorno.status) {
                    Swal.fire({
                        title: 'Sucesso.',
                        text: 'Migração realizada com sucesso.',
                        icon: 'success'
                    });
                } else {
                    Swal.fire({
                        title: 'Aviso!',
                        text: retorno.msg,
                        icon: 'warning'
                    });
                }
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> MIGRANDO...");
            },
            complete: function(){
                $('#carregar').html("");
            },
            error	: function(retorno, XMLHttpRequest, textStatus, errorThrown) {
                console.log(retorno.msg);
                Swal.fire({
                    title: 'Erro.',
                    text: 'Desculpe, ocorreu um erro durante a migração.',
                    icon: 'error'
                });
            }
        });
    });
});
