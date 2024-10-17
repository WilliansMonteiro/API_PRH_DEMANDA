(function () {

    function init() {
        events();
        config();
    }

    function events() {
        $(document).on('click', '#btnSolicitacaoPesquisar', pesquisarSolicitacao);
        $(document).on('click', '#btnExcel', excel);
        //$("#btnExcel").on("click", excel);
    }

    function config() {
        $('#nr_matricula').mask('00000000');
        $('#ds_telefone').mask('(00) 00000-0000');
    }

    function excel() {
        var url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: url,
            data: $("#formTable").serializeArray(),
            success : function(retorno) {
                //alert(JSON.stringify(retorno));
                var url2 = $('#rota_exportacao').val();
                window.open(url2 + '/' + JSON.stringify(retorno));

            }
        });
    }



    function pesquisarSolicitacao() {
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarSolicitacao").serializeArray(),
            success: function (retorno) {
                populateTableSolicitacao(retorno);
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

    function populateTableSolicitacao(retorno){
        var rotaDetalhes    = $("#inputRotaDetalhes").val();
        
        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tbSolicitacao')) {
            table = $('#tbSolicitacao').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!
        $('#tbSolicitacao').DataTable({
            "data": retorno,
            "columnDefs": [
                {className: "text-center", targets: [0,1,3,4,6]},
                {className: "text-left", targets: [2,5]},
                {width    : "20%", targets: [2,5]},
                {width    : "10%", targets: [0,1,3,4,6]},
            ],
            "columns": [
                { "render" : function(data, type, row, meta){
                    var checkbox = '<input id="row_'+row.sq_solicitacao+'" name="solicitacao[]" type="checkbox" value="'+ row.sq_solicitacao + '" />';
                    return checkbox;
                }},
                {"data": "nr_matricula"},
                {"data": "no_usuario"},
                {"data": "ds_funcao_benner"},
                {"data": "ds_area_benner"},
                {"data": "ds_solicitacao"},
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var solicitacaoDetalhes   = rotaDetalhes  + '/' + row.sq_solicitacao;
                        
                        var visualizar  = '<a class="btn btn-info btn-sm" href="' + solicitacaoDetalhes + '"><i class="fas fa-eye" data-toggle="tooltip" data-placement="top" title="Visualizar"></i></a>&nbsp;';
                    
                        var botoes = visualizar;
                        return botoes;
                    }
                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
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



    init();


})();




