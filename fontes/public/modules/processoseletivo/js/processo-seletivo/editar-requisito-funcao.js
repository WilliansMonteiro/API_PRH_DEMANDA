$(document).ready(function () {
    $(document).on('change', '#requisito_funcao', function() {
        var noRequisito = $('#requisito_funcao :selected').text();
        var sq_requisito = $('#requisito_funcao :selected').val();
        var url_requisito = $(this).children("option:selected").data('href') + '/' + sq_requisito;
        var dados_requisito = buscaRequisito(url_requisito);
        preencheCardRequisito(dados_requisito);

        // console.log('requisito: ', noRequisito, ' sq_requisito: ', sq_requisito, ' url: ', url_requisito);
        $('#no_requisito').val(noRequisito);
    });

    function buscaRequisito(url) {
        var obj;
        if (!url.includes("undefined")) {
            $.ajax({
                async: false,
                type: 'GET',
                url: url,
                success: function (retorno) {
                    obj = retorno;
                },
                beforeSend: function () {
                    $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                },
                complete: function () {
                    $('#carregar').html("");
                }
            });
        }
        return obj;
    }

    function preencheCardRequisito(dados_requisito) {
        $('#req_nome').html(dados_requisito['nome']);
        $('#req_pccr').html(dados_requisito['pccr']);
        $('#req_trilha').html(dados_requisito['trilha']);
        $('#req_escolaridade').html(dados_requisito['escolaridade']);
        $('#req_exp_brb').html(dados_requisito['qt_periodo_experiencia_brb']);
        $('#req_exp_ext').html(dados_requisito['qt_periodo_experiencia']);

        if (dados_requisito['st_exercicio_funcao']) {
            $('#req_exercicio').html('Sim');
        } else {
            $('#req_exercicio').html('Não');
        }

        var div_funcoes_gratificadas = $("#req_funcoes_gratificadas");
        div_funcoes_gratificadas.html('');
        var  contadorFuncaoGratificada = 0;
        Object.keys(dados_requisito).forEach((key) => {
            if (key.includes('funcao_gratificada')) {
                div_funcoes_gratificadas.append('<p>' + dados_requisito['funcao_gratificada' + contadorFuncaoGratificada] + '</p>');
                contadorFuncaoGratificada++;
            }
        });

        var div_certificacoes = $("#req_certificacoes");
        div_certificacoes.html('');
        var  contadorCertificacoes = 0;
        Object.keys(dados_requisito).forEach((key) => {
            if (key.includes('certificacao')) {
                div_certificacoes.append('<p>' + dados_requisito['certificacao' + contadorCertificacoes] + '</p>');
                contadorCertificacoes++;
            }
        });

        var div_qualificacoes = $("#req_qualificacoes");
        div_qualificacoes.html('');
        var  contadorQualificacoes = 0;
        Object.keys(dados_requisito).forEach((key) => {
            if (key.includes('qualificacao')) {
                div_qualificacoes.append('<p>' + dados_requisito['qualificacao' + contadorQualificacoes] + '</p>');
                contadorQualificacoes++;
            }
        });
    }
})
