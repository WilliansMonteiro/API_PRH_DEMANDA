$(document).ready(function() {
    $('.money').mask('000,00', {reverse: true});
    $('#vl_pontuacao_etapa').on('input', function() {
        this.value = this.value.replace(/[^0-9\,]/g, ''); // remove qualquer caractere que não seja um número ou uma vírgula
        // this.value = this.value.replace(/(\d*\.\d*)\./g, '$1'); // remove pontos extras
        // this.value = this.value.replace(/^(\d+)\.(\d*)$/, '$1.$2');
        // adiciona 0 à esquerda do ponto, caso digitem apenas o ponto
        // if (this.value.startsWith('.')) {
        //     this.value = '0' + this.value;
        // }

        if (this.value < 0) {
            this.value = 0;
        }
    });

    $('#st_aceita_revisao').change(function() {
        if ($(this).is(':checked')) {
            // Habilita os inputs data inicio e data fim revisao
            $('#dt_inicio_revisao').prop('disabled', false);
            $('#dt_fim_revisao').prop('disabled', false);
        } else {
            // Desabilita os inputs data inicio e data fim revisao
            $('#dt_inicio_revisao').prop('disabled', true);
            $('#dt_fim_revisao').prop('disabled', true);
        }
    });
});

var cronogramas = {
    sq_processo_seletivo: $('#sq_processo_seletivo').val(),
    etapas: []
}

var ordens = [];

function redirecionarCreateCronogramas() {
    let rotaPosSalvamento = $('#rota_criar_cronogramas').val();
    window.location = rotaPosSalvamento;
}

function resetArrayEtapas() {
    return cronogramas.etapas = [];
}

