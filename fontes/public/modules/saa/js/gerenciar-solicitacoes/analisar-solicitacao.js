$(document).ready(function () {

    // $(document).on("click", "#btnSolicAprovar", function (e) {
    //     e.preventDefault();
    //     Swal.fire({
    //         title: "Tem certeza que deseja aprovar a solicitação?",
    //         text: "",
    //         icon: "warning",
    //         showCancelButton: true,
    //         cancelButtonText: "Não",
    //         cancelButtonColor: "#dc3c45",
    //         confirmButtonColor: "#55a846",
    //         confirmButtonText: "Sim",
    //     }).then((result) => {
    //         if (result.value) {
    //             $("#aprovar").trigger("click");
    //         }
    //     });
    // });
    $(document).on("click", "#btn-aprovar-solicitacao-modal", function (e) {
        $("#aprovar").trigger("click");
    });


    $("#tipo_aprovacao_combo").change(function (e) {
       $("#input_tipo_matricula").val($(this).val());
    });

    $("#txtCep").focusout(function () {
        var cep = $("#txtCep").val();

        if(cep.length != 9){
            swal.fire({
                icon: "warning",
                text: "CEP Inválido"
            });

            return false;
        }

        var urlCep = "https://brasilapi.com.br/api/cep/v1/" + cep + "";

        $.ajax({
            url: urlCep,
            type: "get",
            dataType: "json",
            success: function (data) {
                var endereco = data.street;
                var cidade = data.city;
                $("#txtEndereco").val(endereco);
                $("#no_cidade").val(cidade);
            },
            error: function (error) {
                swal.fire({
                    icon: "error",
                    text: "Ocorreu um Erro ao Consultar o Serviço de CEP"
                });
            },
        });
    });

    function TestaCPF(strCPF) {
        var Soma;
        var Resto;
        Soma = 0;
        if (strCPF == "00000000000") return false;
        for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;
        if (Resto == 10 || Resto == 11) Resto = 0;
        if (Resto != parseInt(strCPF.substring(9, 10))) return false;
        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;
        if (Resto == 10 || Resto == 11) Resto = 0;
        if (Resto != parseInt(strCPF.substring(10, 11))) return false;
        return true;
    }

    $("#nr_cpf").focusout(function () {
        var campoCpf = $("#nr_cpf").val();
        var replaceCPF = campoCpf.replace(/[.-]/g, "");
        verificarCPF = TestaCPF(replaceCPF);
        if (verificarCPF == true) {
            console.log("cpf válido");
            $("#nr_cpf").removeClass("is-invalid");
            $("#nr_cpf").addClass("is-valid");
        } else {
            $("#nr_cpf").addClass("is-invalid");
        }
    });

    $("#cd_empresa").change(function (e) {
        console.log('change');
        var url = $("#rotaGetDependencia").val() + "/" + $(this).val();
        var select = $("select[name=cd_dependencia_lotacao]");
        $('#faixa_matricula').html(" ");
        $('#cd_empresa_hidden').val($(this).val());

        $.ajax({
            url: url,
            type: "get",
            success: function (data) {
                select.html("");
                $.each(data, function (key, val) {
                    select.append('<option value="' + val.cd_dependencia + '">' + val.sg_dependencia + ' - ' + val.nm_dependencia + "</option>'");
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });
    });

    $("#cd_empresa").change(function (e) {
        var urlTipo = $('#rotaGetTipoByEmpresa').val()+'/'+$(this).val();
        var selectTipo = $('select[name=tipo]');
        var selectFaixa = $('select[name=faixa]');

        $.ajax({
            url: urlTipo,
            type: "get",
            success: function (data) {
                console.log(data);
                selectFaixa.html('');
                selectTipo.html('');
                selectFaixa.append('<option value="">Selecione</option>');
                $.each( data, function(key, val) {
                    selectTipo.append('<option value="' + val.cd_tipo_empregado + '">' + val.ds_tipo_empregado + "</option>'");
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });

        var url_rota_faixa = $('#rotaGetFaixaEmpregado').val()+'/'+$('#tipo').val()+'/'+$("#cd_empresa").val();
        var span = $('#faixa_matricula');
        $.ajax({
            url: url_rota_faixa,
            type: "get",
            success: function (data) {
                console.log(data);
                //span.html("");
                $.each( data, function(key, val) {
                    span.html(val.fmanumini + ' - ' +val.fmanumfim );

                    $("#faixa").val(val.fmaseq);
                    // span.append( '<input type="text" name="faixa" id="faixa" value="{{$faixa->fmaseq}}" />' );
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });

    });

    $("#tipo").change(function (e) {
        var url = $('#rotaGetFaixaEmpregado').val()+'/'+$(this).val()+'/'+$("#cd_empresa").val();
        var span = $('#faixa_matricula');

        console.log(url);

        $.ajax({
            url: url,
            type: "get",
            success: function (data) {
                console.log(data);
                //span.html("");
                $.each( data, function(key, val) {
                    span.html(val.fmanumini + ' - ' +val.fmanumfim );

                    $("#faixa").val(val.fmaseq);
                    // span.append( '<input type="text" name="faixa" id="faixa" value="{{$faixa->fmaseq}}" />' );
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });
    });


$(document).on("input", "#ds_observacao", function () {
    var limite = 0;
    var caracteresDigitados = $(this).val().length;
    var caracteresRestantes = limite + caracteresDigitados;

    $(".caracteres").text(caracteresRestantes);
});
});
