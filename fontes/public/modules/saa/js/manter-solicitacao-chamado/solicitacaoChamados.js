$(document).ready(function () {
    // Função que faz a requisição para pesquisa de sistemas.
    // $('#btn-pesquisar-solicitacao-chamados').click(function () {
    //     let url = $(this).data('href');
    //     $.ajax({
    //         type: 'POST',
    //         url: url,
    //         data: $('#formularioPesquisarChamados').serializeArray(),
    //         success: function (retorno) {
    //             $('#retorno').html(retorno);
    //             $('#table-chamados').DataTable({
    //                 language: {
    //                     lengthMenu: "Mostrando _MENU_ registros por página",
    //                     zeroRecords: "Nada encontrado",
    //                     info: "Mostrando página _PAGE_ de _PAGES_",
    //                     infoEmpty: "Nenhum registro disponível",
    //                     infoFiltered: "(filtrado de _MAX_ registros no total)",
    //                     search: "Pesquisar",
    //                     paginate: {
    //                         next: "Próximo",
    //                         previous: "Anterior",
    //                         first: "Primeiro",
    //                         last: "Último",
    //                     },
    //                 },
    //             });
    //             $("#resultadoConsulta").show("speed,callback");
    //         },
    //         beforeSend: function () {
    //             $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    //         },
    //         complete: function () {
    //             $('#carregar').html("");
    //         },
    //         error: function (XMLHttpRequest, textStatus, errorThrown) {
    //             $('#carregar').html("");
    //             helper.alertError('Desculpe! Ocorreu um erro.');
    //         }
    //     });
    // });

    $(document).on('click', '#btn-exibir-modal-mensagens', function (e) {
        let url = $(this).data('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            success: function ({ data }) {
                $('#tx_interacao_mensagens').html(data.tx_interacao_mensagens);
                $('#modalMensagens').modal('show');
            }

        });
    });

    $(document).on('click', '#btn-exibir-modal-dados-chamado', function (e) {
        let url = $(this).data('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            success: function ({ data }) {
                $('#modalDadosChamado').modal('show');
                $('#dataModalChamado').html(data);
            }

        });
    });

    $(document).on('click', '#btn-exibir-modal-dados-requisicao', function (e) {
        let url = $(this).data('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            success: function ({ data }) {
                $('#modalDadosRequisicao').modal('show');
                $('#dataModalRequisicao').html(data);
            }

        });
    });

    $(document).on('change', '#sq_tipo_chamado', function (e) {
        const sqTipoChamado = e.target.value;
        const url = `${window.location.origin}/modulo-saa/usuarios-gestor/manter-solicitacao-chamados/buscarTipoChamado/${sqTipoChamado}`;
        const dsUsuario = $('#ds_usuario').val();

        if (!sqTipoChamado) {
            $('#tx_solicitacao_chamado').val(dsUsuario);
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: url,
            success: function (response) {
                const txTipoChamado = response?.tx_tipo_chamado+"\n\n"+dsUsuario;

                if (txTipoChamado) {
                    $('#tx_solicitacao_chamado').val(txTipoChamado);
                }
            }, beforeSend: function () {
                $('#tx_solicitacao_chamado').prop('disabled', true);
            },
            complete: function () {
                $('#tx_solicitacao_chamado').prop('disabled', false);
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#tx_solicitacao_chamado').prop('disabled', false);
                helper.alertError('Desculpe! Ocorreu um erro ao buscar o texto do tipo de chamado.');
            }

        });
    });

    $("#form-manter-chamado").submit(function (e){
        e.preventDefault();
        let url = $(this).attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: $(this).serializeArray(),
            success: function(retorno){
                setTimeout(function (){
                    window.close();
                }, 3000)
            },
            beforeSend: function(){
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#carregar').html("");
                helper.alertError('Desculpe! Ocorreu um erro.');
            }
        });
    });

    function populateTableChamados(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar_mensagem_chamado = $("#input-rota-modal-mensagens-chamado").val();
        var rota_visualizar_dados_chamado    = $("#input-rota-modal-dados-chamado").val();
        var rota_visualizar_dados_requisicao = $("#input-rota-modal-dados-requisicao").val();

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-chamados')) {
            table = $('#tb-chamados').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!
        $('#tb-chamados').DataTable({

            "data": retorno,
            "columnDefs":[
                {width: "150px", targets: 8}
            ],
            "columns": [
                {
                    "render": function (data, type, row, meta) {                           
                        return row.sq_solicitacao_chamado                          
                    }
                },
                {
                    "render": function (data, type, row, meta) {                           
                        return row.ds_solicitacao_chamado  
                    }
                },
                {
                    "render": function (data, type, row, meta) {                           
                        return row.no_tipo_chamado  
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.nr_interacao
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.nr_requisicao
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.ds_interacao_status
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return (row.dt_interacao_abertura) ? row.dt_interacao_abertura : '';
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return (row.dt_interacao_atualizacao) ? row.dt_interacao_atualizacao : '';
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var visualizarMensagemChamado   = rota_visualizar_mensagem_chamado  + '/' + row.sq_solicitacao_chamado;
                        var visualizarDadosChamado      = rota_visualizar_dados_chamado  + '/' + row.sq_solicitacao_chamado;
                        var visualizarDadosRequisicao      = rota_visualizar_dados_requisicao   + '/' + row.sq_solicitacao_chamado;

                        var mensagemChamado  = '<a class="btn btn-primary btn-sm" id="btn-exibir-modal-mensagens" data-href="' + visualizarMensagemChamado + '"><i class="fas fa-eye" data-toggle="tooltip" data-placement="top" title="Visualizar Mensagens"></i></a>&nbsp;';
                        var dadosChamado  = '<a class="btn btn-success btn-sm" id="btn-exibir-modal-dados-chamado" data-href="' + visualizarDadosChamado + '"><i class="fas fa-database" data-toggle="tooltip" data-placement="top" title="Visualizar Dados da IT"></i></a>&nbsp;';
                        var dadosRequisicao  = '<a class="btn btn-primary btn-sm" id="btn-exibir-modal-dados-requisicao" data-href="' + visualizarDadosRequisicao + '"><i class="fas fa-inbox" data-toggle="tooltip" data-placement="top" title="Visualizar Dados da RS"></i></a>&nbsp;';

                        if(row.sq_solicita_chamado_requisicao){
                            var botoes = mensagemChamado + dadosChamado + dadosRequisicao;
                        }else{
                            var botoes = mensagemChamado + dadosChamado;
                        }

                        return botoes;
                    }

                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
            order:[[3,'asc']],
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

    $(document).on('click', '#pesquisarChamados', function(e){
        $("#fdematric").val($(this).parent().attr('value'));
        const fdematric = $("#fdematric").val();
        const urlNovoChamado = $('#urlNovoChamado').val();
        
        $('#btn-pesquisar-solicitacao-chamados').attr('data-href', $(this).parent().attr('data-href'));
        $('#btn-novo').attr("href", urlNovoChamado+ "/" + fdematric);

        $('#pesquisarChamadosModal').modal('show');

    });

    $(document).on('click', '#btn-pesquisar-solicitacao-chamados', function(e){
        let url = $(this).data('href');
        let form = $("#formularioPesquisarChamados").serializeArray();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type    : "GET",
            url     : url,
            dataType: 'json',
            data: form,
            success : function(retorno) {
              console.log(retorno)
              populateTableChamados(retorno);
            },
            beforeSend : function() {
                $('#carregar-chamados').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete : function() {
                $('#carregar-chamados').html("");
            }

        });
    });

    $('#pesquisarChamadosModal').on('hidden.bs.modal', function (){
        $('#tb-chamados').DataTable().clear().destroy();
        console.log("Fechou modal");
    });

    $(document).on('click', '#btn-exibir-modal-mensagens', function (e) {
        let url = $(this).data('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function ({ data }) {
                $('#tx_interacao_mensagens').html(data.tx_interacao_mensagens);
                $('#modalMensagens').modal('show');
            }

        });
    });

    $(document).on('click', '#btnPesquisaRapida', function(e){
        var tr = $(this).closest("tr");
        var tbIt = tr.next("#tbUsuarioIt");
        var td = "";

        if(tbIt.length){
            tbIt.remove();          
        }else{

            var url = $(this).data('href');
            console.log(url)
            $.ajax({
                type: "GET",
                url: url,
                dataType: 'json',
                success: function (retorno) {
                    console.log(retorno)
                    if(retorno.length > 0){
                        $.each(retorno, function(key, value){
                            if(value.nr_interacao == null){
                                value.nr_interacao = "Nenhuma IT";
                            }else{
                                value.nr_interacao = "IT"+value.nr_interacao;
                            }

                            if(value.nr_requisicao == null){
                                value.nr_requisicao = "Nenhuma RS";
                            }else{
                                value.nr_requisicao = "RS"+value.nr_requisicao;
                            }
                            td += "<tr>"+
                                "<td>"+ value.nr_interacao +"</td>"+
                                "<td>"+ value.nr_requisicao +"</td>"+
                            "</tr>";
                        });
                    }else{
                        td = "<tr>"+
                            "<td valign='top' colspan='2' class='dataTables_empty'>Nada encontrado</td>"
                        "</tr>";
                    }

                },
                beforeSend: function () {
                    $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                },
                complete: function () {
                    $('#carregar').html("");

                    var detalhes = '<table class="table table-striped" id="tbUsuarioIt">'+
                        '<thead>'+
                            '<tr>'+
                                '<th>IT</th>'+
                                '<th>RS</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody>'+
                            td+
                        '</tbody>'
                    '</table>';


                    tr.after(detalhes);
                },

            });    
        }
    });

});
