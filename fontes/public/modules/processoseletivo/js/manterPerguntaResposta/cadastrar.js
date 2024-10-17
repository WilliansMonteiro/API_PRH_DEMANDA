(function () {
    let arPergunta = {
        no_pergunta_formulario: null,
        tx_pergunta_formulario: null,
        vl_maximo_pergunta: null,
        st_obrigatorio: null,
        arResposta: [],
    };


    function init() {
        events();
        config();
        $('#tx_pergunta_formulario').summernote({
            placeholder: 'Digite a pergunta',
            tabsize: 4,
            height: 100
        });

    }

    function events() {
        $(document).on('click', '#btnAddItemResposta', addItensRespostas);
        $(document).on('click', '#btnRemItemResposta', removeItemResposta);
        $(document).on('click', '#btnAddResposta', addResposta);
        $(document).on('click', '#info-pergunta-resposta', onInfoPerguntaResposta);
        $(document).on('click', '#close-info-pergunta-resposta', onClosePerguntaResposta);
        $(document).on('click', '#btnExcluirResposta', excluirResposta);
        $(document).on('click', '#btnSalvarPergunta', salvarPergunta);
    }

    function config() {

        $('.money').mask('000,00', {reverse: true});

        let infoPerguntaResposta = document.getElementById("info-pergunta-resposta");

        infoPerguntaResposta.addEventListener("mouseover", function (event) {
            // highlight the mouseover target
            var stop = $('.info-pergunta-resposta').fadeIn().offset().top;
            var delay = 500;
            $('body').animate({scrollTop: stop + 'px'}, delay);
            return true;

            // reset the color after a short delay
            setTimeout(function () {
                var stop = $('.info-pergunta-resposta').fadeOut().offset().top;
                var delay = 500;
                $('body').animate({scrollTop: stop + 'px'}, delay);
                return true;
            }, 500);
        }, false);
    }

    function resetInputRespostas() {
        $("#no_resposta_formulario, .vlItemResposta ").val('')
    }

    function onClosePerguntaResposta() {
        setTimeout(function () {
            var stop = $('.info-pergunta-resposta').fadeOut().offset().top;
            var delay = 500;
            $('body').animate({scrollTop: stop + 'px'}, delay);
            return true;
        }, 500)
    }

    function onInfoPerguntaResposta() {
        setTimeout(function () {
            var stop = $('.info-pergunta-resposta').fadeIn().offset().top;
            var delay = 500;
            $('body').animate({scrollTop: stop + 'px'}, delay);
            return true;
        }, 500)
    }

    function validFormResposta() {
        let noResposta = $('#no_resposta_formulario').val();
        let error = [];

        if (noResposta == "") {
            $('#msg-error-no-resposta').text('O Campo resposta é de preenchimento obrigatório, verifique!');
            error.push('O Campo resposta é de preenchimento obrigatório, verifique!')
        }

        let arText = $('.dsItemResposta').map(function () {
            let numberId = Number(this.id.split('_')[3]);
            if ((numberId === 1 || numberId === 2) && this.value == "") {
                $('#msg-error-ds-item-resposta-' + numberId).text('O Campo item de resposta é de preenchimento obrigatório, verifique!');
                error.push('O Campo item de resposta é de preenchimento obrigatório, verifique!')
            }
        }).get();


        if (error.length !== 0) {
            return false;
            helper.alertError('Erros no formulário, verifique!');
        }

        return true;

    }


    function addResposta() {
        $('.msg-erro-form-resposta').html('');

        let noResposta = $('#no_resposta_formulario').val();
        let nrOrdem = $('#nr_ordem').val();
        let lastItem = Number($('.item:last').attr('id').split('_')[1]);
        let arItemRespostaLocal = [];
        let idResposta = Math.floor(Date.now() * Math.random()).toString(36);

        if (validFormResposta()) {
            $('#carregarResposta').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adicionando...');
            for (var i = 1; i <= lastItem; i++) {

                if (arItemRespostaLocal.filter(val => val.ds_item_resposta == $('#ds_item_resposta_' + i).val()).length === 0) {

                    if(typeof $('#ds_item_resposta_' + i).val() !== 'undefined'){
                        arItemRespostaLocal.push({
                            id_resposta: idResposta,
                            id: i,
                            ds_item_resposta: $('#ds_item_resposta_' + i).val(),
                            vl_item_resposta: $('#vl_item_resposta_' + i).val(),
                        });
                        $('.itemResposta_' + i).attr('disabled', 'disabled')

                    }
                } else {
                    helper.alertInformation('Item de resposta já foi cadastrado com a mesma descrição, verifique!')

                    if (i > 2) {
                        $('#itemResposta_' + i).remove();
                    }
                }
            }

            if (arPergunta.arResposta.filter(val => val.id_resposta == idResposta).length === 0) {
                arPergunta.arResposta.push({
                    id_resposta: idResposta,
                    no_resposta_formulario: noResposta,
                    nr_ordem: nrOrdem,
                    arItemResposta: arItemRespostaLocal
                })
            }
            populateTableRespostas(arPergunta.arResposta);
            resetInputRespostas();
            $('#carregarResposta').html('');
        }

    }

    function populateTableRespostas(arRespostas) {
        $('#tb_resposta tbody').html('');

        $.each(arRespostas, function(i, value){
            let row = [];
            row.push('<tr id="' + value.id_resposta + '">');
            row.push('<td>' + value.no_resposta_formulario + '</td>');
            row.push('<td>')
            $.each(value.arItemResposta, function(indice, valueItem){
                if ((valueItem.id_resposta == value.id_resposta)  && (typeof valueItem.ds_item_resposta !== 'undefined')) {
                    row.push('<b>Desc:</b> '+ valueItem.ds_item_resposta + ', <b>Valor:</b>' + valueItem.vl_item_resposta + '<br>');
                }
            });
            row.push('</td>');
            row.push('<td class="text-center"><a class="btn btn-small btn-danger" href="" id="btnExcluirResposta" data-id-resposta="' + value.id_resposta + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir Resposta"></i></a></td>');
            row.push('</tr>');
            $(['#tb_resposta tbody'].join('')).append(row.join(''));
            row.length = 0;
        })

    }

    function excluirResposta(e) {
        e.preventDefault();
        $('#carregarResposta').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
        let idResposta = $(this).data('id-resposta');
        let row = [];

        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#55a846',
            confirmButtonColor: '#dc3c45',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if (result.value) {
                removerValorRespostaArray(arPergunta.arResposta, idResposta);
                $("#" + idResposta).remove();
                if (arPergunta.arResposta.length === 0) {
                    if ($("tr_resposta_0").length === 0) {
                        row.push('<tr id="tr_resposta_0">');
                        row.push('<td class="text-center"  colspan="3">Nenhum registro adicionado!</td>');
                        row.push('</tr>');
                        $(['#tb_resposta tbody'].join('')).append(row.join(''));
                    }
                    $('.dsItemResposta,.addItemResposta').removeAttr('disabled', 'disabled')
                    $('.divItemRespostaDinamico').html('');
                    resetInputRespostas();
                    $('#carregarResposta').html('');
                }
            }
        });

    }

    function removerValorRespostaArray(array, valueRemove) {
        let arr = array;
        for (let i = 0; i < arr.length; i++) {
            if (arr[i].id_resposta == valueRemove) {
                arr.splice(i, 1);
                i--;
            }
        }
    }

    function addItensRespostas(e) {
        e.preventDefault()
        let lastItem = $('.item:last').attr('id');
        let nextIndex = Number(lastItem.split('_')[1]) + 1;

        $('.item:last').after('<div class="row item" id="itemResposta_' + nextIndex + '"></div>');
        $('#itemResposta_' + nextIndex).append('\r\n\
        <div class="col-md-7"><div class="form-group"><input type="text" name="item[' + nextIndex + '][ds_item_resposta]" id="ds_item_resposta_' + nextIndex + '" class="form-control dsItemResposta itemResposta itemResposta_' + nextIndex + '" placeholder="Descrição do item de resposta" /></div></div>\r\n\
        <div class="col-md-4"><div class="form-group"><input type="text" name="item[' + nextIndex + '][vl_item_resposta]" id="vl_item_resposta_' + nextIndex + '" class="form-control money vlItemResposta itemResposta " maxlength="5" placeholder = "Valor do Item de Resposta. Ex: 0,50" /></div></div>\r\n\
        <div class="col-md-1"><div class="form-group">\r\n\
        <button type="button" class="btn btn-fill btn-danger pull-right btn-marg-left itemResposta itemResposta_' + nextIndex + '" id="btnRemItemResposta"><i class="fa fa-minus"></i></button>\r\n\
        </div>\r\n\
        ').addClass('divItemRespostaDinamico');
    }

    function removeItemResposta(e) {
        e.preventDefault()
        let id = $(this).closest('div.item').attr('id');
        let deleteIndex = Number(id.split('_')[1]);
        let url = $(this).data('href');

        if (typeof url !== typeof undefined && url !== false) {
            Swal.fire({
                title: 'Tem certeza que deseja excluir?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Não',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
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
                        type: "DELETE",
                        url: url,
                        success: function (retorno) {
                            if (retorno)
                                $('#itemResposta_' + deleteIndex).remove();
                            window.location.reload();
                        }

                    });
                }
            });
        }
        $('#itemResposta_' + deleteIndex).remove();
    }

    function validFormPergunta() {
        let noPergunta = $('#no_pergunta_formulario').val();
        let txPergunta_dom = document.createElement('html');
        txPergunta_dom.innerHTML = $('#tx_pergunta_formulario').val();
        let rowCount = $("#tr_resposta_0").length
        let error = [];


        if (noPergunta == "") {
            $('#msg-error-no-pergunta').text('O Campo nome da pergunta é de preenchimento obrigatório, verifique!');
            error.push('O Campo nome da pergunta é de preenchimento obrigatório, verifique!')
        }

        if (txPergunta_dom.textContent.trim() == "") {
            $('#msg-error-tx-pergunta').text('O Campo pergunta é de preenchimento obrigatório, verifique!');
            error.push('O Campo pergunta é de preenchimento obrigatório, verifique!')
        }

        if (rowCount === 1) {
            $('#msg-error-resposta').text('É obrigatório adicionar pelo menos uma resposta. Verifique!');
            error.push('É obrigatório adicionar pelo menos uma resposta. Verifique!')
        }

        if (error.length !== 0) {
            helper.alertError('Erros no formulário, verifique!');
            return false;
        }

        return true;
    }


    function salvarPergunta(e){
        $('.msg-erro-form').html('');
        let rota = $("#rotaSalvarPergunta").val();
        let rotaPesquisarPergunta = $("#rotaPesquisarPergunta").val();

        if (!validFormPergunta()) {
            return;
        }

        arPergunta.no_pergunta_formulario = $('#no_pergunta_formulario').val();
        arPergunta.tx_pergunta_formulario = $('#tx_pergunta_formulario').val();
        arPergunta.vl_maximo_pergunta = $('#vl_maximo_pergunta').val();
        arPergunta.st_obrigatorio = $("input[name=st_obrigatorio]:checked").val();
        console.debug(arPergunta)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: rota,
            type: "POST",
            data: arPergunta,
            beforeSend: function () {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> Salvando...");
            },
            success: function (response) {
                helper.alertSuccess(response.msg);
                window.location = rotaPesquisarPergunta;
            },
            error: function (response) {
                console.info(response)
            }
        });
    }

    init();

})();
