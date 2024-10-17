(function () {
    function init() {
        events();
        config();
        loadDataTableControleAcesso();
        loadDataTableSolicitacoesPendentes();
    }

    function events() {
        $(document).on(
            "click",
            "#btnPesquisarSolicitacoesAcessoPendentes",
            pesquisar
        );
    }

    function config() {
        $("#nr_matricula").mask("00000000");
    }

    function pesquisar() {
        var url = $("#rotaSolicitacoesPendentes").val();
        if ($("#tp_consulta").val() == 2) {
            var url = $("#rotaControleAcesso").val();
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                cd_modulo: $("#cd_modulo").val(),
                ds_area: $("#ds_area").val(),
                nr_matricula: $("#nr_matricula").val(),
            },
            success: function (retorno) {
                if ($("#tp_consulta").val() == 1) {
                    populateTableSolicitacaoPendente(retorno);
                } else {
                    populateTableControleAcessso(retorno);
                }
            },
            beforeSend: function () {
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                if ($("#tp_consulta").val() == 1) {
                    $(["#tbSolicitacoesPendentes tbody"].join("")).append(
                        row.join("")
                    );
                } else {
                    $(["#tbControleAcesso tbody"].join("")).append(
                        row.join("")
                    );
                }
            },
        });
    }

    function loadDataTableControleAcesso() {
        var url = $("#rotaControleAcesso").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (retorno) {
                populateTableControleAcessso(retorno);
            },
            beforeSend: function () {
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                $(["#tbControleAcesso tbody"].join("")).append(row.join(""));
            },
        });
    }

    function populateTableControleAcessso(retorno) {
        console.log(retorno);

        $("#tbControleAcesso tbody>tr").remove();
        if (retorno.count > 0) {
            $.each(retorno.data, function (key, value) {
                var informacao =
                    '<a class="btn btn-primary btn-sm" href="gerenciarAcesso/informacoesPerfis/' +
                    value.nr_matricula +
                    '/1"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Informações do Usuário"></i></a>&nbsp';
                var adicionar =
                    '<a class="btn btn-success btn-sm" href="gerenciarAcesso/adicionarPerfil/' +
                    value.nr_matricula +
                    '"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Adicionar Perfil do Usuário"></i></a>&nbsp';
                var deletar =
                    '<a class="btn btn-danger btn-sm" href="gerenciarAcesso/informacoesPerfis/' +
                    value.nr_matricula +
                    '/2" style="display: none;"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Deletar Perfil do Usuário"></i></a>&nbsp';

                var acao = informacao + adicionar + deletar;
                var area =
                    value.ds_area_benner != null ? value.ds_area_benner : "";

                var row = ["<tr>"];
                row.push(
                    '<td class="text-center">' + value.nr_matricula + "</td>"
                );
                row.push("<td>" + value.no_usuario + "</td>");
                row.push('<td class="text-center">' + area + "</td>");
                row.push(
                    '<td class="project-actions text-center">' + acao + "</td>"
                );
                row.push("</tr>");
                $(["#tbControleAcesso tbody"].join("")).append(row.join(""));
            });
            if ($.fn.dataTable.isDataTable("#tbControleAcesso")) {
                table = $("#tbControleAcesso").DataTable();
            } else {
                $("#tbControleAcesso").DataTable({
                    language: {
                        filter: "Pesquisar",
                        lengthMenu: "Mostrando _MENU_ registros por página",
                        zeroRecords: "Nada encontrado",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "Nenhum registro disponível",
                        infoFiltered: "(filtrado de _MAX_ registros no total)",
                        search: "Pesquisar",
                        paginate: {
                            next: "Próximo",
                            previous: "Anterior",
                            first: "Primeiro",
                            last: "Último",
                        },
                    },
                });
            }
        } else {
            var row = ["<tr>"];
            row.push(
                '<td align="center" colspan="5">Nenhum Registro encontrado!</td>'
            );
            row.push("</tr>");
            $(["#tbControleAcesso tbody"].join("")).append(row.join(""));
        }
    }

    function loadDataTableSolicitacoesPendentes() {
        var url = $("#rotaSolicitacoesPendentes").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (retorno) {
                populateTableSolicitacaoPendente(retorno);
            },
            beforeSend: function () {
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                $(["#tbSolicitacoesPendentes tbody"].join("")).append(
                    row.join("")
                );
            },
        });
    }

    function populateTableSolicitacaoPendente(retorno) {
        $("#tbSolicitacoesPendentes tbody>tr").remove();
        if (retorno.count > 0) {
            $.each(retorno.data, function (key, value) {
                console.log(value);
                var informacao =
                    '<a class="btn btn-primary btn-sm" href="gerenciarAcesso/informacoes/' +
                    value.sq_usuario_perfil +
                    '"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Informações do Usuário"></i></a>&nbsp';
                var aprovado =
                    '<a class="btn btn-success btn-sm" href="gerenciarAcesso/aprovar/' +
                    value.sq_usuario_perfil +
                    '"><i class="fas fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="Aprovar Solicitação de Acesso"></i></a>&nbsp';
                var reprovado =
                    '<a class="btn btn-danger btn-sm" href="gerenciarAcesso/reprovar/' +
                    value.sq_usuario_perfil +
                    '"><i class="fas fa-thumbs-down" data-toggle="tooltip" data-placement="top" title="Reprovar  Solicitação de Acesso"></i></a>&nbsp';
                var area =
                    value.ds_area_benner != null ? value.ds_area_benner : "";

                var row = ["<tr>"];
                row.push(
                    '<td class="text-center">' + value.nr_matricula + "</td>"
                );
                row.push("<td>" + value.no_usuario + "</td>");
                row.push('<td class="text-center">' + area + "</td>");
                row.push(
                    '<td class="text-center">' + value.ds_modulo + "</td>"
                );
                row.push(
                    '<td class="project-actions text-center">' +
                        informacao +
                        aprovado +
                        reprovado +
                        "</td>"
                );
                row.push("</tr>");
                $(["#tbSolicitacoesPendentes tbody"].join("")).append(
                    row.join("")
                );
            });
            if ($.fn.dataTable.isDataTable("#tbSolicitacoesPendentes")) {
                table = $("#tbSolicitacoesPendentes").DataTable();
            } else {
                $("#tbSolicitacoesPendentes").DataTable({
                    language: {
                        lengthMenu: "Mostrando _MENU_ registros por página",
                        zeroRecords: "Nada encontrado",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "Nenhum registro disponível",
                        infoFiltered: "(filtrado de _MAX_ registros no total)",
                        search: "Pesquisar",
                        paginate: {
                            next: "Próximo",
                            previous: "Anterior",
                            first: "Primeiro",
                            last: "Último",
                        },
                    },
                });
            }
        } else {
            var row = ["<tr>"];
            row.push(
                '<td align="center" colspan="5">Nenhum Registro encontrado!</td>'
            );
            row.push("</tr>");
            $(["#tbSolicitacoesPendentes tbody"].join("")).append(row.join(""));
        }
    }

    init();
})();
