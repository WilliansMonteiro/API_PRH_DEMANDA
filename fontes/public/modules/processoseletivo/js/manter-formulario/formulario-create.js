    // Array com os campos do formulário
    let inputs_formulario = {
        sq_formulario: null,
        no_formulario: null,
        ds_formulario: null,
        ds_finalidade: null,
        perguntas: [],
        cientes: []
    };

    function redirecionarIndexFormulario() {
        // $("#MyWindowModalSpinner").modal("show");
        let rotaPosSalvamento = $('#rota_manter_formulario').val();
        window.location = rotaPosSalvamento;
    }

    function resetArrayPerguntas() {
        return inputs_formulario.perguntas = [];
    }

    function resetArrayCientes() {
        return inputs_formulario.cientes = [];
    }

    function addCiente(e) {

        e.preventDefault();
        let row = [];
        let data_ciente = $('#sq_ciente').select2('data');
        let cod_ciente  = data_ciente[0].id;
        let nome_ciente = data_ciente[0].text;
        let ordem_ciente = $('#nr_ordem_ciente').val();
        let no_ciente_obrigatorio = $('#st_obrigatorio :selected').text();
        let st_ciente_obrigatorio = $('#st_obrigatorio :selected').val();

        if (cod_ciente === '') {
            Swal.fire({
                title: 'Favor selecionar um ciente para adicionar!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        console.log(inputs_formulario.cientes);
        if (inputs_formulario.cientes.length === 0) {
            $("#tb_cientes tbody>tr").remove();
        }

        if (!encontrarValorCiente(cod_ciente, inputs_formulario.cientes)) {
            inputs_formulario.cientes.push({
                sq_ciente: cod_ciente,
                no_ciente: nome_ciente,
                nr_ordem: ordem_ciente,
                st_obrigatorio: st_ciente_obrigatorio
            });

            if (!$("#" + cod_ciente).length) {
                row.push('<tr id="tr_ciente_' + cod_ciente + '">');
                row.push('<td>' + nome_ciente + '</td>');
                row.push('<td>' + no_ciente_obrigatorio + '</td>');
                row.push('<td>' + ordem_ciente + '</td>');
                row.push('<td class="text-center"><i id="btn-remove-ciente" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover Ciente" onclick="removerCiente(event, '+cod_ciente+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="cientes[]" value="' + cod_ciente + '"></i></td>');
                row.push('</tr>');
                $(['#tb_cientes tbody'].join('')).append(row.join(''));
            }
            row.length = 0;
            $("#sq_ciente").select2("val", "");


        } else {
            Swal.fire({
                title: 'O ciente ' + nome_ciente + ' já foi adicionado, favor verificar!',
                text: '',
                icon: 'warning'
            })
            return;
        }
        // Limpa select de cientes após opção ser adicionada
        $("#sq_ciente").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("select").trigger("change.select2");
    }

    function addPergunta(e) {

        e.preventDefault();
        let linha = [];
        let data_pergunta = $('#sq_pergunta_formulario').select2('data');
        let cod_pergunta  = data_pergunta[0].id;
        let nome_pergunta = data_pergunta[0].text;
        let ordem_pergunta = $('#nr_ordem_pergunta').val();
        // let no_pergunta_obrigatorio = $('#st_obrigatorio :selected').text();
        // let st_pergunta_obrigatorio = $('#st_obrigatorio :selected').val();

        if (cod_pergunta === '') {
            Swal.fire({
                title: 'Favor selecionar uma pergunta para adicionar!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        console.log(inputs_formulario.perguntas);
        if (inputs_formulario.perguntas.length === 0) {
            $("#tb_perguntas tbody>tr").remove();
        }

        if (!encontrarValorPergunta(cod_pergunta, inputs_formulario.perguntas)) {
            inputs_formulario.perguntas.push({
                sq_pergunta_formulario: cod_pergunta,
                no_pergunta: nome_pergunta,
                nr_ordem: ordem_pergunta
            });

            if (!$("#" + cod_pergunta).length) {
                linha.push('<tr id="tr_pergunta_' + cod_pergunta + '">');
                linha.push('<td>' + nome_pergunta + '</td>');
                linha.push('<td>' + ordem_pergunta + '</td>');
                linha.push('<td class="text-center"><i id="btn-remove-pergunta" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover pergunta" onclick="removerPergunta(event, '+cod_pergunta+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="perguntas[]" value="' + cod_pergunta + '"></i></td>');
                linha.push('</tr>');
                $(['#tb_perguntas tbody'].join('')).append(linha.join(''));
            }
            linha.length = 0;
            $("#sq_pergunta_formulario").select2("val", "");


        } else {
            Swal.fire({
                title: 'A pergunta ' + nome_pergunta + ' já foi adicionada, favor verificar!',
                text: '',
                icon: 'warning'
            })
            return;
        }
        // Limpa select de cientes após opção ser adicionada
        $("#sq_pergunta_formulario").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("select").trigger("change.select2");
    }

    function isEmpty(obj) {
        for (let prop in obj) {
            if (obj.hasOwnProperty(prop))
                return false;
        }
        return true;
    }

    function encontrarValorCiente(codCiente, array) {
        let valorEncontrado = false;

        if (isEmpty(array)) {
            return false;
        }

        if (typeof array == 'object' && array.length >= 1) {
            $.each(array, function (key, value) {
                if (value.sq_ciente.indexOf(codCiente) >= 0) {
                    valorEncontrado = true;
                }
            });
        }
        return valorEncontrado;
    }

    function encontrarValorPergunta(codPergunta, array) {
        let valorEncontrado = false;

        if (isEmpty(array)) {
            return false;
        }

        if (typeof array == 'object' && array.length >= 1) {
            $.each(array, function (key, value) {
                if (value.sq_pergunta_formulario.indexOf(codPergunta) >= 0) {
                    valorEncontrado = true;
                }
            });
        }
        return valorEncontrado;
    }

    function removerCiente(e, cod_ciente) {
        e.preventDefault();
        let row = [];
        Swal.fire({
            title: 'Tem certeza que deseja excluir este ciente?',
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
                removerCienteArray(inputs_formulario.cientes, cod_ciente);
                $("#tr_ciente_" + cod_ciente).remove();
                if (inputs_formulario.cientes.length === 0) {
                    if ($("tr_ciente_0").length === 0) {
                        row.push('<tr id="tr_ciente_0">');
                        row.push('<td class="text-center"  colspan="4">Nenhum registro adicionado!</td>');
                        row.push('</tr>');
                        $(['#tb_cientes tbody'].join('')).append(row.join(''));
                    }
                }
                $("#sq_ciente").select2("val", "");
            }
        });
    }

    function removerPergunta(e, cod_pergunta) {
        e.preventDefault();
        let linha = [];
        Swal.fire({
            title: 'Tem certeza que deseja excluir esta pergunta?',
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
                removerPerguntaArray(inputs_formulario.perguntas, cod_pergunta);
                $("#tr_pergunta_" + cod_pergunta).remove();
                if (inputs_formulario.perguntas.length === 0) {
                    if ($("tr_perguntas_0").length === 0) {
                        linha.push('<tr id="tr_perguntas_0">');
                        linha.push('<td class="text-center"  colspan="3">Nenhum registro adicionado!</td>');
                        linha.push('</tr>');
                        $(['#tb_perguntas tbody'].join('')).append(linha.join(''));
                    }
                }
                $("#sq_pergunta_formulario").select2("val", "");
            }
        });
    }

    function removerCienteArray(array, valueRemove) {
        let arr = array;
        for (let i = 0; i < arr.length; i++) {
            if (arr[i].sq_ciente == valueRemove) {
                arr.splice(i, 1);
                i--;
            }
        }
    }

    function removerPerguntaArray(array, valueRemove) {
        let arr = array;
        for (let i = 0; i < arr.length; i++) {
            if (arr[i].sq_pergunta_formulario == valueRemove) {
                arr.splice(i, 1);
                i--;
            }
        }
    }

    function salvarFormulario(e, form) {
        let rota = $('#rota_salva_formulario').val();
        let _token = $('meta[name="csrf-token"]').attr('content');

        $('.msg-erro-form').html('');
        if (inputs_formulario.cientes.length === 0) {
            Swal.fire({
                title: 'É obrigatório adicionar pelo menos um ciente. Verifique!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_formulario.perguntas.length === 0) {
            Swal.fire({
                title: 'É obrigatório adicionar pelo menos uma pergunta. Verifique!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if ($("#" + form).valid()) {

            inputs_formulario.sq_formulario = $('#sq_formulario').val();
            inputs_formulario.no_formulario = $('#no_formulario').val();
            inputs_formulario.ds_formulario = $('#ds_formulario').val();
            inputs_formulario.ds_finalidade = $('#ds_finalidade').val();

            // console.log(inputs_formulario);
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
                    'inputs_formulario': inputs_formulario
                },
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        console.log('Response: ' + response + " - Response Status: " + response.status);
                        redirecionarIndexFormulario();
                        resetArrayCientes();
                        resetArrayPerguntas();
                    } else {
                        if (response.status == false) {
                            console.log('Response: ' + response + " - Response Status: " + response.status + " Message: " + response.message + " Exception: " + response.e);
                            redirecionarIndexFormulario();
                            // alert('Erros no formulário, verifique!');
                            // $('#msg-error-nr-ordem-cientes').text(response.error.nr_ordem_ciente);
                            // $('#msg-error-no-formulario').text(response.error.no_formulario);
                            // $('#msg-error-cientes').text(response.error.cientes);
                        } else {
                            console.log('Response: ' + response);
                            return;
                        }
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    }
// });
