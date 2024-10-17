$(document).ready(function() {
    $('.money').each(function() {
        var valor = parseFloat($(this).val().replace(',', '.')).toFixed(2);
        $(this).val(valor.replace('.', ','));
    });
    $('.money').mask('000,00', {
        reverse: true,
        placeholder: '0,00'
    });

    $(document).on('click', '#btn_salva_alteracao', function(e){
        e.preventDefault();

        // AQUI PREENCHER TODOS OS CAMPOS DO OBJETO ETAPA ANTES DE ENVIAR A REQUISIÇÃO
        let cronograma = $(this).attr('data-sq-cronograma');
        let sq_etapa = $('#sq_etapa_cronograma_' + cronograma).val();
        let inicio_etapa = $('#dt_inicio_etapa_cronograma_' + cronograma).val();
        let fim_etapa = $('#dt_fim_etapa_cronograma_' + cronograma).val();
        let revisao = $('#st_aceita_revisao_cronograma_' + cronograma).is(':checked');
        let inicio_revisao = $('#dt_inicio_revisao_cronograma_' + cronograma).val();
        let fim_revisao = $('#dt_fim_revisao_cronograma_' + cronograma).val();
        let pontuacao = $('#vl_pontuacao_etapa_cronograma_' + cronograma).val();
        let ordem = $('#nr_ordem_etapa_cronograma_' + cronograma).val();

        etapa.sq_cronograma_processo_seletiv = cronograma;
        etapa.sq_etapa_processo_seletivo = sq_etapa;
        etapa.dt_inicio_etapa = inicio_etapa;
        etapa.dt_fim_etapa = fim_etapa;
        etapa.st_aceita_revisao = revisao;
        etapa.dt_inicio_revisao = inicio_revisao;
        etapa.dt_fim_revisao = fim_revisao;
        etapa.vl_pontuacao_etapa = pontuacao;
        etapa.nr_ordem_etapa = ordem;

        let url = $(this).data('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type    : "POST",
            url     : url,
            data    : {
                'etapa': etapa
            },
            success : function(retorno) {
                if (retorno)
                console.log('chegou', retorno);
                Swal.fire({
                    title: 'Etapa atualizada com sucesso.',
                    text: '',
                    icon: 'success',
                    showCancelButton: false
                });
                window.location.reload();
            },
            error   : function(XMLHttpRequest, textStatus, errorThrown) {
                console.log('Erro: ', errorThrown, ' Status: ', textStatus, ' Request: ', XMLHttpRequest);
                Swal.fire({
                    title: 'Erro ao tentar atualizar etapa.',
                    text: '',
                    icon: 'error',
                    showCancelButton: false
                });
            }

        });
    });

    $(document).on('click', '#remove_etapa_cronograma', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja remover a etapa?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#dc3c45',
            confirmButtonColor: '#55a846',
            confirmButtonText: 'Sim',
        }).then((result) => {
        if (result.value) {
            let url = $(this).data('href');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "POST",
                url     : url,
                data    : {
                    'sq_cronograma_processo_seletiv': $(this).attr('data-sq-cronograma'),
                },
                success : function(retorno) {
                    if (retorno.status) {
                        // console.log('chegou', retorno);
                        Swal.fire({
                            title: 'Etapa removida com sucesso.',
                            text: '',
                            icon: 'success',
                            showCancelButton: false
                        });
                        redirecionarEdicaoCronograma();
                    } else {
                        Swal.fire({
                            title: 'Erro ao tentar remover etapa.',
                            text: '',
                            icon: 'error',
                            showCancelButton: false
                        });
                    }
                },
                error   : function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log('Erro: ', errorThrown, ' Status: ', textStatus, ' Request: ', XMLHttpRequest);
                }
            });
        }
        });
    });

    function redirecionarEdicaoCronograma() {
        let rota = $("#rota_edicao_cronograma").val();
        window.location = rota;
    }
});

function habilitarEdicao(sq_cronograma_processo_seletiv) {
    $(".sq_cronograma_"+sq_cronograma_processo_seletiv).prop('disabled', false);
    $(".checkbox_sq_cronograma_"+sq_cronograma_processo_seletiv).prop('disabled', false);
    $(".btn_edicao_"+sq_cronograma_processo_seletiv).css('display', 'none');
    $(".btn_exclui_etapa_"+sq_cronograma_processo_seletiv).css('display', 'none');
    $(".btn_cancela_edicao_"+sq_cronograma_processo_seletiv).css('display', 'block');
    $(".btn_salva_edicao_"+sq_cronograma_processo_seletiv).css('display', 'block');
    $(".div_etapa_cronograma_"+sq_cronograma_processo_seletiv).css('flex-direction', 'row');
    $(".div_etapa_cronograma_"+sq_cronograma_processo_seletiv).css('justify-content', 'end');
}

function cancelarEdicao(sq_cronograma_processo_seletiv) {
    $(".sq_cronograma_"+sq_cronograma_processo_seletiv).prop('disabled', true);
    $(".checkbox_sq_cronograma_"+sq_cronograma_processo_seletiv).prop('disabled', true);
    $(".btn_cancela_edicao_"+sq_cronograma_processo_seletiv).css('display', 'none');
    $(".btn_salva_edicao_"+sq_cronograma_processo_seletiv).css('display', 'none');
    $(".btn_edicao_"+sq_cronograma_processo_seletiv).css('display', 'block');
    $(".btn_exclui_etapa_"+sq_cronograma_processo_seletiv).css('display', 'block');
    // $(".div_etapa_cronograma_"+sq_cronograma_processo_seletiv).css('flex-direction', 'column-reverse');
}

function habilitarPreenchimento(sq_cronograma_processo_seletiv) {
    if ($('.checkbox_sq_cronograma_'+sq_cronograma_processo_seletiv).is(':checked')) {
        // Habilita os inputs data inicio e data fim revisao
        $('.dt_inicio_revisao_edicao_'+sq_cronograma_processo_seletiv).prop('disabled', false);
        $('.dt_fim_revisao_edicao_'+sq_cronograma_processo_seletiv).prop('disabled', false);
    } else {
        // Desabilita os inputs data inicio e data fim revisao
        $('.dt_inicio_revisao_edicao_'+sq_cronograma_processo_seletiv).prop('disabled', true);
        $('.dt_fim_revisao_edicao_'+sq_cronograma_processo_seletiv).prop('disabled', true);
    }
}

var etapa = {
    sq_cronograma_processo_seletiv: null,
    sq_etapa_processo_seletivo: null,
    dt_inicio_etapa: '',
    dt_fim_etapa: '',
    st_aceita_revisao: false,
    dt_inicio_revisao: '',
    dt_fim_revisao: '',
    vl_pontuacao_etapa: 0,
    nr_ordem_etapa: null
}
