(function () {
    var arr_registros_inseridos = [];
    var arr_dados_session_php_json = {};
    var arr_dados = [];
    var arrCompetencia = [];
    arr_dados_session_php_json.arr_dados = arr_dados;
    function init() {
        events();
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

        let id = $(this).closest("div.item").attr("id");
        let deleteIndex = Number(id.split("_")[1]);
        let url = $(this).data("href");
        let totalItem = $(".item").length;

        if (totalItem === 1) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Pelo menos um item deve ser mantido!",
            });
            return;
        }
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
        let cd_dependencia_empresa_rh = $("#cd_dependencia_empresa_rh").val();
        arrCompetencia.length = 0;
        $(".competencia_tecnica option:selected").each(function (key, value) {
            var sq_competencia_tecnica = value.value;
            if (sq_competencia_tecnica !== "") {
                arrCompetencia.push({
                    cd_dependencia_empresa_rh: cd_dependencia_empresa_rh,
                    sq_competencia_tecnica: sq_competencia_tecnica,
                });
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
            if (value.value === "" && cd_dependencia_empresa_rh === "") {
                Swal.fire({
                    icon: "error",
                    title: "",
                    text: "Selecione uma área e pelo menos uma competência.",
                });
                return false;
            } else {
                if (value.value !== "" && cd_dependencia_empresa_rh !== "") {
                    arr_dados_session_php_json.arr_dados.push({
                        cd_dependencia_empresa_rh: cd_dependencia_empresa_rh,
                        sq_competencia_tecnica: $(value)
                            .find(":selected")
                            .val(),
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
                        let urlDelete = $(
                            "#deleteRotaAreasCompetenciaTecnica"
                        ).val();
                        $("#area_comp_tecnica_table")
                            .find("tbody:last")
                            .append(
                                '<tr id="tr_act_' +
                                    key +
                                    '">\r\n\
                                       <td>' +
                                    ds_dependencia_empresa_rh +
                                    "</td>\r\n\
                                       <td>" +
                                    $(value)
                                        .find(":selected")
                                        .data("ds_competencia_tecnica") +
                                    '</td>\r\n\
                                       <td class="text-center">\r\n\
                                <button type="button" class="btn btn-fill btn-danger" id="btnDelAreaCompTecnica" data-sq_competencia_tecnica="' +
                                    $(value).find(":selected").val() +
                                    '" data-cd_dependencia_empresa_rh="' +
                                    cd_dependencia_empresa_rh +
                                    '" ><i class="fa fa-trash"></i>\r\n\
                                           </button></td>\r\n\
                                   </tr>'
                            );
                    }
                }
                $(value).val("");
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
            data: arr_dados_session_php_json,
            success: function (retorno) {},
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

                let cd_dependencia_empresa_rh = $(this).data(
                    "cd_dependencia_empresa_rh"
                );
                let sq_competencia_tecnica = $(this).data(
                    "sq_competencia_tecnica"
                );

                if (typeof url === "undefined") {
                    $("#tr_act_" + deleteIndex).remove();
                    return;
                }
                removeValueArray(
                    arr_dados_session_php_json.arr_dados,
                    cd_dependencia_empresa_rh,
                    sq_competencia_tecnica
                );
            }
        });
    }

    function removeValueArray(
        array,
        cd_dependencia_empresa_rh,
        sq_competencia_tecnica
    ) {
        var arr = array;
        for (var i = 0; i < arr.length; i++) {
            if (
                parseInt(arr[i].cd_dependencia_empresa_rh) ===
                    parseInt(cd_dependencia_empresa_rh) &&
                parseInt(arr[i].sq_competencia_tecnica) ===
                    parseInt(sq_competencia_tecnica)
            ) {
                arr.splice(i, 1);
                i--;
            }
        }
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
            sq_ciclo_avaliativo: {
                required: true,
            },
            sq_escala: {
                required: true,
            },
        },
        messages: {
            ds_matriz_tecnica: {
                required: "O campo descrição é de preenchimento obrigatório",
                maxlength: "O tamanho máximo permitido é de 255 caracteres",
            },
            sq_ciclo_avaliativo: {
                required:
                    "O campo ciclo avaliativo é de preenchimento obrigatório",
            },
            sq_escala: "O campo escala é de preenchimento obrigatório",
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
