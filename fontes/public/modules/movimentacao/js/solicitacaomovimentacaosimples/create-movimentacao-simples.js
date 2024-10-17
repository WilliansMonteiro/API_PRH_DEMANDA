(function () {

    function init() {
        events();
    }

    function events() {
        //Enviar Aprovação
        $(document).on('click', "#btn-enviar-aprovacao", validacaoEnviar);
    }

    function validacaoEnviar(event){

        $("#frmSolicitacao").validate({
            rules: {
                nr_matricula: {
                    required: true,
                },
                dt_alteracao: {
                    required: true,
                },
                cd_dependencia_empresa_rh_origem: {
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
                cd_dependencia_empresa_rh_origem: {
                    required: "Selecione pelo menos uma Nova Unidade",
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

    }

    init();
})();