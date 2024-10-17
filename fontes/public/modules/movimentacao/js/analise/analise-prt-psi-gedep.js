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
        config();
    }

    function events() {
        $("input[name=st_aprovado_psi]").on("click", habilitaFormPSI);
        $("input[name=st_aprovado_prt]").on("click", habilitaFormPRT);

    }

    function config() {
       configurarObjetosPorTpProcesso();
    }


    function configurarObjetosPorTpProcesso(){

        switch (tp_processo_movimentacao) {
            case 1:
                $('.tp_processo_movimentacao_prt_psi').fadeIn();
                $('.tp_processo_movimentacao_prt').fadeIn();
                $('.tp_processo_movimentacao_psi').fadeOut();
                break;
            case 2:
                $('.tp_processo_movimentacao_prt_psi').fadeIn();
                $('.tp_processo_movimentacao_psi').fadeIn();                ;
                $('.tp_processo_movimentacao_prt').fadeOut();
                break;
        }
        return;
    }


    function habilitaFormPSI(){

        if ($(this).val() == 'N') {
            $('.ds_identificacao_psi').fadeOut();
            $('.ds_motivo_devolucao_psi').fadeIn();
            $('#btnAprovarAnalise').prop('disabled', true);
            $('#btnDevolverAnalise').prop('disabled', false);
            $('#ds_identificacao_psi').prop('disabled', true).val('');
            $('#ds_motivo_devolucao_psi').prop('disabled', false).val('');
        } else {
            $('.ds_identificacao_psi').fadeIn();
            $('.ds_motivo_devolucao_psi').fadeOut();
            $('#ds_justificativa').val('');
            $('#btnAprovarAnalise').prop('disabled', false);
            $('#btnDevolverAnalise').prop('disabled', true);
            $('#ds_identificacao_psi').prop('disabled', false).val('');
            $('#ds_motivo_devolucao_psi').prop('disabled', true).val('');
        }
    }

    function habilitaFormPRT(){
        if ($(this).val() == 'N') {
            $('.ds_motivo_devolucao_prt').fadeIn();
            $('#ds_motivo_devolucao_prt').prop('disabled', false).val('');
            $('#btnAprovarAnalise').prop('disabled', true);
            $('#btnDevolverAnalise').prop('disabled', false);
        } else {
            $('.ds_motivo_devolucao_prt').fadeOut();
            $('#ds_motivo_devolucao_prt').prop('disabled', true).val('');
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

    $.validator.addClassRules(
        "st_regra_alternancia", { required: function(element) {
            var response = true;
            if($('#st_regra_alternancia').is(':checked') && $('#st_regra_alternancia').val() == 'S'){
                response = false;
            }
            return response;
        } });

    $.validator.messages.required = 'O campo nova função ou nova unidade é de preenchimento obrigatório, selecione pelo menos uma opção!';

    $("#formularioAnalisarSolicitacao").validate({
        rules: {
            st_atende_regra_alternancia: {
                required: true,
            },
            st_aprovado_psi: {
                required: true,
            },
            ds_identificacao_psi: {
                required: true,
            },
            ds_justificativa: {
                required: true,
            },
            st_aprovado_prt: {
                required: true,
            },
            ds_motivo_devolucao_prt: {
                required: true,
            },
            ds_motivo_devolucao_psi: {
                required: true,
            }
        },
        messages: {
            st_atende_regra_alternancia: {
                required: "O campo Atende Regra de Alternância PSI-PRT é de preenchimento obrigatório",
            },
            st_aprovado_psi: {
                required: "O campo Empregado Aprovado em PSI é de preenchimento obrigatório",
            },
            ds_identificacao_psi: {
                required: "O campo Identificação de PSI é de preenchimento obrigatório",
            },
            ds_justificativa: {
                required: "O campo Justificativa é de preenchimento obrigatório",
            },
            st_aprovado_prt: {
                required: "O campo Empregado Aprovado em PRT é de preenchimento obrigatório",
            },
            ds_motivo_devolucao_prt: {
                required: "O campo Motivo da devolução é de preenchimento obrigatório",
            },
            ds_motivo_devolucao_psi: {
                required: "O campo Motivo da devolução é de preenchimento obrigatório",
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
