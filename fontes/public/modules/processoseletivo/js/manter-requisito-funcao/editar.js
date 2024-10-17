(function () {
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

    var inputs_requisito = {
        sq_requisito: "",
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
    }

    function init() {
        events();
        loadQualificacoes();
        loadCertificacoes();
        loadExercicios();
    }

    function events() {
        $(document).on('click', '#btn_add_qualificacao', addQualificacao);
        $(document).on('click', '#btn_excluir_qualificacao', excluirQualificacao);
        $(document).on('click', '#btn_add_certificacao', addCertificacao);
        $(document).on('click', '#btn_excluir_certificacao', excluirCertificacao);
        $(document).on('click', '#btn_add_exercicio', addExercicio);
        $(document).on('click', '#btn_excluir_exercicio', excluirExercicio);
        $(document).on('click', '#btn_atualizar_requisito', salvarRequisito);
    }

    function loadQualificacoes() {
        let rotaCarregaQualificacoes = $("#rota_carrega_qualificacoes").val();
        let sq_requisito_funcao_pccr = $("#sq_requisito_funcao_pccr").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: rotaCarregaQualificacoes,
            type: "GET",
            data: {'sq_requisito_funcao_pccr': sq_requisito_funcao_pccr},
            success: function (response) {
                // console.table(response);
                populateTableQualificacoes(response);
            },
            beforeSend: function () {
                $("#tbody_qualificacao").empty();
                let row = ['<tr>'];
                row.push('<td align="center" colspan="3"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb_qualificacoes tbody'].join('')).append(row.join(''));
            },
            error: function (response) {
                console.info(response)
            }
        });
    }

    function populateTableQualificacoes(arrayQualificacoes) {
        $("#tbody_qualificacao").empty();
        $('#cd_qualificacao').val(null).trigger('change');
        $('#vl_qualificacao').val('');

        if (arrayQualificacoes.length != 0) {
            $.each(arrayQualificacoes, function (i, value) {
                let row = [];
                row.push('<tr id="tr_qualificacao_' + value.sq_req_func_qualific + '">');
                row.push('<td>' + value.no_tipo_qualificacao + '</td>');
                row.push('<td>' + value.vl_pontuacao_qualificacao + '</td>');
                row.push('<td class="text-center"><a class="btn btn-small btn-danger" href="" id="btn_excluir_qualificacao" data-qualificacao="' + value.sq_req_func_qualific + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir qualificação"></i></a></td>');
                row.push('</tr>');
                $(['#tb_qualificacoes tbody'].join('')).append(row.join(''));
            });
            // row.length = 0;
        } else {
            let row = [];
            row.push('<tr id="tr_qualificacao_0">');
            row.push('<td class="text-center" colspan="3">Nenhum registro encontrado</td>');
            row.push('</tr>');
            $(['#tb_qualificacoes tbody'].join('')).append(row.join(''));
        }
    }

    function addQualificacao(e) {
        let rota = $('#rota_salva_qualificacao').val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let qualificacao = $('#cd_qualificacao').val();
        let pontuacao = $('#vl_qualificacao').val();
        let requisito = $('#sq_requisito_funcao_pccr').val();

        if (qualificacao === '') {
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
                'sq_requisito_funcao_pccr': requisito,
                'cd_tipo_qualificacao': qualificacao,
                'vl_pontuacao_qualificacao': pontuacao
            },
            dataType: 'json',
            success: function (retorno) {
                console.log(retorno);
                if (retorno.status)
                    Swal.fire({
                        title: 'Qualificação adicionada com sucesso',
                        text: '',
                        icon: 'success',
                        showCancelButton: false
                    });

                if (retorno.status == false && retorno.ja_existe) {
                    Swal.fire({
                        title: 'Qualificação já associada ao requisito.',
                        text: '',
                        icon: 'error',
                        showCancelButton: false
                    });
                }
                loadQualificacoes();
            }, beforeSend: function (){
                $('#carregarQualificacao').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adicionando...');
            }, complete: function(){
                $('#carregarQualificacao').html('');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown, retorno) {
                console.log(retorno);
                Swal.fire({
                    title: 'Erro ao tentar adicionar qualificação.',
                    text: '',
                    icon: 'error',
                    showCancelButton: false
                });
            }
        });
        e.preventDefault();
        return false;
    }

    function excluirQualificacao(e) {
        e.preventDefault();
        let qualificacao = $(this).attr('data-qualificacao');
        let rotaExcluirQualificacao = $('#rota_exclui_qualificacao').val();
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: rotaExcluirQualificacao,
                    type: "POST",
                    data: {'sq_req_func_qualific': qualificacao},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Qualificação excluída com sucesso.',
                                text: '',
                                icon: 'success',
                                showCancelButton: false
                            });
                            loadQualificacoes()
                        } else {
                            Swal.fire({
                                title: 'Erro ao tentar excluir qualificação.',
                                text: '',
                                icon: 'error',
                                showCancelButton: false
                            });
                        }
                    }, beforeSend: function (){
                        $('#carregarQualificacao').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
                    }, complete: function(){
                        $('#carregarQualificacao').html('');
                    }
                });
            }
        });

    }

    function loadCertificacoes() {
        let rotaCarregaCertificacoes = $("#rota_carrega_certificacoes").val();
        let sq_requisito_funcao_pccr = $("#sq_requisito_funcao_pccr").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: rotaCarregaCertificacoes,
            type: "GET",
            data: {'sq_requisito_funcao_pccr': sq_requisito_funcao_pccr},
            success: function (response) {
                // console.table(response);
                populateTableCertificacoes(response);
            },
            beforeSend: function () {
                $("#tbody_certificacao").empty();
                let row = ['<tr>'];
                row.push('<td align="center" colspan="3"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb_certificacoes tbody'].join('')).append(row.join(''));
            },
            error: function (response) {
                console.info(response)
            }
        });
    }

    function populateTableCertificacoes(arrayCertificacoes) {
        $("#tbody_certificacao").empty();
        $('#cd_certificacao').val(null).trigger('change');
        $('#vl_certificacao').val('');

        if (arrayCertificacoes.length != 0) {
            $.each(arrayCertificacoes, function (i, value) {
                let row = [];
                row.push('<tr id="tr_certificacao_' + value.sq_req_func_qualific + '">');
                row.push('<td>' + value.no_tipo_qualificacao + '</td>');
                row.push('<td>' + value.vl_pontuacao_qualificacao + '</td>');
                row.push('<td class="text-center"><a class="btn btn-small btn-danger" href="" id="btn_excluir_certificacao" data-certificacao="' + value.sq_req_func_qualific + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir certificação"></i></a></td>');
                row.push('</tr>');
                $(['#tb_certificacoes tbody'].join('')).append(row.join(''));
            });
            // row.length = 0;
        } else {
            let row = [];
            row.push('<tr id="tr_certificacao_0">');
            row.push('<td class="text-center" colspan="3">Nenhum registro encontrado</td>');
            row.push('</tr>');
            $(['#tb_certificacoes tbody'].join('')).append(row.join(''));
        }
    }

    function addCertificacao(e) {
        let rota = $('#rota_salva_certificacao').val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let certificacao = $('#cd_certificacao').val();
        let pontuacao = $('#vl_certificacao').val();
        let requisito = $('#sq_requisito_funcao_pccr').val();

        if (certificacao === '') {
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
                'sq_requisito_funcao_pccr': requisito,
                'cd_tipo_qualificacao': certificacao,
                'vl_pontuacao_qualificacao': pontuacao
            },
            dataType: 'json',
            success: function (retorno) {
                console.log(retorno);
                if (retorno.status)
                    Swal.fire({
                        title: 'Certificação adicionada com sucesso',
                        text: '',
                        icon: 'success',
                        showCancelButton: false
                    });

                if (retorno.status == false && retorno.ja_existe) {
                    Swal.fire({
                        title: 'Certificação já associada ao requisito.',
                        text: '',
                        icon: 'error',
                        showCancelButton: false
                    });
                }
                loadCertificacoes();
            }, beforeSend: function (){
                $('#carregarCertificacao').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adicionando...');
            }, complete: function(){
                $('#carregarCertificacao').html('');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown, retorno) {
                console.log(retorno);
                Swal.fire({
                    title: 'Erro ao tentar adicionar certificação.',
                    text: '',
                    icon: 'error',
                    showCancelButton: false
                });
            }
        });
        e.preventDefault();
        return false;
    }

    function excluirCertificacao(e) {
        e.preventDefault();
        let certificacao = $(this).attr('data-certificacao');
        let rotaExcluirCertificacao = $('#rota_exclui_certificacao').val();
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: rotaExcluirCertificacao,
                    type: "POST",
                    data: {'sq_req_func_qualific': certificacao},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Certificação excluída com sucesso.',
                                text: '',
                                icon: 'success',
                                showCancelButton: false
                            });
                            loadCertificacoes()
                        } else {
                            Swal.fire({
                                title: 'Erro ao tentar excluir certificação.',
                                text: '',
                                icon: 'error',
                                showCancelButton: false
                            });
                        }
                    }, beforeSend: function (){
                        $('#carregarCertificacao').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
                    }, complete: function(){
                        $('#carregarCertificacao').html('');
                    }
                });
            }
        });

    }

    function loadExercicios() {
        let rotaCarregaExercicioFuncoesGratificadas = $("#rota_carrega_exercicios").val();
        let sq_requisito_funcao_pccr = $("#sq_requisito_funcao_pccr").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: rotaCarregaExercicioFuncoesGratificadas,
            type: "GET",
            data: {'sq_requisito_funcao_pccr': sq_requisito_funcao_pccr},
            success: function (response) {
                console.table(response);
                populateTableExercicios(response);
            },
            beforeSend: function () {
                $("#tbody_exercicio_funcao_gratificada").empty();
                let row = ['<tr>'];
                row.push('<td align="center" colspan="3"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb_funcoes_gratificadas tbody'].join('')).append(row.join(''));
            },
            error: function (response) {
                console.info(response)
            }
        });
    }

    function populateTableExercicios(arrayExercicios) {
        $("#tbody_exercicio_funcao_gratificada").empty();
        $('#sq_exercicio_funcao_gratificad').val(null).trigger('change');
        $('#vl_funcao_gratificada').val('');

        if (arrayExercicios.length != 0) {
            $.each(arrayExercicios, function (i, value) {
                let row = [];
                row.push('<tr id="tr_exercicio_' + value.sq_req_func_gratific + '">');
                row.push('<td>' + value.qt_periodo_exercicio + ' meses: ' + value.ds_exercicio + '</td>');
                row.push('<td>' + value.vl_pontuacao_func_gratificad + '</td>');
                row.push('<td class="text-center"><a class="btn btn-small btn-danger" href="" id="btn_excluir_exercicio" data-exercicio="' + value.sq_req_func_gratific + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir exercício"></i></a></td>');
                row.push('</tr>');
                $(['#tb_funcoes_gratificadas tbody'].join('')).append(row.join(''));
            });
            // row.length = 0;
        } else {
            let row = [];
            row.push('<tr id="tr_exercicio_0">');
            row.push('<td class="text-center" colspan="3">Nenhum registro encontrado</td>');
            row.push('</tr>');
            $(['#tb_funcoes_gratificadas tbody'].join('')).append(row.join(''));
        }
    }

    function addExercicio(e) {
        let rota = $('#rota_salva_exercicio').val();
        let _token = $('meta[name="csrf-token"]').attr('content');
        let exercicio = $('#sq_exercicio_funcao_gratificad').val();
        let pontuacao = $('#vl_funcao_gratificada').val();
        let requisito = $('#sq_requisito_funcao_pccr').val();

        if (exercicio === '') {
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
                'sq_requisito_funcao_pccr': requisito,
                'sq_exercicio_funcao_gratificad': exercicio,
                'vl_pontuacao_func_gratificad': pontuacao
            },
            dataType: 'json',
            success: function (retorno) {
                console.log(retorno);
                if (retorno.status)
                    Swal.fire({
                        title: 'Exercício em função gratificada adicionado com sucesso',
                        text: '',
                        icon: 'success',
                        showCancelButton: false
                    });

                if (retorno.status == false && retorno.ja_existe) {
                    Swal.fire({
                        title: 'Exercício em função gratificada já associado ao requisito.',
                        text: '',
                        icon: 'error',
                        showCancelButton: false
                    });
                }
                loadExercicios();
            },
            beforeSend: function (){
                $('#carregarExercicio').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adicionando...');
            }, complete: function(){
                $('#carregarExercicio').html('');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown, retorno) {
                console.log(retorno);
                Swal.fire({
                    title: 'Erro ao tentar adicionar exercício em função gratificada.',
                    text: '',
                    icon: 'error',
                    showCancelButton: false
                });
            }
        });
        e.preventDefault();
        return false;
    }

    function excluirExercicio(e) {
        e.preventDefault();
        let exercicio = $(this).attr('data-exercicio');
        let rotaExcluirExercicio = $('#rota_exclui_exercicio').val();
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: rotaExcluirExercicio,
                    type: "POST",
                    data: {'sq_req_func_gratific': exercicio},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: 'Exercício em função gratificada excluído com sucesso.',
                                text: '',
                                icon: 'success',
                                showCancelButton: false
                            });
                            loadExercicios();
                        } else {
                            Swal.fire({
                                title: 'Erro ao tentar excluir exercício em função gratificada.',
                                text: '',
                                icon: 'error',
                                showCancelButton: false
                            });
                        }
                    }, beforeSend: function (){
                        $('#carregarExercicio').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
                    }, complete: function(){
                        $('#carregarExercicio').html('');
                    }
                });
            }
        });

    }

    function validacoes() {
        if (inputs_requisito.nome === '') {
            Swal.fire({
                title: 'É obrigatório informar o nome do requisito!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.pccr === '') {
            Swal.fire({
                title: 'É obrigatório informar o PCCR do requisito!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.trilha === '') {
            Swal.fire({
                title: 'É obrigatório informar a trilha do requisito!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.funcao === '') {
            Swal.fire({
                title: 'É obrigatório informar a função do requisito!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.exercicio === '') {
            Swal.fire({
                title: 'É obrigatório informar a necessidade de exercício na função ou funções superiores!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.experiencia_ext === '') {
            Swal.fire({
                title: 'É obrigatório informar a experiência externa exigida pelo requisito!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.pontuacao_exp_ext === '') {
            Swal.fire({
                title: 'É obrigatório informar a pontuação da experiência externa!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.experiencia_brb === '') {
            Swal.fire({
                title: 'É obrigatório informar a experiência no BRB exigida pelo requisito!',
                text: '',
                icon: 'warning'
            })
            return;
        }

        if (inputs_requisito.pontuacao_exp_brb === '') {
            Swal.fire({
                title: 'É obrigatório informar a pontuação da experiência no BRB!',
                text: '',
                icon: 'warning'
            })
            return;
        }
    }

    function salvarRequisito(e) {
        e.preventDefault();
        let rota = $('#rota_atualiza_requisito').val();
        let _token = $('meta[name="csrf-token"]').attr('content');

        $('.msg-erro-form').html('');
        let cod_requisito = $("#sq_requisito_funcao_pccr").val();
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

        inputs_requisito.sq_requisito      = cod_requisito;
        inputs_requisito.nome              = no_requisito;
        inputs_requisito.pccr              = pccr_requisito;
        inputs_requisito.trilha            = trilha_requisito;
        inputs_requisito.funcao            = funcao_requisito;
        inputs_requisito.escolaridade      = escolaridade_requisito;
        inputs_requisito.exercicio         = exercicio_requisito;
        inputs_requisito.experiencia_ext   = exp_externa;
        inputs_requisito.pontuacao_exp_ext = pontuacao_ext;
        inputs_requisito.experiencia_brb   = exp_brb;
        inputs_requisito.pontuacao_exp_brb = pontuacao_brb;
        validacoes();

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
                    'requisito': inputs_requisito
                },
                dataType: 'json',
                success: function (retorno) {
                    if (retorno)
                        Swal.fire({
                            title: 'Requisito atualizado com sucesso.',
                            text: '',
                            icon: 'success',
                            showCancelButton: false
                        });
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


        return false;
    }

    function redirecionarIndexRequisito() {
        let rota = $('#rota_index_requisto').val();
        window.location = rota;
    }

    init();
})();
