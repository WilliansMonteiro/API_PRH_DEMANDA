/**
 * Created by u653667 on 20/09/2020.
 */
(function(){

    function init(){
        events();
        loadAvaliacao();
    }

    function events(){
        $(document).on('click', '#btnAvaliacaoSalvar', saveAvaliacao);
        $(document).on('click', '.btnMembroEquipe', loadAvaliacao);
    }

    function loadAvaliacao(){
        $(this).removeClass('btnMembroEquipe');
        var nr_matricula = $(this).attr('data-matricula') ? $(this).attr('data-matricula') : $('#membroEquipe0').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "GET",
            url		: '/modulo-avaliacao/responder-avaliacao/load-avaliacao',
            dataType : 'html',
            data	: {
                nr_matricula: nr_matricula,
                sq_membro_equipe_avaliador: $('#sqMembroEquipe').val(),
                cd_tipo_avaliacao: $('#tipoAvaliacao').val(),
                sq_ciclo_avaliativo: $('#cicloAvaliativo').val(),
                tp_modulo: '1'
            },
            success	: function(retorno) {
                $('#loadAvaliacao'+nr_matricula).html('');
                $('#loadAvaliacao'+nr_matricula).html(retorno);
            },
            beforeSend: function() {
                $('#loadAvaliacao'+nr_matricula).html('<img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO');
            }
        });
    }

    function saveAvaliacao(){
        var url = $(this).attr('data-href');
        var urlBack = $(this).attr('data-href-back');

        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioAvaliacao").serializeArray(),
            success	: function(retorno) {
                if(retorno.status == true){
                    swal.fire({
                        icon: "success",
                        text: retorno.msg
                    }).then((result) => {

                });
                }else{
                    swal.fire({
                        icon: "error",
                        text: retorno.msg
                    });
                }
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            }

        });
    }

    init();
})()
