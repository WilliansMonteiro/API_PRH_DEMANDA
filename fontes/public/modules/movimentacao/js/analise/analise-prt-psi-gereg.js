(function () {
    /*
     * Tipo de Processos
     * 1 - PRT
     * 2 - PSI
     * 3 - Ascensão
     */
    var tp_processo_movimentacao = $('#tp_processo_movimentacao').val();

    function init() {
        events();
    }

    function events() {
        $("input[name=st_aprovado]").on("click", habilitaForm);
    }


    function habilitaForm(){
        if ($(this).val() == 'N') {
            $('.st_aprovado').fadeIn();
            $('#btnAprovarAnalise').prop('disabled', true);
            $('#btnDevolverAnalise').prop('disabled', false);
        } else {
            $('.st_aprovado').fadeOut();
            $('#ds_justificativa').val('');
            $('#btnAprovarAnalise').prop('disabled', false);
            $('#btnDevolverAnalise').prop('disabled', true);
        }
    }

    $.validator.setDefaults({
        submitHandler: function () {
            return true;
        },
        success: "valid",
    });

    $("#formularioAnalisarSolicitacao").validate({
        rules: {
            ds_justificativa: {
                required: true,
            },
            st_aprovado: {
                required: true,
            },
            st_cumpre_requisito_pccr: {
                required: true,
            }
        },
        messages: {
            ds_justificativa: {
                required: "O campo Justifcativa é de preenchimento obrigatório",
            },
            st_aprovado: {
                required: "O campo Empregado Aprovado é de preenchimento obrigatório",
            },
            st_cumpre_requisito_pccr: {
                required: "O campo Empregado cumpre requisito do PCCR é de preenchimento obrigatório",
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
