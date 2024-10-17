(function () {
    var arr_registros_inseridos = [];
    var arr_dados_session_php_json = {};
    var arr_dados = [];
    arr_dados_session_php_json.arr_dados = arr_dados;
    function init() {
        loadTableAreasCompetenciaTecnica();
        events();
        config();
    }

    function events() {
        $(document).on(
            "click",
            "#btnItemCompetenciaTecnicaCriar",
            criarItemCompetencia
        );
        $(document).on(
            "click",
            "#btnItemCompetenciaTecnicaExcluir",
            excluirItemCompetencia
        );
        $(document).on(
            "click",
            "#btnAddAreaCompTecnica",
            addAreaCompetenciaTecnica
        );
        $(document).on(
            "click",
            "#btnDelAreaCompTecnica",
            excluirAreaCompetenciaTecnica
        );
    }

    function criarItemCompetencia(e) {
        e.preventDefault();

        let lastItem = $(".item:last").attr("id");
        let nextIndex = Number(lastItem.split("_")[1]) + 1;
        var routeItemCompetencia = $("#inputRotaCompetenciaTecnica").val();

        $(".item:last").after(
            '<div class="row item" id="itemCompetenciaTecnica_' +
                nextIndex +
                '"></div>'
        );
        var select = ['<div class="col-md-7">'];
        select.push('<div class="form-group">');
        select.push(
            '<select id="sq_competencia_tecnica_' +
                nextIndex +
                '" name="item[' +
                nextIndex +
                '][sq_competencia_tecnica]" class="form-control competencia_tecnica" disabled="disabled" placeholder="Selecione">'
        );
        select.push('<option value="">Selecione</option>');
        select.push("</select></div></div>");
        select.push('<div class="col-md-2"><div class="form-group">');
        select.push(
            '<button type="button" class="btn btn-fill btn-danger pull-right btn-marg-left" id="btnItemCompetenciaTecnicaExcluir"><i class="fa fa-minus"></i></button>'
        );
        select.push(
            '&nbsp;<button type="button" class="btn btn-fill btn-primary pull-right btn-marg-left" id="btnItemCompetenciaTecnicaCriar" data-href="' +
                routeItemCompetencia +
                '"><i class="fa fa-plus"></i></button>'
        );
        select.push("</div>");
        $("#itemCompetenciaTecnica_" + nextIndex).append(select.join(""));

        $.ajax({
            url: $(this).data("href"),
            type: "get",
            dataType: "json",
        }).done(function (response) {
            $.each(response, function (key, value) {
                $(
                    'select[name="item[' +
                        nextIndex +
                        '][sq_competencia_tecnica]"]'
                )
                    .append(
                        $("<option>")
                            .val(value.sq_competencia_tecnica)
                            .text(value.ds_competencia_tecnica)
                            .attr(
                                "data-ds_competencia_tecnica",
                                value.ds_competencia_tecnica
                            )
                    )
                    .prop("disabled", false);
            });
        });
    }

    function excluirItemCompetencia(e) {
        e.preventDefault();

        let totalItem = $(".item").length;
        if (totalItem === 1) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Pelo menos um item deve ser mantido!",
            });
            return;
        }

        let id = $(this).closest("div.item").attr("id");
        let deleteIndex = Number(id.split("_")[1]);
        let url = $(this).data("href");

        if (typeof url !== typeof undefined && url !== false) {
            Swal.fire({
                title: "Tem certeza que deseja excluir?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Não",
                cancelButtonColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Sim",
            }).then((result) => {
                if (result.value) {
                    let url = $(this).data("href");
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
                            if (retorno)
                                $("#itemEscala_" + deleteIndex).remove();
                            window.location.reload();
                        },
                    });
                }
            });
        }
        $("#itemCompetenciaTecnica_" + deleteIndex).remove();
    }

    function validateCompetenciaTecnica() {
        var arrCompetencia = [];
        $.each($(".competencia_tecnica"), function (key, value) {
            var sq_competencia_tecnica = $(value).find(":selected").val();
            if (sq_competencia_tecnica !== "") {
                arrCompetencia.push(sq_competencia_tecnica);
            }
        });
        if (arrCompetencia.length === 0) {
            return false;
        }
        return;
    }

    function addAreaCompetenciaTecnica(e) {
        e.preventDefault();

        if ($("#cd_dependencia_empresa_rh").val() == "") {
            Swal.fire({
                icon: "error",
                title: "Alerta",
                text: "Selecione uma área.",
            });
            return;
        }

        if (validateCompetenciaTecnica() === false) {
            Swal.fire({
                icon: "error",
                title: "Alerta",
                text: "Selecione pelo menos uma competência.",
            });
            return;
        }

        if ($(".div_arr_registros_inseridos").length) {
            $(".div_arr_registros_inseridos").each(function () {
                if (
                    !Array.isArray(
                        arr_registros_inseridos[$(this).data("sg_dependencia")]
                    )
                ) {
                    if (
                        !arr_registros_inseridos.includes(
                            $(this).data("sg_dependencia")
                        )
                    ) {
                        arr_registros_inseridos[
                            $(this).data("sg_dependencia")
                        ] = [];
                    }
                }
                if (
                    !arr_registros_inseridos[
                        $(this).data("sg_dependencia")
                    ].includes($(this).data("ds_competencia_tecnica"))
                ) {
                    arr_registros_inseridos[
                        $(this).data("sg_dependencia")
                    ].push($(this).data("ds_competencia_tecnica"));
                }
            });
        }

        let url = $(this).data("href");
        let cd_dependencia_empresa_rh = $("#cd_dependencia_empresa_rh").val();
        let ds_dependencia_empresa_rh = $(
            "#cd_dependencia_empresa_rh option:selected"
        ).data("ds_dependencia_empresa_rh");

        if (
            !Array.isArray(arr_registros_inseridos[ds_dependencia_empresa_rh])
        ) {
            if (!arr_registros_inseridos.includes(ds_dependencia_empresa_rh)) {
                arr_registros_inseridos[ds_dependencia_empresa_rh] = [];
            }
        }

        $("#cd_dependencia_empresa_rh").val("").trigger("change");

        $.each($(".competencias select"), function (key, value) {
            if (value.value !== "" && cd_dependencia_empresa_rh !== "") {
                arr_dados_session_php_json.arr_dados.push({
                    cd_dependencia_empresa_rh: cd_dependencia_empresa_rh,
                    sq_competencia_tecnica: $(value).find(":selected").val(),
                });
                arr_dados_session_php_json.arr_dados =
                    arr_dados_session_php_json.arr_dados.filter(
                        (arr_dados, index, self) =>
                            index ===
                            self.findIndex(
                                (ad) =>
                                    ad.cd_dependencia_empresa_rh ===
                                        arr_dados.cd_dependencia_empresa_rh &&
                                    ad.sq_competencia_tecnica ===
                                        arr_dados.sq_competencia_tecnica
                            )
                    );

                if (
                    !arr_registros_inseridos[
                        ds_dependencia_empresa_rh
                    ].includes(
                        $(value)
                            .find(":selected")
                            .data("ds_competencia_tecnica")
                    )
                ) {
                    arr_registros_inseridos[ds_dependencia_empresa_rh].push(
                        $(value)
                            .find(":selected")
                            .data("ds_competencia_tecnica")
                    );
                }
            }
        });

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                arr_dados_session_php_json:
                    arr_dados_session_php_json.arr_dados,
                sq_matriz_tecnica: $("#sq_matriz_tecnica").val(),
            },
            success: function (retorno) {
                $(".competencia_tecnica").val("");

                loadTableAreasCompetenciaTecnica();
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

    function excluirAreaCompetenciaTecnica(e) {
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
                let id = $(this).closest("tr").attr("id");
                let deleteIndex = Number(id.split("_")[2]);
                let url = $(this).data("href");

                if (typeof url === "undefined") {
                    $("#tr_act_" + deleteIndex).remove();
                    return;
                }

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
                        if (retorno.status === true) {
                            Swal.fire(
                                "Aviso!",
                                "Registro Excluído com Sucesso!",
                                "success"
                            );
                            loadTableAreasCompetenciaTecnica();
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Aviso",
                                text: "Erro ao tentar excluir o registro, a matriz deve possuir pelo menos uma competência vinculada!",
                            });
                        }
                    },
                });
            }
        });
    }

    function loadTableAreasCompetenciaTecnica() {
        var sqMatrizTecnica = $("#sq_matriz_tecnica").val();
        let url =
            $("#inputRotaAreasCompetenciaTecnica").val() +
            "/" +
            sqMatrizTecnica;
        let urlDelete = $("#deleteRotaAreasCompetenciaTecnica").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "GET",
            url: url,
            success: function (retorno) {
                $("#area_comp_tecnica_table tbody").html("");
                $.each(retorno, function (key, value) {
                    var row = ["<tr>"];
                    row.push(
                        '<div class="div_arr_registros_inseridos" style="display: none;"  data-sg_dependencia="' +
                            value.sg_dependencia +
                            '"   data-ds_competencia_tecnica="' +
                            value.ds_competencia_tecnica +
                            '"></div>'
                    );
                    row.push('<tr id="tr_act_' + key + '">');
                    row.push("<td>" + value.sg_dependencia + "</td>");
                    row.push("<td>" + value.ds_competencia_tecnica + "</td>");
                    row.push('<td class="text-center">');
                    row.push(
                        '<button type="button" class="btn btn-fill btn-danger" id="btnDelAreaCompTecnica" data-href="' +
                            urlDelete +
                            "/" +
                            value.cd_dependencia_empresa_rh +
                            "/" +
                            value.sq_competencia_tecnica +
                            "/" +
                            sqMatrizTecnica +
                            '">'
                    );
                    row.push('<i class="fas fa-trash"></i>');
                    row.push("</button></td>");
                    row.push("</tr>");
                    $("#area_comp_tecnica_table  tbody").append(row.join(""));
                });
            },
            beforeSend: function () {
                $("#area_comp_tecnica_table tbody").html("");
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                $(["#area_comp_tecnica_table tbody"].join("")).append(
                    row.join("")
                );
                $("#carregar").html(
                    "<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO"
                );
            },
            complete: function () {
                $("#carregar").html("");
            },
        });
    }

    $.validator.setDefaults({
        submitHandler: function () {
            return true;
        },
    });

    $("#frmCompetenciaTecnica").validate({
        rules: {
            ds_matriz_tecnica: {
                required: true,
                maxlength: 100,
            },
        },
        messages: {
            ds_matriz_tecnica: {
                required: "O campo descrição é de preenchimento obrigatório",
                maxlength: "O tamanho máximo permitido é de 255 caracteres",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass("is-invalid");
        },
    });

    init();
})();
