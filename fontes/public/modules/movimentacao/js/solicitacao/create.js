(function () {

    function init() {
        events();
    }

    function events() {
        $("input[name=chkMovimentacaoFuncao]").on("click", habilitaSelectFuncao);
        $("input[name=chkMovimentacaoUnidade]").on("click", habilitaSelectUnidade);
        $("input[name=tp_processo_movimentacao]").on("click", habilitaFormPRT);
        $(document).on('change', '#file_curriculo', verificaExtensao);
    }

    function habilitaSelectFuncao() {
        if ($(this).is(':checked')) {
            $('#cd_funcao_benner').removeAttr('disabled');
            $("input[name=tp_processo]").removeAttr('disabled');
            $('.tp_processo').fadeIn();
            $('#tp_movimentacao_funcao').val(1);
        } else if (!$(this).is(':checked')) {
            $('#cd_funcao_benner').prop('disabled', true);
            $('.tp_processo').fadeOut();
            $("input[name=tp_processo]").prop('disabled', true);
            $('#tp_movimentacao_funcao').val(0);
        }
    }


    function habilitaSelectUnidade() {
        if(!$('#chkMovimentacaoFuncao').is(':checked')){
            $('.tp_processo').fadeOut();
            $("input[name=tp_processo]").prop('disabled', true);
        }
        if ($(this).is(':checked')) {
            $('#cd_dependencia_empresa_rh').removeAttr('disabled');
            $('#tp_movimentacao_unidade').val(1);
        } else if (!$(this).is(':checked')) {
            $('#cd_dependencia_empresa_rh').prop('disabled', true);
            $('#tp_movimentacao_unidade').val(0);
        }
    }

    function habilitaFormPRT() {
        if ($(this).val() == 1) {
            $('#form_prt').fadeIn();
            $('#file_curriculo').removeAttr('disabled');
            $('#ds_parecer').removeAttr('disabled');
            $('#rd_processo_movimentacao').val(1);
        } else {
            $('#rd_processo_movimentacao').val(0);
            $('#form_prt').fadeOut();
            $('#file_curriculo').prop('disabled', true);
            $('#ds_parecer').prop('disabled', true);
        }
    }

    function verificaExtensao(event) {
        var anexo = $(this)[0].files[0];
        var mimeTypesPermitidos = ['application/pdf'];
        var mimeTypeArquivo = anexo.type;
        if (typeof mimeTypesPermitidos.find(function (ext) {
                return mimeTypeArquivo == ext;
            }) == 'undefined') {
            $('form')[0].reset();
            $('.error-mime-type').show();
        } else {
            $('.error-mime-type').hide();
        }
    }


    $.validator.setDefaults({
        submitHandler: function () {
            return true;
        },
        success: "valid",
    });

    $.validator.addClassRules(
        "chkMovimentacao", { required: function(element) {
            var response = true;
            if($('#chkMovimentacaoFuncao').is(':checked') || $('#chkMovimentacaoUnidade').is(':checked')){
                response = false;
            }
            return response;
        } });

    $.validator.messages.required = 'O campo nova função ou nova unidade é de preenchimento obrigatório, selecione pelo menos uma opção!';

    $("#frmSolicitacao").validate({
        rules: {
            nr_matricula: {
                required: true,
            },
            dt_alteracao: {
                required: true,
            },
            cd_funcao_benner: {
                required: true,
            },
            cd_dependencia_empresa_rh: {
                required: true,
            },
            tp_processo_movimentacao: {
                required: true,
            },
            file_curriculo: {
                required: true,
            },
            ds_parecer: {
                required: true,
            },
            st_ciente: {
                required: true,
            }
        },
        messages: {
            nr_matricula: {
                required: "O campo empregado é de preenchimento obrigatório",
            },
            dt_alteracao: {
                required: "O campo data de alteração é de preenchimento obrigatório",
            },
            cd_funcao_benner: {
                required: "O campo nova função é de preenchimento obrigatório",
            },
            cd_dependencia_empresa_rh: {
                required: "O campo nova unidade é de preenchimento obrigatório",
            },
            tp_processo_movimentacao: {
                required: "O campo tipo de processo é de preenchimento obrigatório",
            },
            file_curriculo: {
                required: "O campo currículo é de preenchimento obrigatório",
            },
            ds_parecer: {
                required: "O campo parecer é de preenchimento obrigatório",
            },
            st_ciente: {
                required: "O campo ciente da declaração é de preenchimento obrigatório",
            }

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