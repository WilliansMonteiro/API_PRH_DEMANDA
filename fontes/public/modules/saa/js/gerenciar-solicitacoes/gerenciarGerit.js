(function () {
    function init() {
        events();
        document.getElementById("btnGeritPesquisar").click()
    }

    function events() {
        $(document).on('click', '#btnGeritPesquisar', pesquisar_solicitacoes_gerit);
    }

    function pesquisar_solicitacoes_gerit()
    {
        $('#tb-solicitacoes-gerit').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarGerit").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTableSolicitacoesGerit(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb-solicitacoes-gerit tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTableSolicitacoesGerit(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar_solicitacao = $("#input-rota-visualizar-solicitacao").val();
        var rota_analisar_solicitacao   = $("#input-rota-analisar-solicitacao").val();
        var rota_excluir_solicitacao    = $("#input-rota-excluir-solicitacao").val();
        var prestadora                  = "Sem prestadora";
        var aprovado                    = "Aprovado";
        var reprovado                   = "Reprovado";
        var solicitado                  = "Solicitado";
        var em_aprovacao                = "Em aprovação";

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-solicitacoes-gerit')) {
            table = $('#tb-solicitacoes-gerit').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tb-solicitacoes-gerit').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.sq_solic_cadastramento
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.nm_empresa
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.fdenome
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.sg_dependencia
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.pstnome !== '' || row.pstnome !== null){
                            return row.pstnome
                        }else{
                            return prestadora
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.no_prestador
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.st_cadastramento === "S"){
                            return solicitado
                        }
                        if(row.st_cadastramento === "R"){
                            return reprovado
                        }
                        if(row.st_cadastramento === "A"){
                            return aprovado
                        }
                        if(row.st_cadastramento === "E"){
                            return em_aprovacao
                        }

                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var sqVisualizar   = rota_visualizar_solicitacao  + '/' + row.sq_solic_cadastramento;
                        var sqAnalisar   = rota_analisar_solicitacao + '/' + row.sq_solic_cadastramento;
                        var sqExcluir    = rota_excluir_solicitacao   + '/' + row.sq_solic_cadastramento;

                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Detalhes da solicitação"></i></a>&nbsp;';
                        if(row.st_cadastramento === "E"){
                            var analisar   = '<a class="btn btn-success btn-sm" href="' + sqAnalisar + '" style="display: none;"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Analisar solicitação"></i></a>&nbsp;';
                            var excluir      = '<a class="btn btn-sm btn-danger" data-href="' + sqExcluir + '" id="btn-excluir-solicitacao"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir solicitação" style="color:white;"></i></a>&nbsp;';
                            var acoes = visualizar + excluir;
                        }
                        if(row.st_cadastramento === "S"){
                            var analisar   = '<a class="btn btn-success btn-sm" href="' + sqAnalisar + '"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Analisar solicitação"></i></a>&nbsp;';
                            var excluir      = '<a class="btn btn-sm btn-danger" data-href="' + sqExcluir + '" id="btn-excluir-solicitacao"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir solicitação" style="color:white;"></i></a>&nbsp;';
                            var acoes =  visualizar + analisar + excluir;
                        }

                        if(row.st_cadastramento === "A"){
                            var analisar   = '<a class="btn btn-success btn-sm" href="' + sqAnalisar + '" style="display: none;"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Analisar solicitação"></i></a>&nbsp;';
                            var acoes = visualizar;
                        }

                        if(row.st_cadastramento === "R"){
                            var analisar   = '<a class="btn btn-success btn-sm" href="' + sqAnalisar + '" style="display: none;"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Analisar solicitação"></i></a>&nbsp;';
                            var acoes = visualizar;
                        }





                        var botoes = acoes;
                        return botoes;
                    }

                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
            order:[[0,'desc']],
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

    $(document).on('click', '#btn-excluir-solicitacao', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#55a846',
            confirmButtonColor: '#dc3c45',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if (result.value) {
                let url = $(this).data('href');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type    : "POST",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    }

                });
            }
        });
    });

    $("#situacao").select2({
        theme: "bootstrap4",
    });
    $("#empresa").select2({
        theme: "bootstrap4",
    });
    $("#area").select2({
        theme: "bootstrap4",
    });



    $(document).on('click', '#btn-excluir-solicitacao', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#55a846',
            confirmButtonColor: '#dc3c45',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if (result.value) {
                let url = $(this).data('href');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type    : "POST",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    }

                });
            }
        });
    });



    $("#btnLimpar").click(function () {
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarGerit").serializeArray(),
            success: function (retorno) {
                $('#cd_area_combo option:first').prop('selected', true);
                $('#situacao_combo option:first').prop('selected', true);
                $('#cd_empresa_combo option:first').prop('selected', true);
                $('#empresa_lotacao_combo option:first').prop('selected', true);
                $('#no_prestador_input').val('');
                $('#no_solicitante').val('');

                $("select").trigger("change.select2");
            },
            beforeSend: function () {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });

    });


    init();
})();
