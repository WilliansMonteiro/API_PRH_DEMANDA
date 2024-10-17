(function () {
    function init() {
        events();
        document.getElementById("btnGestorPesquisar").click()
    }

    function events() {
        $(document).on('click', '#btnGestorPesquisar', pesquisar_solicitacoes_gestor);
    }
    function pesquisar_solicitacoes_gestor()
    {
        $('#tb-solicitacoes-gestor').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarGestor").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTableSolicitacoesGestor(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb-solicitacoes-gestor tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTableSolicitacoesGestor(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar_solicitacao = $("#input-rota-visualizar-solicitacao").val();
        var rota_analisar_solicitacao   = $("#input-rota-analisar-solicitacao").val();
        var rota_excluir_solicitacao    = $("#input-rota-excluir-solicitacao").val();
        var permissao_gestor            = $("#input-permissao-gestor").val();

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-solicitacoes-gestor')) {
            table = $('#tb-solicitacoes-gestor').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tb-solicitacoes-gestor').DataTable({

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
                        if(row.no_social_prestador === null || row.no_social_prestador === ""){
                            return row.no_prestador
                        }else{
                            return row.no_social_prestador
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.st_cadastramento === "A"){
                            return row.matricula + '-' + row.digito
                        }else{
                            return ""
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.status_case
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var sqVisualizar   = rota_visualizar_solicitacao  + '/' + row.sq_solic_cadastramento;
                        var sqAnalisar   = rota_analisar_solicitacao + '/' + row.sq_solic_cadastramento;
                        var sqExcluir    = rota_excluir_solicitacao   + '/' + row.sq_solic_cadastramento;

                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Visualizar solicitação"></i></a>&nbsp;';
                        if(row.st_cadastramento === "E" && permissao_gestor > 0){
                            var analisar   = '<a class="btn btn-success btn-sm" href="' + sqAnalisar + '"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Analisar solicitação"></i></a>&nbsp;';
                        }else{
                            var analisar   = '<a class="btn btn-success btn-sm" href="' + sqAnalisar + '" style="display:none;"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Analisar solicitação"></i></a>&nbsp;';
                        }
                        if(row.st_cadastramento === "E"){
                            var excluir      = '<a class="btn btn-sm btn-danger" data-href="' + sqExcluir + '" id="btn-excluir-solicitacao-gestor"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir solicitação" style="color:white;"></i></a>&nbsp;';
                        }else{
                            var excluir      = '<a class="btn btn-sm btn-danger" data-href="' + sqExcluir + '" id="btn-excluir-solicitacao-gestor" style="display: none;"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir solicitação" style="color:white;"></i></a>&nbsp;';
                        }



                        var botoes = visualizar + analisar +  excluir;
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


    $(document).on('click', '#btn-excluir-solicitacao-gestor', function(e){
        console.log('clicou');
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir a solicitação?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#e32929',
            confirmButtonColor: '#55a846',
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

    $("#cd_empresa").select2({
        theme: "bootstrap4",
    });
    console.log('chegou aqui publicado');
    $("#cd_situacao").select2({
        theme: "bootstrap4",
    });
    $("#btnLimpar").click(function () {
        // $("select").find("option").prop("selected", function () {
        //         return this.defaultSelected;
        // });
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarGestor").serializeArray(),
            success: function (retorno) {
                $('#cd_empresa_prestadora_combo option:first').prop('selected', true);
                $('#cd_situacao_combo option:first').prop('selected', true);
                $('#cd_empresa_combo option:first').prop('selected', true);
                $('#input_no_prestador').val('');
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
