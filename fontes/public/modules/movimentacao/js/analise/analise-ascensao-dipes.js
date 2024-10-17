(function () {

    var tp_processo_movimentacao = $('#tp_processo_movimentacao').val();

    function init() {
        events();
    }

    function events() {
        $("input[name=st_aprovado_ascensao]").on("click", habilitaFormAscensao);
    }

    function habilitaFormAscensao(){
        if ($(this).val() == 'N') {
            $('.st_aprovado_ascensao').fadeIn();
            $('#btnAprovarAnalise').prop('disabled', true);
            $('#btnDevolverAnalise').prop('disabled', false);
        } else {
            $('.st_aprovado_ascensao').fadeOut();
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
            st_aprovado_ascensao: {
                required: true,
            }
        },
        messages: {
            ds_justificativa: {
                required: "O campo Justifcativa é de preenchimento obrigatório",
            },
            st_aprovado_ascensao: {
                required: "O campo Empregado Aprovado em Ascensão é de preenchimento obrigatório",
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
