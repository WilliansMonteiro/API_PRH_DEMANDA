(function () {
    var anexoPosicao = 0;
    var verificaDiasPromise = null;
    var swalAlertPromise = null;

    function init() {
        events();
    }

    function events() {
        $("#cd_justificativa").on("change", escolheJustificativa);
        //File
        $(document).on('click', "#btnCriarInput", adicionaInputFile);
        $(document).on('click', "#btnRemoverInput", removerInputFile);
        //Enviar Aprovação
        $(document).on('click', "#btn-enviar-aprovacao", validacaoEnviar);
    }


    function escolheJustificativa() {
        if($(this).val() == 3){
            hideOthersElements()
            $('#ds_demanda_temporaria').show()
        }else if($(this).val() == 4){
            hideOthersElements()
            $('#substituicao').show()
        }else if($(this).val() == 5){
            hideOthersElements()
            $('#ds_outros_motivos').show()
        }else if($(this).val() == 1){
            hideOthersElements()
            $('#form_prt').show()
        }
        else{
            hideOthersElements()
        }
    }

    function hideOthersElements(){
        $('#ds_demanda_temporaria').hide()
        $('#substituicao').hide()
        $('#ds_outros_motivos').hide()
        $('#form_prt').hide()
    }

    function adicionaInputFile(){
        var posicao = ++anexoPosicao
        $('#lista_anexo').append('<hr class="col-xs-12" id="linha_'+posicao+'"> <input type="file" name="file_anexo_parecer[]" id="anexo_'+posicao+'" > <button type="button" class="btn btn-fill btn-danger pull-right btn-marg-left" data-valor="'+posicao+'" id="btnRemoverInput" ><i class="fa fa-minus"></i></button>')
    }

    function removerInputFile(){
        $(this).remove(); // Some com o Botão
        removeElementAnexo($(this)[0].dataset.valor);  
    }

    function removeElementAnexo(id_elemento){
        $("#linha_"+id_elemento+"").remove();  
        $("#anexo_"+id_elemento+"").remove();
    }

    function validacaoEnviar(event){

        var tipoJustificativa = $('#cd_justificativa').val();
        
        $.validator.setDefaults({
            submitHandler: function () {
                //Validação dos Dias
                verificaDias().then(function (response) {
                    if(response > 90){
                        //Retorna Confirmação
                       alertSwal().then(function(result) {
                            if(result.value){
                                document.getElementById("frmSolicitacao").submit();
                            }else{
                                return false;
                            }
                        })
                    }else if(response == 0){
                        document.getElementById("frmSolicitacao").submit();
                    }else if(response < 90){
                        document.getElementById("frmSolicitacao").submit();
                    }else{
                        return false;
                    }
        
                });
                
            },
            success: "valid",
        });

        $("#frmSolicitacao").validate({
            rules: {
                nr_matricula: {
                    required: true,
                },
                dt_inicio_adicao: {
                    required: true,
                },
                cd_justificativa: {
                    required: true,
                },
                cd_dependencia_empresa_rh_origem:{
                    required: true,
                },
                cd_dependencia_empresa_rh_destino:{
                    required: true,
                },
                ds_demanda_temporaria: {
                    required: function (params) {
                        return ($('#cd_justificativa').val() == 3) ? true : false;
                    },
                },
                cd_funcao_benner: {
                    required: function (params) {
                        return ($('#cd_justificativa').val() == 4) ? true : false;
                    },
                },
                ds_outros_motivos: {
                    required: function (params) {
                        return ($('#cd_justificativa').val() == 5) ? true : false;
                    },
                },
                'file_anexo_parecer[]': {
                    required: function (params) {
                        return ($('#cd_justificativa').val() == 1) ? true : false;
                    },
                }
            },
            messages: {
                nr_matricula: {
                    required: "O campo empregado é de preenchimento obrigatório",
                },
                dt_inicio_adicao: {
                    required: "O campo data inicio Adição é de preenchimento obrigatório",
                },
                cd_justificativa: {
                    required: "O campo justificativa é de preenchimento obrigatório",
                },
                cd_dependencia_empresa_rh_origem:{
                    required: "Selecione pelo menos uma Unidade Origem",
                },
                cd_dependencia_empresa_rh_destino:{
                    required: "Selecione pelo menos uma Unidade Destino",
                },
                ds_demanda_temporaria: {
                    required: "O campo demanda temporária é de preenchimento obrigatório",
                },
                cd_funcao_benner: {
                    required: "O campo substituição é de preenchimento obrigatório",
                },
                ds_outros_motivos: {
                    required: "O campo outros motivos é de preenchimento obrigatório",
                },
                'file_anexo_parecer[]': {
                    required: "É necessário fazer upload de pelo menos um arquivo.",
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


    //Ferramentas AJAX/ALERT
   
    
    function verificaDias(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       return $.ajax({
            type    : "POST",
            url     : '/modulo-movimentacao/solicitacao/adicao-temporaria/verifica-tempo',
            data    : $("#frmSolicitacao").serializeArray(),
            async   : false
        });
    }

    function alertSwal(){
       return Swal.fire({
            title: 'A solicitação de adição temporária está fora do prazo regulamentar. Casos excepcionais deverão ser submetido à análise do Superintendente SUAPE. Deseja continuar?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sim',
        })
    }


    init();
})();