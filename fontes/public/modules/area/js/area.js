(function () {
    function init() {
        events();
        config();
    }

    function events() {
        $(document).on("change", "#cd_empresa", loadSiglaAreas);
        $(document).on("click", "#btnAreaPesquisar", pesquisar);
        $(document).on("click", "#btnImportarArea", importarBenner);
    }

    function config() {
        $("#dt_descontinuidade_inicio").datepicker().mask("00/00/0000");
        $("#dt_descontinuidade_fim").datepicker().mask("00/00/0000");
        $("#dt_encerramento_inicio").datepicker().mask("00/00/0000");
        $("#dt_encerramento_fim").datepicker().mask("00/00/0000");
    }

    function loadSiglaAreas() {
        var url = $(this).attr("data-href");

        if (typeof url === "undefined") {
            return;
        }

        $("#cd_dependencia_empresa_rh").prop("disabled", true);
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
                cd_empresa: $(this).val(),
            },
            beforeSend: function () {
                $("#carregar").html(
                    "<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO"
                );
            },
            complete: function () {
                $("#carregar").html("");
            },
            success: function (retorno) {
                $("#cd_dependencia_empresa_rh")
                    .html("Carregando...")
                    .find("option")
                    .remove()
                    .end();
                $("#cd_dependencia_empresa_rh").append(
                    '<option value="">Selecione</option>'
                );
                $.each(retorno, function (key, value) {
                    $("#cd_dependencia_empresa_rh").append(
                        '<option value="' +
                            value.cd_dependencia_empresa_rh +
                            '">' +
                            value.sg_dependencia +
                            "</option>"
                    );
                });
                $("#cd_dependencia_empresa_rh").prop("disabled", false);
            },
        });
    }

    function pesquisar(e) {
        let url = $(this).data("href");
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarArea").serializeArray(),
            success: function (retorno) {
                $('#formularioPesquisarArea')[0].reset();
                $("select").select2({
                    theme: "bootstrap4",
                });
                if (retorno.status == false) {
                    Swal.fire({
                        icon: "error",
                        title: "Aviso",
                        text: retorno.message,
                    });
                    $("#tbArea tbody>tr").remove();
                    return;
                } else {
                    populateTableDependenciaRH(retorno);
                }
            },
            beforeSend: function () {
                $("#tbArea").DataTable().clear().destroy();
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                $(["#tbArea tbody"].join("")).append(row.join(""));
                $("#carregar").html(
                    "<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO"
                );
            },
            complete: function () {
                $("#carregar").html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });
    }

    function populateTableDependenciaRH(retorno) {
        $("#tbArea tbody>tr").remove();
        $.each(retorno.data, function (key, value) {
            var row = ["<tr>"];
            row.push(
                '<td width="20%" class="text-center">' + value.empresa + "</td>"
            );
            row.push(
                '<td width="10%" class="text-center">' +
                    value.sg_dependencia +
                    "</td>"
            );
            row.push('<td width="40%" >' + value.no_dependencia + "</td>");
            row.push(
                '<td  width="15%" class="text-center">' +
                    value.dt_descontinuidade +
                    "</td>"
            );
            row.push(
                '<td  width="15%" class="text-center">' +
                    value.dt_encerramento +
                    "</td>"
            );
            row.push("</tr>");
            $(["#tbArea tbody"].join("")).append(row.join(""));
        });
        $("#tbArea").DataTable({
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

    function importarBenner() {
        var url = $(this).data("href");
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarArea").serializeArray(),
            success: function (retorno) {
                console.info(retorno);
                if (retorno.status == true) {
                    swal.fire({
                        icon: "success",
                        text: retorno.msg,
                    });
                    $("#carregar").html("");
                } else {
                    swal.fire({
                        icon: "error",
                        text: retorno.msg,
                    });
                }
            },
            beforeSend: function () {
                $("#carregar").html(
                    "<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO"
                );
            },
            complete: function () {
                $("#carregar").html("");
            },
        });
    }

    init();
})();