function addEtapa(e) {
    e.preventDefault();
    let linha = [];
    let grupo = $('#indice_grupo').val();
    let dados_etapa = $('#cd_tipo_etapa_processo_seletivo').select2('data');
    let cod_etapa  = dados_etapa[0].id;
    let no_etapa = dados_etapa[0].text;
    let inicio = $('#dt_inicio').val();
    let fim = $('#dt_fim').val();
    let pontuacao = $('#vl_pontuacao_etapa').val();
    let ordem = $('#nr_ordem_etapa').val();
    let revisao = $('#st_aceita_revisao').is(':checked');
    let inicio_revisao = $('#dt_inicio_revisao').val();
    let fim_revisao = $('#dt_fim_revisao').val();
    let temRepeticao = false;

    if (grupo === '') {
        Swal.fire({
            title: 'Favor selecionar uma grupo para adicionar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (cod_etapa === '') {
        Swal.fire({
            title: 'Favor selecionar uma etapa para adicionar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (inicio === '') {
        Swal.fire({
            title: 'Favor selecionar uma data de início para a etapa!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (fim === '') {
        Swal.fire({
            title: 'Favor selecionar uma data de fim para a etapa!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (revisao === true && inicio_revisao === '') {
        Swal.fire({
            title: 'Favor selecionar uma data de início para a revisão da etapa!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (revisao === true && fim_revisao === '') {
        Swal.fire({
            title: 'Favor selecionar uma data de fim para a revisão da etapa!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (pontuacao === '') {
        pontuacao = '0';
    }

    for (var i = 0; i < cronogramas.etapas.length; i++) {
        // var ordemVerificada = cronogramas.etapas[i].nr_ordem_etapa;

        if (ordens.includes(ordem)) {
            temRepeticao = true;
            break;
        }
    }
    ordens.push(ordem);

    if (temRepeticao) {
        Swal.fire({
            title: 'Valor de ordem da etapa já utilizado. Favor verificar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (cronogramas.etapas.length === 0) {
        $("#tb_etapas tbody>tr").remove();
    }

    cronogramas.etapas.push({
        indice_grupo: grupo,
        cd_tipo_etapa_processo_seletivo: cod_etapa,
        nome_etapa: no_etapa,
        dt_inicio_etapa: inicio,
        dt_fim_etapa: fim,
        vl_pontuacao_etapa: pontuacao,
        nr_ordem_etapa: ordem,
        st_aceita_revisao: revisao,
        dt_inicio_revisao: inicio_revisao,
        dt_fim_revisao: fim_revisao
    });

    // Apenas formatando para exibição
    var inicio_exibicao = inicio.replace('T', ' ');
    var ano_inicio  = inicio.slice(0, 4);
    var mes_inicio  = inicio.slice(5, 7);
    var dia_inicio  = inicio.slice(8, 10);
    var hora_inicio = inicio.slice(11, 13);
    var min_inicio  = inicio.slice(14, 16);
    inicio_exibicao = dia_inicio + '/' + mes_inicio + '/' + ano_inicio + ' ' + hora_inicio + ':' + min_inicio;

    var fim_exibicao = fim.replace('T', ' ');
    var ano_fim  = fim.slice(0, 4);
    var mes_fim  = fim.slice(5, 7);
    var dia_fim  = fim.slice(8, 10);
    var hora_fim = fim.slice(11, 13);
    var min_fim  = fim.slice(14, 16);
    fim_exibicao = dia_fim + '/' + mes_fim + '/' + ano_fim + ' ' + hora_fim + ':' + min_fim;

    if (revisao === true) {
        var inicio_revisao_exibicao = inicio_revisao.replace('T', ' ');
        var ano_inicio_revisao  = inicio_revisao.slice(0, 4);
        var mes_inicio_revisao  = inicio_revisao.slice(5, 7);
        var dia_inicio_revisao  = inicio_revisao.slice(8, 10);
        var hora_inicio_revisao = inicio_revisao.slice(11, 13);
        var min_inicio_revisao  = inicio_revisao.slice(14, 16);
        inicio_revisao_exibicao = dia_inicio_revisao + '/' + mes_inicio_revisao + '/' + ano_inicio_revisao + ' ' + hora_inicio_revisao + ':' + min_inicio_revisao;

        var fim_revisao_exibicao = fim_revisao.replace('T', ' ');
        var ano_fim_revisao  = fim_revisao.slice(0, 4);
        var mes_fim_revisao  = fim_revisao.slice(5, 7);
        var dia_fim_revisao  = fim_revisao.slice(8, 10);
        var hora_fim_revisao = fim_revisao.slice(11, 13);
        var min_fim_revisao  = fim_revisao.slice(14, 16);
        fim_revisao_exibicao = dia_fim_revisao + '/' + mes_fim_revisao + '/' + ano_fim_revisao + ' ' + hora_fim_revisao + ':' + min_fim_revisao;
    } else {
        inicio_revisao_exibicao = '-';
        fim_revisao_exibicao = '-';
    }
    // Apenas formatando para exibição

    if (!$("#" + cod_etapa).length) {
        linha.push('<tr id="tr_etapa_' + cod_etapa + '">');
        linha.push('<td class="text-center">' + ordem + '</td>');
        linha.push('<td class="text-center">' + no_etapa + '</td>');
        linha.push('<td class="text-center">' + inicio_exibicao + '</td>');
        linha.push('<td class="text-center">' + fim_exibicao + '</td>');
        linha.push('<td class="text-center">' + pontuacao + '</td>');
        if (revisao === true) {
            linha.push('<td class="text-center">Sim</td>');
        } else {
            linha.push('<td class="text-center">Não</td>');
        }
        linha.push('<td class="text-center">' + inicio_revisao_exibicao + '</td>');
        linha.push('<td class="text-center">' + fim_revisao_exibicao + '</td>');
        linha.push('<td class="text-center"><i id="btn-remove-etapa" data-toggle="tooltip" data-placement="top" title="" data-ordem="' + ordem + '" data-original-title="Remover etapa" onclick="removerEtapa(event, '+cod_etapa+', '+ordem+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="etapas[]" value="' + cod_etapa + '"></i></td>');
        linha.push('</tr>');
        $(['#tb_etapas tbody'].join('')).append(linha.join(''));
    }
    linha.length = 0;
    $("#cd_tipo_etapa_processo_seletivo").select2("val", "");

    // Limpa select de cientes após opção ser adicionada
    $("#cd_tipo_etapa_processo_seletivo").find("option").prop("selected", function () {
        return this.defaultSelected;
    });

    $("select").trigger("change.select2");
    $("input[type=datetime-local]").val("");
    $('#vl_pontuacao_etapa').val("");
    $('#nr_ordem_etapa').val(parseInt(ordem) + 1);
    $('#st_aceita_revisao').prop('checked', false);
    $('#dt_inicio_revisao').prop('disabled', true);
    $('#dt_fim_revisao').prop('disabled', true);
}

function isEmpty(obj) {
    for (let prop in obj) {
        if (obj.hasOwnProperty(prop))
            return false;
    }
    return true;
}

function removerEtapa(e, cod_etapa, ordem) {
    e.preventDefault();
    let linha = [];
    Swal.fire({
        title: 'Tem certeza que deseja excluir esta etapa?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        cancelButtonColor: '#dc3c45',
        confirmButtonColor: '#55a846',
        confirmButtonText: 'Sim',
    }).then((result) => {
        console.log(result);
        console.log(result.value);

        if (result.value) {
            removerEtapaArray(cronogramas.etapas, cod_etapa);
            removerOrdemArray(ordem);
            $("#tr_etapa_" + cod_etapa).remove();
            if (cronogramas.etapas.length === 0) {
                if ($("tr_etapas_0").length === 0) {
                    linha.push('<tr id="tr_etapas_0">');
                    linha.push('<td class="text-center" colspan="9">Nenhuma etapa adicionada</td>');
                    linha.push('</tr>');
                    $(['#tb_etapas tbody'].join('')).append(linha.join(''));
                }
            }
            $("#cd_tipo_etapa_processo_seletivo").select2("val", "");
        }
    });
}

function removerEtapaArray(array, valueRemove) {
    let arr = array;
    for (let i = 0; i < arr.length; i++) {
        if (arr[i].cd_tipo_etapa_processo_seletivo == valueRemove) {
            arr.splice(i, 1);
            i--;
        }
    }
}

function removerOrdemArray(ordem) {
    ordem = ordem.toString();

    for (var i = ordens.length - 1; i >= 0; i--) {
        if (ordens[i] === ordem) {
            ordens.splice(i, 1);
        }
    }
}

function salvarCronograma(e, form) {
    e.preventDefault();
    let rota = $('#rota_salva_cronograma').val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    $('.msg-erro-form').html('');
    if (cronogramas.etapas.length === 0) {
        Swal.fire({
            title: 'É obrigatório adicionar pelo menos uma etapa. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if ($("#" + form).valid()) {
        // console.log(cronogramas);
        // return;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: rota,
            type: 'POST',
            data: {
                _token: _token,
                'cronogramas': cronogramas
            },
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    console.log('Response: ' + response + " - Response Status: " + response.status);
                    redirecionarCreateCronogramas();
                    resetArrayEtapas();
                } else {
                    if (response.status == false) {
                        console.log('Response: ' + response + " - Response Status: " + response.status + " Message: " + response.message + " Exception: " + response.e);
                        redirecionarCreateCronogramas();
                    } else {
                        console.log('Response: ' + response);
                        return;
                    }
                }
            }
        });
    }

    return false;
}
