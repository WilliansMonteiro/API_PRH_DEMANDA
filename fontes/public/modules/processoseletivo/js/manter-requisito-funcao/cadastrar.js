$(document).ready(function() {
    // MASCARA DA PONTUACAO DECIMAL
    $('.money').each(function() {
        var valor = parseFloat($(this).val().replace(',', '.')).toFixed(2);
        $(this).val(valor.replace('.', ','));
    });
    $('.money').mask('000,00', {
        reverse: true,
        placeholder: '0,00'
    });

    // ONCHANGE DE TRILHAS E FUNCOES. FUNCOES DEPENDEM DA TRILHA ESCOLHIDA
    $(document).on('change', '#trilha', function (e) {
        var trilha_escolhida = $(this).children("option:selected").val();

        let url = $(this).children("option:selected").data('href') + '/' + trilha_escolhida;

        if (!url.includes("undefined")) {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (retorno) {
                    console.log(retorno);

                    function fillSelect(retorno) {
                        var select = $("#funcoes");
                        select.empty();

                        for (var i=0; i < retorno.length; i++) {
                            select.append('<option value="' + retorno[i].handle + '">' + retorno[i].nome + '</option>');
                        }
                    }
                    fillSelect(retorno);
                },
                beforeSend: function () {
                    $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                },
                complete: function () {
                    $('#carregar').html("");
                }
            });
        } else {
            $("#funcoes").empty();
        }
    });
});

var requisito = {
    nome: "",
    pccr: "",
    trilha: "",
    funcao: "",
    escolaridade: "",
    exercicio: "",
    experiencia_ext: "",
    pontuacao_exp_ext: "",
    experiencia_brb: "",
    pontuacao_exp_brb: "",
    qualificacoes: [],
    certificacoes: [],
    funcoes_gratificadas: []
}

// var certificacoes = {
//     certificacao: []
// }
var certificacoes_adicionadas = [];

// var qualificacoes = {
//     qualificacao: []
// }
var qualificacoes_adicionadas = [];

// var funcoes_gratificadas = {
//     funcao: []
// }
var funcoes_gratificadas_adicionadas = [];

function redirecionarCreateRequisito() {
    let rota = $('#rota_criar_requisito').val();
    window.location = rota;
}

function redirecionarIndexRequisito() {
    let rota = $('#rota_index_requisto').val();
    window.location = rota;
}

function resetArrayQualificacoes() {
    return requisito.qualificacoes = [];
}

function resetArrayCertificacoes() {
    return requisito.certificacoes = [];
}

function resetArrayFuncoesGratificadas() {
    return requisito.funcoes_gratificadas = [];
}

function resetArraysRequisito() {
    resetArrayQualificacoes();
    resetArrayCertificacoes();
    resetArrayFuncoesGratificadas();
    // return;
}

