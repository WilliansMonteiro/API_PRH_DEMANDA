$(document).ready(function(){

    $('#btnModuloPesquisar').click(function(){
        var url =  getUrl();
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarMovimentacao").serializeArray(),
            success	: function(retorno) {
                $("#resultadoConsulta").show("speed,callback");	
                if ( $.fn.dataTable.isDataTable( '#tableSolic' ) ) {
                    table = $('#tableSolic').DataTable();               
                    table.destroy();
                }
                $('#tableSolic').DataTable( {
                    "data": retorno,
                    "columnDefs": [
                        {className: "text-center", targets: [0,4,5]},
                        {className: "text-left", targets: [1,2,3]},
                        {width    : "20%", targets: [1,2,5]},
                        {width    : "10%", targets: [0,3,4]},
                    ],
                    "columns": [
                   { "data": getColunaName()[0] },
                   { "data": getColunaName()[1] },
                   { "data": getColunaName()[2] },
                   { "data": getColunaName()[3] },
                   { "data": getColunaName()[4] },
                   {                            
                    "render": function(data, type, row, meta){
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var tipoSolicitacao = $('#tp_solicitacao').val();

                        if(tipoSolicitacao == 1){
                            var rotaVisualizar   = $("#inputRotaVisualizar").val()  + '/' + row.sq_solicitacao_movimentacao;
                        }else if(tipoSolicitacao == 2){
                            var rotaVisualizar   = $("#inputRotaVisualizarAdicaoTemporaria").val()  + '/' + row.sq_solic_adicao_temporaria;
                        }else{
                            var rotaVisualizar  =  $("#inputRotaVisualizarMovimentacaoSimples").val() + '/' + row.sq_solicitacao_movimentacao;
                        }
                        
                        var botoes = '<a class="btn btn-info btn-sm" href="'+rotaVisualizar+'"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="Visualizar Solicitação"></i></a>';
                        return botoes;
                        
                } 

                },
                  
                  ],
                  //O codigo abaixo define o idioma PT_BR para a dataTable
                  "language": {
                   "lengthMenu": "Mostrando _MENU_ registros por página",
                   "zeroRecords": "Nenhum registro encontrado",
                   "info": "Mostrando página _PAGE_ de _PAGES_",
                   "infoEmpty": "Nenhum registro disponível",
                   "infoFiltered": "(filtrado de _MAX_ registros no total)",
                   "search": "Pesquisar",
                   "paginate": {
                       "next": "Próximo",
                       "previous": "Anterior",
                       "first": "Primeiro",
                       "last": "Último"
                   },
               }
       } );			           
               		           
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

    //Colunas Dinâmicas Movimentação/Adição
    function getColunaName(){
        var tipoSolicitacao = $('#tp_solicitacao').val();

        if(tipoSolicitacao == 1){
           return ['sq_solicitacao_movimentacao','tipo_solicitacao','no_usuario','no_status_solicitacao','sg_dependencia'];
        }else if(tipoSolicitacao == 2){
           return ['sq_solic_adicao_temporaria','tipo_justificativa.tipo_solicitacao.no_tipo_solicitacao','empregado_benner.empregado_usuario.no_usuario','historico_atual[0].status.no_status_solicitacao','historico_atual[0].areas.sg_dependencia'];
        }else{
           return ['sq_solicitacao_movimentacao','tp_solicitacao_movimentacao','empregados.no_usuario','historico_atual[0].status.no_status_solicitacao','historico_atual[0].areas.sg_dependencia']
        }
    }

    //Recuperando URL
    function getUrl(){

        var tpSolicitacao = $('#tp_solicitacao').val();
        var rota = "";

        //Rota para Listagem de Designação
        if(tpSolicitacao == 1){
           rota =  $('#btnModuloPesquisar').val()
        }
        //Rota para Listagem de Adição Temporária
        else if(tpSolicitacao == 2){
           rota = $('#inputRotaPesquisarAdicaoTemporaria').val()
        }
        //Rota para Movimentação Simples
        else{
           rota =  $('#inputRotaPesquisarMovimentacaoSimples').val()
        }

        return rota;

    }

    
});