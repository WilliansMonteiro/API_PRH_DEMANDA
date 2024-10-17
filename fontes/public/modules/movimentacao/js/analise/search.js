(function () {

    function init() {
        events();
        config();
    }

    function events() {
        $("#btnModuloPesquisar").on("click", pesquisar);
    }

    function config() {
        $("#dt_inicio").datepicker().mask("00/00/0000");
        $("#dt_fim").datepicker().mask("00/00/0000");
    }

    function pesquisar() {
        var url = getUrl();
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarMovimentacao").serializeArray(),
            success: function (retorno) {
                populateTableSolicitacao(retorno);
                $('.excel').show();
            },
            beforeSend: function () {
                $('#tbSolicitacao').DataTable().clear().destroy();
                var row = ['<tr>'];
                row.push('<td align="center" colspan="7"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tbSolicitacao tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },
        });
    }


    function populateTableSolicitacao(retorno) {

        if ($.fn.dataTable.isDataTable('#tbSolicitacao')) {
            table = $('#tbSolicitacao').DataTable();
            table.destroy();
        }

        $('#tbSolicitacao').DataTable({
            "data": retorno,
            "columnDefs": [
                {className: "text-center", targets: [0,4,5,6]},
                {className: "text-left", targets: [1,2,3]},
                {width    : "20%", targets: [1,2,5,6]},
                {width    : "10%", targets: [0,3,4]},
            ],
            "columns": [
                {"data": getColunaName()[0]},
                {"data": getColunaName()[1]},
                {"data": getColunaName()[2]},
                {"data": getColunaName()[3]},
                {"data": getColunaName()[4]},
                {"data": getColunaName()[5]},
                {
                    "render": function (data, type, row, meta) {

                        var tipoSolicitacao = $('#tp_solicitacao').val();

                        if(tipoSolicitacao == 1){
                            return botoesMovimentacao(row)
                        }else if(tipoSolicitacao == 2){
                            return botoesAdicaoTemporaria(row)
                        }else{
                            return botoesMovimentacaoSimples(row)
                        }

                    }
                },

            ],

            "language": {
                "lengthMenu": "Mostrando _MENU_ registros por página",
                "zeroRecords": "Nada encontrado",
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
        });
    }

    //Botões de Analise do Movimentação
    function botoesMovimentacao(row){
        var rotaVisualizar   = $("#inputRotaVisualizar").val()  + '/' + row.sq_solicitacao_movimentacao;
        var rotaAnalisar     = $("#inputRotaAnalisar").val()  + '/' + row.sq_solicitacao_movimentacao;
        var visualizar       = '<a class="btn btn-small btn-primary" href="' + rotaVisualizar + '"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="Visualizar Histórico da Solicitação"></i></a>&nbsp;';
        var analisar         = '<a class="btn btn-small btn-success disabled" ><i class="fas fa-user-check" data-toggle="tooltip" data-placement="top" title="Analisar Solicitação"></i></a>&nbsp;';
        if(row.cd_area_atual == $('#cdAreaGestor').val() || row.cd_area_atual == 11612 || row.cd_area_atual == 16012){
            var analisar         = '<a class="btn btn-small btn-success" href="' + rotaAnalisar + '"><i class="fas fa-user-check" data-toggle="tooltip" data-placement="top" title="Analisar Solicitação"></i></a>&nbsp;';
        }
        var botoes           = visualizar + analisar;

        return botoes;
    }

    //Botões de Analise do Adição Temporára
    function botoesAdicaoTemporaria(row){
        var rotaVisualizar   = $("#inputRotaVisualizarAdicaoTemporaria").val()  + '/' + row.sq_solic_adicao_temporaria;
        var rotaAnalisar     = $("#inputRotaAnaliseAdicaoTemporaria").val()  + '/' + row.sq_solic_adicao_temporaria;
        var visualizar       = '<a class="btn btn-small btn-primary" href="' + rotaVisualizar + '"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="Visualizar Histórico da Solicitação"></i></a>&nbsp;';
        var analisar         = '<a class="btn btn-small btn-success" href="' + rotaAnalisar + '"><i class="fas fa-user-check" data-toggle="tooltip" data-placement="top" title="Analisar Solicitação"></i></a>&nbsp;';
        
        //Caso esteja Concluída ou Reprovada, não mostra o botão de Analisar
        if((row.historico_atual[0].status.cd_status_solicitacao == 7) || (row.historico_atual[0].status.cd_status_solicitacao == 6)){
            analisar = "";
        }
        var botoes           = visualizar + analisar;

        return botoes;
    }

    //Botões de Analise do Movimentação Simples
    function botoesMovimentacaoSimples(row){
        var rotaVisualizar   = $("#inputRotaVisualizarMovimentacaoSimples").val()  + '/' + row.sq_solicitacao_movimentacao;
        var rotaAnalisar     = $("#inputRotaAnaliseMovimentacaoSimples").val()  + '/' + row.sq_solicitacao_movimentacao;
        var visualizar       = '<a class="btn btn-small btn-primary" href="' + rotaVisualizar + '"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="Visualizar Histórico da Solicitação"></i></a>&nbsp;';
        var analisar         = '<a class="btn btn-small btn-success" href="' + rotaAnalisar + '"><i class="fas fa-user-check" data-toggle="tooltip" data-placement="top" title="Analisar Solicitação"></i></a>&nbsp;';

        //Caso esteja Concluída ou Reprovada, não mostra o botão de Analisar
        if((row.historico_atual[0].status.cd_status_solicitacao == 7) || (row.historico_atual[0].status.cd_status_solicitacao == 6)){
            analisar = "";
        }
        var botoes           = visualizar + analisar;

        return botoes;
    }

    //Colunas Dinâmicas Movimentação/Adição
    function getColunaName(){
        var tipoSolicitacao = $('#tp_solicitacao').val();
        if(tipoSolicitacao == 1){
           return ['sq_solicitacao_movimentacao','tipo_solicitacao','no_usuario','no_status_solicitacao','sg_dependencia','dt_solicitacao'];
        }else if(tipoSolicitacao == 2){
           return ['sq_solic_adicao_temporaria','tipo_justificativa.tipo_solicitacao.no_tipo_solicitacao','empregado_benner.empregado_usuario.no_usuario','historico_atual[0].status.no_status_solicitacao','historico_atual[0].areas.sg_dependencia','dt_inclusao'];
        }else{
           return ['sq_solicitacao_movimentacao','tp_solicitacao_movimentacao','empregados.no_usuario','historico_atual[0].status.no_status_solicitacao','historico_atual[0].areas.sg_dependencia','dt_inclusao']
        }
    }

    //Recuperando URL
    function getUrl(){
        var tipoSolicitacao = $('#tp_solicitacao').val();
        var rotaListagem = "";

        if(tipoSolicitacao == 1){
            rotaListagem = $('#btnModuloPesquisar').val()
        }
        else if(tipoSolicitacao == 2){
            rotaListagem = $('#inputRotaPesquisarAdicaoTemporaria').val()
        }else{
            rotaListagem = $('#inputRotaPesquisarMovimentacaoSimples').val()
        }

        return rotaListagem;

    }


    init();
})();