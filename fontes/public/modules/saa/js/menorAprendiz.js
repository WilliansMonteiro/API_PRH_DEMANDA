$(document).ready(function () {
    $("select").select2({
        theme: "bootstrap4",
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

        var urlCep = "https://brasilapi.com.br/api/cep/v1/{" + cep + "}";
        $.ajax({
            url: urlCep,
            type: "get",
            dataType: "json",
            success: function (data) {

                var endereco = data.street;
                var cidade = data.city;
                var uf = data.state;

                $("#txtEndereco").val(endereco);
                $("#no_cidade").val(cidade);
                $('#cd_uf option[value="' + uf + '"]').attr({ selected: "selected" });
                $("#cd_uf").trigger("change.select2");
            },
            beforeSend: function () {
                $("#load").html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                console.log("funcionou");
                $("#load").html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
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
            $("#nr_cpf").removeClass("is-invalid");
            $("#nr_cpf").addClass("is-valid");
        } else {
            $("#nr_cpf").addClass("is-invalid");
        }
    });

    $("#cd_empresa").change(function (e) {
        var url = $("#rotaGetDependencia").val() + "/" + $(this).val();
        var select = $("select[name=cd_dependencia_lotacao]");
        $("#cd_empresa_hidden").val($(this).val());

        $.ajax({
            url: url,
            type: "get",
            success: function (data) {
                select.html("");
                $.each(data, function (key, val) {
                    select.append('<option value="' + val.cd_dependencia + '">' + val.sg_dependencia + " - " + val.nm_dependencia + "</option>'");
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });
    });

    $("#btnSalvar").click(function () {
        console.log("click");
        $("#carregar").html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    });
});