function addCertificacao(e) {
    e.preventDefault();
    let linha = [];
    let cod_certificacao = $('#cd_certificacao').val();
    let nome_certificacao = $('#cd_certificacao').find('option:selected').text();
    let pontuacao = $('#vl_certificacao').val();
    let certificacao_repetida = false;

    if (cod_certificacao === '') {
        Swal.fire({
            title: 'Favor selecionar uma certificação para adicionar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (pontuacao === '') {
        Swal.fire({
            title: 'Favor informar um valor para a pontuação!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    for (var i = 0; i < requisito.certificacoes.length; i++) {
        if (certificacoes_adicionadas.includes(cod_certificacao)) {
            certificacao_repetida = true;
            break;
        }
    }
    certificacoes_adicionadas.push(cod_certificacao);

    if (certificacao_repetida) {
        Swal.fire({
            title: 'Certificação já adicionada. Favor verificar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.certificacoes.length === 0) {
        $("#tb_certificacoes tbody>tr").remove();
    }

    requisito.certificacoes.push({
        cd_tipo_qualificacao: cod_certificacao,
        vl_pontuacao_qualificacao: pontuacao
    });

    console.log(requisito.certificacoes);

    if (!$("#" + cod_certificacao).length) {
        linha.push('<tr id="tr_certificacao_' + cod_certificacao + '">');
        linha.push('<td class="text-center">' + nome_certificacao + '</td>');
        linha.push('<td class="text-center">' + pontuacao + '</td>');
        linha.push('<td class="text-center"><i id="btn-remove-certificacao" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover certificação" onclick="removerCertificacao(event, '+cod_certificacao+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="certificacao[]" value="' + cod_certificacao + '"></i></td>');
        linha.push('</tr>');
        $(['#tb_certificacoes tbody'].join('')).append(linha.join(''));
    }
    linha.length = 0;
    $("#cd_certificacao").select2("val", "");

    // Limpa select de cientes após opção ser adicionada
    $("#cd_certificacao").find("option").prop("selected", function () {
        return this.defaultSelected;
    });

    $("select").trigger("change.select2");
    $("input[type=datetime-local]").val("");
    $('#vl_certificacao').val("");
}

function removerCertificacao(e, cod_certificacao) {
    e.preventDefault();
    let linha = [];
    Swal.fire({
        title: 'Tem certeza que deseja excluir esta certificação?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        cancelButtonColor: '#dc3c45',
        confirmButtonColor: '#55a846',
        confirmButtonText: 'Sim',
    }).then((result) => {
        // console.log(result);
        // console.log(result.value);

        if (result.value) {
            removerCertificacaoArray(requisito.certificacoes, certificacoes_adicionadas, cod_certificacao);
            $("#tr_certificacao_" + cod_certificacao).remove();
            console.log(requisito.certificacoes.length);
            if (requisito.certificacoes.length === 0) {
                if ($("tr_certificacao_0").length === 0) {
                    linha.push('<tr id="tr_certificacao_0">');
                    linha.push('<td class="text-center" colspan="3">Nenhuma certificação adicionada</td>');
                    linha.push('</tr>');
                    $(['#tb_certificacoes tbody'].join('')).append(linha.join(''));
                }
            }
            $("#cd_certificacao").select2("val", "");
        }
    });
}

function removerCertificacaoArray(arrayCertificacoes, arrayVerificacaoRepeticao, valueRemove) {
    let arr = arrayCertificacoes;
    let arrayRepeticao = arrayVerificacaoRepeticao;

    for (let i = 0; i < arr.length; i++) {
        if (arr[i].cd_tipo_qualificacao == valueRemove) {
            arr.splice(i, 1);
            i--;
        }
    }

    for (let i = 0; i < arrayRepeticao.length; i++) {
        if (arrayRepeticao[i] == valueRemove.toString()) {
            arrayRepeticao.splice(i, 1);
            i--;
        }
    }
    // console.log('repetidas: ', arrayVerificacaoRepeticao);
}

function addQualificacao(e) {
    e.preventDefault();
    let linha = [];
    let cod_qualificacao = $('#cd_qualificacao').val();
    let nome_qualificacao = $('#cd_qualificacao').find('option:selected').text();
    let pontuacao = $('#vl_qualificacao').val();
    let qualificacao_repetida = false;

    if (cod_qualificacao === '') {
        Swal.fire({
            title: 'Favor selecionar uma qualificação para adicionar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (pontuacao === '') {
        Swal.fire({
            title: 'Favor informar um valor para a pontuação!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    for (var i = 0; i < requisito.qualificacoes.length; i++) {
        if (qualificacoes_adicionadas.includes(cod_qualificacao)) {
            qualificacao_repetida = true;
            break;
        }
    }
    qualificacoes_adicionadas.push(cod_qualificacao);

    if (qualificacao_repetida) {
        Swal.fire({
            title: 'Qualificação já adicionada. Favor verificar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.qualificacoes.length === 0) {
        $("#tb_qualificacoes tbody>tr").remove();
    }

    requisito.qualificacoes.push({
        cd_tipo_qualificacao: cod_qualificacao,
        vl_pontuacao_qualificacao: pontuacao
    });

    console.log(requisito.qualificacoes);

    if (!$("#" + cod_qualificacao).length) {
        linha.push('<tr id="tr_qualificacao_' + cod_qualificacao + '">');
        linha.push('<td class="text-center">' + nome_qualificacao + '</td>');
        linha.push('<td class="text-center">' + pontuacao + '</td>');
        linha.push('<td class="text-center"><i id="btn-remove-qualificacao" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover certificação" onclick="removerQualificacao(event, '+cod_qualificacao+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="qualificacao[]" value="' + cod_qualificacao + '"></i></td>');
        linha.push('</tr>');
        $(['#tb_qualificacoes tbody'].join('')).append(linha.join(''));
    }
    linha.length = 0;
    $("#cd_qualificacao").select2("val", "");

    // Limpa select de cientes após opção ser adicionada
    $("#cd_qualificacao").find("option").prop("selected", function () {
        return this.defaultSelected;
    });

    $("select").trigger("change.select2");
    $("input[type=datetime-local]").val("");
    $('#vl_qualificacao').val("");
}

function removerQualificacao(e, cod_qualificacao) {
    e.preventDefault();
    let linha = [];
    Swal.fire({
        title: 'Tem certeza que deseja excluir esta qualificação?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        cancelButtonColor: '#dc3c45',
        confirmButtonColor: '#55a846',
        confirmButtonText: 'Sim',
    }).then((result) => {
        // console.log(result);
        // console.log(result.value);

        if (result.value) {
            removerQualificacaoArray(requisito.qualificacoes, qualificacoes_adicionadas, cod_qualificacao);
            $("#tr_qualificacao_" + cod_qualificacao).remove();
            console.log(requisito.qualificacoes.length);
            if (requisito.qualificacoes.length === 0) {
                if ($("tr_qualificacao_0").length === 0) {
                    linha.push('<tr id="tr_qualificacao_0">');
                    linha.push('<td class="text-center" colspan="3">Nenhuma qualificação adicionada</td>');
                    linha.push('</tr>');
                    $(['#tb_qualificacoes tbody'].join('')).append(linha.join(''));
                }
            }
            $("#cd_qualificacao").select2("val", "");
        }
    });
}

function removerQualificacaoArray(arrayQualificacoes, arrayVerificacaoRepeticao, valueRemove) {
    let arr = arrayQualificacoes;
    let arrayRepeticao = arrayVerificacaoRepeticao;

    for (let i = 0; i < arr.length; i++) {
        if (arr[i].cd_tipo_qualificacao == valueRemove) {
            arr.splice(i, 1);
            i--;
        }
    }

    for (let i = 0; i < arrayRepeticao.length; i++) {
        if (arrayRepeticao[i] == valueRemove.toString()) {
            arrayRepeticao.splice(i, 1);
            i--;
        }
    }
    // console.log('repetidas: ', arrayVerificacaoRepeticao);
}

function addFuncaoGratificada(e) {
    e.preventDefault();
    let linha = [];
    let cod_funcao = $('#sq_exercicio_funcao_gratificad').val();
    let nome_funcao = $('#sq_exercicio_funcao_gratificad').find('option:selected').text();
    let pontuacao = $('#vl_funcao_gratificada').val();
    let funcao_repetida = false;

    if (cod_funcao === '') {
        Swal.fire({
            title: 'Favor selecionar um exercício em função gratificada para adicionar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (pontuacao === '') {
        Swal.fire({
            title: 'Favor informar um valor para a pontuação!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    for (var i = 0; i < requisito.funcoes_gratificadas.length; i++) {
        if (funcoes_gratificadas_adicionadas.includes(cod_funcao)) {
            funcao_repetida = true;
            break;
        }
    }
    funcoes_gratificadas_adicionadas.push(cod_funcao);

    if (funcao_repetida) {
        Swal.fire({
            title: 'Exercício em função gratificada já adicionado. Favor verificar!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.funcoes_gratificadas.length === 0) {
        $("#tb_funcoes_gratificadas tbody>tr").remove();
    }

    requisito.funcoes_gratificadas.push({
        sq_exercicio_funcao_gratificad: cod_funcao,
        vl_pontuacao_func_gratificad: pontuacao
    });

    console.log(requisito.funcoes_gratificadas);

    if (!$("#" + cod_funcao).length) {
        linha.push('<tr id="tr_funcao_' + cod_funcao + '">');
        linha.push('<td class="text-center">' + nome_funcao + '</td>');
        linha.push('<td class="text-center">' + pontuacao + '</td>');
        linha.push('<td class="text-center"><i id="btn-remove-funcao-gratificada" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover função gratificada" onclick="removerFuncaoGratificada(event, '+cod_funcao+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="funcao[]" value="' + cod_funcao + '"></i></td>');
        linha.push('</tr>');
        $(['#tb_funcoes_gratificadas tbody'].join('')).append(linha.join(''));
    }
    linha.length = 0;
    $("#sq_exercicio_funcao_gratificad").select2("val", "");

    // Limpa select de cientes após opção ser adicionada
    $("#sq_exercicio_funcao_gratificad").find("option").prop("selected", function () {
        return this.defaultSelected;
    });

    $("select").trigger("change.select2");
    $("input[type=datetime-local]").val("");
    $('#vl_funcao_gratificada').val("");
}

function removerFuncaoGratificada(e, cod_funcao) {
    e.preventDefault();
    let linha = [];
    Swal.fire({
        title: 'Tem certeza que deseja excluir este exercício em função gratificada?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        cancelButtonColor: '#dc3c45',
        confirmButtonColor: '#55a846',
        confirmButtonText: 'Sim',
    }).then((result) => {
        // console.log(result);
        // console.log(result.value);

        if (result.value) {
            removerFuncaoGratificadaArray(requisito.funcoes_gratificadas, funcoes_gratificadas_adicionadas, cod_funcao);
            $("#tr_funcao_" + cod_funcao).remove();
            console.log(requisito.funcoes_gratificadas.length);
            if (requisito.funcoes_gratificadas.length === 0) {
                if ($("tr_funcao_0").length === 0) {
                    linha.push('<tr id="tr_funcao_0">');
                    linha.push('<td class="text-center" colspan="3">Nenhum exercício em função gratificada adicionado</td>');
                    linha.push('</tr>');
                    $(['#tb_funcoes_gratificadas tbody'].join('')).append(linha.join(''));
                }
            }
            $("#sq_exercicio_funcao_gratificad").select2("val", "");
        }
    });
}

function removerFuncaoGratificadaArray(arrayFuncoesGratificadas, arrayVerificacaoRepeticao, valueRemove) {
    let arr = arrayFuncoesGratificadas;
    let arrayRepeticao = arrayVerificacaoRepeticao;

    for (let i = 0; i < arr.length; i++) {
        if (arr[i].sq_exercicio_funcao_gratificad == valueRemove) {
            arr.splice(i, 1);
            i--;
        }
    }

    for (let i = 0; i < arrayRepeticao.length; i++) {
        if (arrayRepeticao[i] == valueRemove.toString()) {
            arrayRepeticao.splice(i, 1);
            i--;
        }
    }
    // console.log('repetidas: ', arrayVerificacaoRepeticao);
}

function validacoes() {
    if (requisito.nome === '') {
        Swal.fire({
            title: 'É obrigatório informar o nome do requisito!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.pccr === '') {
        Swal.fire({
            title: 'É obrigatório informar o PCCR do requisito!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.trilha === '') {
        Swal.fire({
            title: 'É obrigatório informar a trilha do requisito!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.funcao === '') {
        Swal.fire({
            title: 'É obrigatório informar a função do requisito!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.exercicio === '') {
        Swal.fire({
            title: 'É obrigatório informar a necessidade de exercício na função ou funções superiores!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.experiencia_ext === '') {
        Swal.fire({
            title: 'É obrigatório informar a experiência externa exigida pelo requisito!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.pontuacao_exp_ext === '') {
        Swal.fire({
            title: 'É obrigatório informar a pontuação da experiência externa!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.experiencia_brb === '') {
        Swal.fire({
            title: 'É obrigatório informar a experiência no BRB exigida pelo requisito!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (requisito.pontuacao_exp_brb === '') {
        Swal.fire({
            title: 'É obrigatório informar a pontuação da experiência no BRB!',
            text: '',
            icon: 'warning'
        })
        return;
    }
}

function salvarRequisito(e, form) {
    e.preventDefault();
    let rota = $('#rota_salva_requisito').val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    $('.msg-erro-form').html('');
    let no_requisito = $('#no_requisito_funcao_pccr').val();
    let pccr_requisito = $('#sq_pccr').val();
    let trilha_requisito = $('#trilha').val();
    let funcao_requisito = $('#funcoes').val();
    let escolaridade_requisito = $('#escolaridade').val();
    let exercicio_requisito = $('#st_exercicio_funcao').val();
    let exp_externa = $('#qt_periodo_experiencia').val();
    let pontuacao_ext = $('#vl_pontuacao_exp_externa').val();
    let exp_brb = $("#qt_periodo_experiencia_brb").val();
    let pontuacao_brb = $("#vl_pontuacao_exp_brb").val();

    requisito.nome              = no_requisito;
    requisito.pccr              = pccr_requisito;
    requisito.trilha            = trilha_requisito;
    requisito.funcao            = funcao_requisito;
    requisito.escolaridade      = escolaridade_requisito;
    requisito.exercicio         = exercicio_requisito;
    requisito.experiencia_ext   = exp_externa;
    requisito.pontuacao_exp_ext = pontuacao_ext;
    requisito.experiencia_brb   = exp_brb;
    requisito.pontuacao_exp_brb = pontuacao_brb;
    validacoes();

    if ($("#" + form).valid()) {
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
                'requisito': requisito
            },
            dataType: 'json',
            success: function (retorno) {
                if (retorno)
                    Swal.fire({
                        title: 'Requisito cadastrado com sucesso.',
                        text: '',
                        icon: 'success',
                        showCancelButton: false
                    });
                resetArraysRequisito();
                redirecionarIndexRequisito();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown, retorno) {
                console.log(retorno);
                Swal.fire({
                    title: 'Erro ao tentar cadastrar requisito.',
                    text: '',
                    icon: 'error',
                    showCancelButton: false
                });
            }
        });
    }

    return false;
}

function isEmpty(obj) {
    for (let prop in obj) {
        if (obj.hasOwnProperty(prop))
            return false;
    }
    return true;
}
