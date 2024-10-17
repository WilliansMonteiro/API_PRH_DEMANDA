$(document).ready(function(e){
    $('#btnMetaPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioMeta").serializeArray(),
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


});

//Reset da Pesquisa
$("[type='reset']").click(function(){
    $('#sq_ciclo_avaliativo').val(null).trigger('change');
    $('#cd_dependencia_empresa_rh').val(null).trigger('change');
});
