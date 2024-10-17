(function () {

    let arPergunta = {
        no_pergunta_formulario: null,
        tx_pergunta_formulario: null,
        vl_maximo_pergunta: null,
        st_obrigatorio: null,
        sq_pergunta_formulario: null,
    };

    function init() {
        config();
        loadRespostas();
    }

    function config() {
        $('#tx_pergunta_formulario').summernote('disable');
    }

    function loadRespostas() {
        let rota = $("#rotaSalvarPergunta").val();
        let rotaCarregaTabelaResposta = $("#rotaCarregaTabelaResposta").val();
        let sqPergunta = $('#sq_pergunta_formulario').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: rotaCarregaTabelaResposta,
            type: "GET",
            data: {'sq_pergunta_formulario': sqPergunta},
            success: function (response) {
                console.table(response)
                populateTableRespostas(response)
            },
            beforeSend: function () {
                $("#tbodyResposta").empty();
                let row = ['<tr>'];
                row.push('<td align="center" colspan="3"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb_resposta tbody'].join('')).append(row.join(''));
            },
            error: function (response) {
                console.info(response)
            }
        });
    }

    function populateTableRespostas(arRespostas) {
        $("#tbodyResposta").empty();


        if (arRespostas.length != 0) {
            $.each(arRespostas, function (i, value) {
                let row = [];
                row.push('<tr id="' + value.sq_resposta_formulario + '">');
                row.push('<td>' + value.tx_resposta + '</td>');
                row.push('<td>')
                $.each(value.itens_respostas, function (indice, valueItem) {
                    row.push('<b>Desc:</b> ' + valueItem.tipo_item_resposta.no_tipo_item_resposta + ', <b>Valor:</b>' + parseFloat(valueItem.vl_item_resposta).toFixed(2).toString() + '<br>');
                });
                row.push('</td>');
                row.push('</tr>');
                $(['#tb_resposta tbody'].join('')).append(row.join(''));
            })
            row.length = 0;
        } else {
            let row = [];
            row.push('<tr id="tr_resposta_0">');
            row.push('<td class="text-center" colspan="2">Nenhum registro encontrado</td>');
            row.push('</tr>');
            $(['#tb_resposta tbody'].join('')).append(row.join(''));
        }
    }



    init();

})();
