$(document).ready(function() {
    $('#btn-pesquisar-diretoria').click(function() {
        let url = $(this).data('href');
        console.log(url);
        $.ajax({
            type   : 'POST',
            url    :  url,
            data   :  $('#formularioPesquisarDiretorias').serializeArray(),
            success: function(retorno) {
                if(retorno == 1){
                    window.location.reload();
                }
                $('#retorno').html(retorno);
                $('#table-diretoria').DataTable({
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
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function() {
                $('#carregar').html("");
            },
            error   : function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error('Desculpe! Ocorreu um erro.');
            }
        });
    });
});
