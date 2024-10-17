$(document).ready(function(){

    
    $("#dt_inicio").datepicker().mask("00/00/0000");
    $("#dt_fim").datepicker().mask("00/00/0000");



    $('#btnExcel').click(function(){
        var url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: url,
            data: $("#formularioPesquisarMovimentacao").serializeArray(),
            success : function(retorno) {

                var url2 = retorno['rota_exportacao'];
                var tp_solicitacao = JSON.stringify(retorno['tp_solicitacao']);
                var cd_status_solicitacao = JSON.stringify(retorno['cd_status_solicitacao']);
                var dt_fim = retorno['data_format_fim'];
                var dt_inicio = retorno['data_format_inicio'];
                var no_empregado = JSON.stringify(retorno['no_empregado']);

                var dados = tp_solicitacao + '/' + cd_status_solicitacao + '/' + dt_fim + '/' + dt_inicio + '/' + no_empregado;
                //alert(dados);
                window.open(url2 + '/' + dados);
   

            }
        });
    });


    

    $('#btnModuloPesquisar').click(function(){
        var url =  $('#btnModuloPesquisar').val();
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
                        { className: "align-left", "targets": 5, render: function(data){
                            return moment(new Date(data)).format('DD/MM/YYYY');
                         }
                      },
                        {width    : "20%", targets: [1,2,5]},
                        {width    : "10%", targets: [0,3,4]},
                    ],
                    "columns": [
                   { "data": ['sq_acomp_solicitacao_moviment'][0] },
                   { "data": ['tipo_solicitacao'][0] },
                   { "data": ['nome_usuario'][0] },
                   { "data": ['no_status_solicitacao'][0] },
                   { "data": ['sg_dependencia'][0] },
                   { "data": ['dt_inclusao'][0] },
                  
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





    
});