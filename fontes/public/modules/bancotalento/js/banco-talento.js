$(document).ready(function(e){

});

$('#btnFuncaoPesquisar').click(function(){
    let url = $(this).data('href');
    $.ajax({
        type	: "POST",
        url		: url,
        data	: $("#formularioPesquisarFuncao").serializeArray(),
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
