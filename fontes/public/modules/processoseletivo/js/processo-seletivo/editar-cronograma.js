$(document).ready(function() {
    $('#qt_pontuacao_max_etapa').on('input', function() {
        // Remove qualquer caractere diferente de número ou ponto
        this.value = this.value.replace(/[^0-9\.]/g, ''); // remove qualquer caractere que não seja um número ou um ponto
        this.value = this.value.replace(/(\d*\.\d*)\./g, '$1'); // remove pontos extras
        this.value = this.value.replace(/^(\d+)\.(\d*)$/, '$1.$2');
        // adiciona 0 à esquerda do ponto, caso digitem apenas o ponto
        if (this.value.startsWith('.')) {
            this.value = '0' + this.value;
        }

        if (this.value < 0) {
            this.value = 0;
        }
    });
});

var cronograma = {
    sq_processo_seletivo: $('#sq_processo_seletivo').val(),
    sq_grupo_processo_seletivo: null,
    etapas: []
}

function redirecionarIndexEdicao() {
    // $("#MyWindowModalSpinner").modal("show");
    let rotaPosSalvamento = $('#rota_editar_cronograma').val();
    window.location = rotaPosSalvamento;
}

function resetArrayEtapas() {
    return cronograma.etapas = [];
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

    if (cronograma.etapas.length === 0) {
        $("#tb_etapas tbody>tr").remove();
    }

    cronograma.etapas.push({
        indice_grupo: grupo,
        cd_tipo_etapa_processo_seletivo: cod_etapa,
        nome_etapa: no_etapa,
        dt_inicio_etapa: inicio,
        dt_fim_etapa: fim
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
    // Apenas formatando para exibição

    console.log(cronograma.etapas, inicio_exibicao, ano_inicio, mes_inicio, dia_inicio, hora_inicio, min_inicio);

    if (!$("#" + cod_etapa).length) {
        linha.push('<tr id="tr_etapa_' + cod_etapa + '">');
        linha.push('<td>' + no_etapa + '</td>');
        linha.push('<td class="text-center">' + inicio_exibicao + '</td>');
        linha.push('<td class="text-center">' + fim_exibicao + '</td>');
        linha.push('<td class="text-center"><i id="btn-remove-etapa" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover etapa" onclick="removerEtapa(event, '+cod_etapa+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="etapas[]" value="' + cod_etapa + '"></i></td>');
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
}

function isEmpty(obj) {
    for (let prop in obj) {
        if (obj.hasOwnProperty(prop))
            return false;
    }
    return true;
}

function removerEtapa(e, cod_etapa) {
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
            removerEtapaArray(cronograma.etapas, cod_etapa);
            $("#tr_etapa_" + cod_etapa).remove();
            if (cronograma.etapas.length === 0) {
                if ($("tr_etapas_0").length === 0) {
                    linha.push('<tr id="tr_etapas_0">');
                    linha.push('<td class="text-center"  colspan="3">Nenhuma etapa adicionada!</td>');
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

function salvarCronograma(e, form) {
    e.preventDefault();
    let rota = $('#rota_salva_cronograma').val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    $('.msg-erro-form').html('');
    if (cronograma.etapas.length === 0) {
        Swal.fire({
            title: 'É obrigatório adicionar pelo menos uma etapa. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if ($("#" + form).valid()) {

        cronograma.sq_grupo_processo_seletivo = $('#indice_grupo').val();
        // console.log(cronograma);
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
                'cronograma': cronograma
            },
            dataType: 'json',
            success: function (response) {
                if (response.status) {
                    console.log('Response: ' + response + " - Response Status: " + response.status);
                    redirecionarIndexEdicao();
                    resetArrayEtapas();
                    // helper.alertSuccess(response.msg);
                } else {
                    if (response.status == false) {
                        console.log('Response: ' + response + " - Response Status: " + response.status + " Message: " + response.message + " Exception: " + response.e);
                        redirecionarIndexEdicao();
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
