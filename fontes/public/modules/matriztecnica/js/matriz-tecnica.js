(function () {
    function init() {
        events();
    }

    function events() {
        $(document).on("click", "#btnMatrizTecnicaPesquisar", pesquisar);
        $(document).on("click", "#btnMatrizTecnicaExcluir", excluir);
    }

    function pesquisar(e) {
        let url = $(this).data("href");
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarMatrizTecnica").serializeArray(),
            success: function (retorno) {
                populateTableMatrizTecnica(retorno);
            },
            beforeSend: function () {
                $("#tbMatrizTecnica").DataTable().clear().destroy();
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                $(["#tbMatrizTecnica tbody"].join("")).append(row.join(""));
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

    function populateTableMatrizTecnica(retorno) {
        $("#tbMatrizTecnica tbody>tr").remove();
        $.each(retorno.data, function (key, value) {
            var editar =
                '<a class="btn btn-small btn-info" href="/modulo-avaliacao/administrativo/matrizes/matriz-tecnica/editar/' +
                value.sq_matriz_tecnica +
                '"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar Matriz sq_matriz_tecnica"></i></a>&nbsp;';
            var inativar =
                '<a class="btn btn-small btn-danger" href="" id="btnMatrizTecnicaExcluir" data-href="/modulo-avaliacao/administrativo/matrizes/matriz-tecnica/excluir/' +
                value.sq_matriz_tecnica +
                '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Inativar Matriz Técnica"></i></a>';

            var row = ["<tr>"];
            row.push(
                '<td class="text-center">' + value.sq_matriz_tecnica + "</td>"
            );
            row.push("<td>" + value.ds_matriz_tecnica + "</td>");
            row.push("<td>" + value.ciclo.ds_ciclo_avaliativo + "</td>");
            row.push("<td>" + value.escala.ds_escala + "</td>");
            row.push(
                '<td class="project-actions text-center">' +
                    editar +
                    inativar +
                    "</td>"
            );
            row.push("</tr>");
            $(["#tbMatrizTecnica tbody"].join("")).append(row.join(""));
        });
        $("#tbMatrizTecnica").DataTable({
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

    function excluir(e) {
        e.preventDefault();
        Swal.fire({
            title: "Tem certeza que deseja excluir?",
            text: "",
            icon: "warning",
            showCancelButton: true,
            cancelButtonText: "Não",
            cancelButtonColor: "#55a846",
            confirmButtonColor: "#dc3c45",
            confirmButtonText: "Sim",
        }).then((result) => {
            if (result.value) {
                var url = $(this).data("href");
                console.log(url);
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                });
                $.ajax({
                    type: "DELETE",
                    url: url,
                    success: function (retorno) {
                        if (retorno) window.location.reload();
                    },
                });
            }
        });
    }

    init();
})();
