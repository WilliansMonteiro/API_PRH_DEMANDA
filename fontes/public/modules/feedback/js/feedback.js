(function () {

    
    $('[name="dt_inicio_feedback_recebido"]').mask('00/00/0000');
    $('[name="dt_fim_feedback_recebido"]').mask('00/00/0000');
    $('[name="dt_inicio_feedback_concedido"]').mask('00/00/0000');
    $('[name="dt_fim_feedback_concedido"]').mask('00/00/0000');
    $('[name="dt_prazo"]').mask('00/00/0000');

    $('#btnFeedbackPesquisar').click(function(){

      
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarFeedback").serializeArray(),
            success	: function(retorno) {
                
                $('#retorno').html(retorno);
                $.fn.dataTable.moment('DD/MM/YYYY');
                $('#minhaTabela').DataTable({
                    language:{
                        url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
                    },
                    
                });
                $("#resultadoConsulta").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                console.log('funcionou');
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    $('#btnFeedbackPesquisarConcedido').click(function(){
      
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarFeedbackConcedidos").serializeArray(),
            success	: function(retorno) {
                
                $('#retornoconcedido').html(retorno);
                $.fn.dataTable.moment('DD/MM/YYYY');
                $('#minhaTabelaconcedido').DataTable({
                    language:{
                        url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
                    },
                    
                });
                $("#resultadoConsultaconcedido").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregarconcedido').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                console.log('funcionou');
                $('#carregarconcedido').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });


    init();


})();
