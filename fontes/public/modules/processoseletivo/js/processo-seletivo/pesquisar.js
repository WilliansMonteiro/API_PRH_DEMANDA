(function () {

    // Função que faz a requisição para pesquisa de processos seletivos.
    $('#btn-pesquisar-processo-seletivo').click(function() {
        let url = $(this).data('href');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type   : 'POST',
            url    :  url,
            data   :  $('#formularioPesquisarProcessoSeletivo').serializeArray(),
            success: function(retorno) {
                $('#retorno').html(retorno);
                $('#table-processo-seletivo').DataTable({
                    language: {
                        lengthMenu: "Mostrando _MENU_ registros por página",
                        zeroRecords: "Nada encontrado",
                        info: "Mostrando página _PAGE_ de _PAGES_",
                        infoEmpty: "Nenhum registro disponível",
                        infoFiltered: "(filtrado de _MAX_ registros no total)",
                        search: "Pesquisar",
                        paginate: {
                            next: "Próximo",
                            previous: "Anterior",
                            first: "Primeiro",
                            last: "Último",
                        },
                    },
                });
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function() {
                $('#carregar').html("");
            },
            error   : function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error('Desculpe! Ocorreu um erro.');
            }
        });
    });

    $(document).on('click', '#btn-inativar-processo-seletivo', function(e) {
        let url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type   : 'POST',
            url    : url,
            success: function(retorno) {
                $.each(retorno.data, function(key, value) {
                    var numero_processo_seletivo = value.dn_processo_seletivo;
                    var nome_processo_seletivo = value.no_processo_seletivo;
                    var sq_processo_seletivo = value.sq_processo_seletivo;

                    $('#inativarNumero').html(numero_processo_seletivo);
                    $('#inativarNome').html(nome_processo_seletivo);
                    $('#modal-inativar-processo-seletivo').modal('show');
                    $('#inputInativar').val(sq_processo_seletivo);
                });
            }
        });
    });

    $(document).on('click', '#btn-ativar-processo-seletivo', function(e) {
        let url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type    : 'POST',
            url     : url,
            success : function(retorno) {
                $.each(retorno.data, function(key, value) {
                    var numero_processo_seletivo = value.dn_processo_seletivo;
                    var nome_processo_seletivo = value.no_processo_seletivo;
                    var sq_processo_seletivo = value.sq_processo_seletivo;

                    $('#ativarNumero').html(numero_processo_seletivo);
                    $('#ativarNome').html(nome_processo_seletivo);
                    $('#modal-ativar-processo-seletivo').modal('show');
                    $('#inputAtivar').val(sq_processo_seletivo);
                })
            }
        })
    });

    $(document).on('click', '#btn-excluir', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
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
                    type    : "DELETE",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }

                });
            }
        });
    });

    $(document).on('click', '#btn-enviar', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Deseja enviar para aprovação?',
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
                    success : function(retorno) {
                        if (retorno) {
                            window.location.reload();
                        }
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown, retorno) {
                        console.log('Erro: ', errorThrown, retorno);
                    }

                });
            }
        });
    });

    $(document).on('click', '#btn-aprovar', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Deseja aprovar o processo seletivo?',
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
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }

                });
            }
        });
    });

    $(document).on('click', '#btn-publicar', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Deseja realmente publicar o processo seletivo?',
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
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }

                });
            }
        });
    });

    $(document).on('click', '#btn-rejeitar', function(e) {
        let url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type    : 'POST',
            url     : url,
            success : function(retorno) {
                $.each(retorno.data, function(key, value) {
                    var numero_processo_seletivo = value.dn_processo_seletivo;
                    var nome_processo_seletivo = value.no_processo_seletivo;
                    var sq_processo_seletivo = value.sq_processo_seletivo;

                    $('#rejeitarNumero').html(numero_processo_seletivo);
                    $('#rejeitarNome').html(nome_processo_seletivo);
                    $('#modal-rejeitar-processo-seletivo').modal('show');
                    $('#inputRejeitar').val(sq_processo_seletivo);
                })
            }
        })
    });

    $(document).on('click', '#btn-retificar', function(e) {
        let url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type    : 'POST',
            url     : url,
            success : function(retorno) {
                $.each(retorno.data, function(key, value) {
                    var numero_processo_seletivo = value.dn_processo_seletivo;
                    var nome_processo_seletivo = value.no_processo_seletivo;
                    var sq_processo_seletivo = value.sq_processo_seletivo;

                    $('#retificarNumero').html(numero_processo_seletivo);
                    $('#retificarNome').html(nome_processo_seletivo);
                    $('#modal-retificar-processo-seletivo').modal('show');
                    $('#inputRetificar').val(sq_processo_seletivo);
                })
            }
        })
    });

    $(document).on('click', '#btn-revogar', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Deseja realmente revogar o processo seletivo?',
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
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }

                });
            }
        });
    });

    $(document).on('click', '#btn-limpar', function (e) {
        $("#cd_area_solicitante").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("#st_registro_ativo").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("#cd_status_processo_seletivo").find("option").prop("selected", function () {
            return this.defaultSelected;
        });

        $("select").trigger("change.select2");
    });
})();
