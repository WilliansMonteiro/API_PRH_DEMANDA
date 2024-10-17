$(document).ready(function () {
    // Função que faz a requisição para pesquisa de sistemas.
    $('#btn-pesquisar-tipos-chamados').click(function () {
        let rotaVisualizar = $("#rota-visualizar").val();
        let rotaEditar = $("#rota-editar").val();
        let rotaAtualizarStatus = $("#rota-atualizar-status").val();
        let url = $(this).data('href');
    
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'POST',
            url: url,
            data: $('#formularioPesquisarTiposChamados').serializeArray(),
            success: function (retorno) {
                if ($.fn.dataTable.isDataTable('#table-tipos-chamados')) {
                    table = $('#table-tipos-chamados').DataTable();
                    table.destroy();
                }
                $('#table-tipos-chamados').DataTable({
                    'columnDefs': [
                        {
                            "targets": [0,1,2,3,4],
                            "className": "text-center",
                            "width": "15%"
                        },
                        {
                            "targets": [4],
                            "className": "text-center",
                            "width": "20%"
                        },
                    ],
                    "order":[
                        [0, "desc"]
                    ],
                    "data": retorno,
                    "columns": [
                        {"data": "sq_tipo_chamado"},
                        {"data": "no_tipo_chamado"},
                        {"data": "ds_tipo_chamado"},
                        {
                            "render": function (data, type, row, meta) {
                                if(row.dt_inativacao){
                                    return moment(row.dt_inativacao).format('DD/MM/YYYY HH:mm:ss');
                                }
                                return "";
                            }
                        },
                        {
                            "render": function (data, type, row, meta) {
                                var visualizarChamado  = "&nbsp;<a class='btn btn-primary btn-sm' href='" + rotaVisualizar  + '/' + row.sq_tipo_chamado + "'><i class='fas fa-eye' data-toggle='tooltip' data-placement='top' title='Visualizar'></i></a>";
                                var editarChamado  = "&nbsp;<a class='btn btn-success btn-sm' href='" + rotaEditar   + '/' + row.sq_tipo_chamado + "'><i class='fa fa-pencil-alt' data-toggle='tooltip' data-placement='top' title='Editar Tipo de Chamado'></i></a>";
                                var atualizarStatus    = "&nbsp;<a class='btn-warning btn-sm' id='btn-excluir' data-href='" + rotaAtualizarStatus   + '/' + row.sq_tipo_chamado + "'><i class='fa fa-random' data-toggle='tooltip' data-placement='top' title='Alterar Status' style='color: white;'></i></a>";
                                
                                return visualizarChamado + editarChamado + atualizarStatus;
                            }
                            
                        },
                        
                    ],
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
            beforeSend: function () {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                $('#carregar').html("");
                alert_error('Desculpe! Ocorreu um erro.');
            }
        });
    });

    $(document).on('change', '#sq_sistema_contrato', function () {
        var url = $(this).attr('data-href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sq_sistema_contrato: $(this).val()
            },
            beforeSend: function () {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },
            success: function (retorno) {
                console.log(retorno);
                $('#cd_sistema_see').val("");
                $('#no_sistema_see').val("");
                $('#cd_sistema_see').html('Carregando...')
                    .find('option')
                    .remove()
                    .end();
                $.each(retorno, function (key, value) {
                    if (value === null) {
                        $('#cd_sistema_see').append('<option value="">' + "Contrato sem Sistema vinculado" + '</option>');
                    } else {
                        $('#cd_sistema_see').append('<option value="' + value.cd_sistema_see + '">' + value.no_sistema_see + '</option>');
                        document.getElementById("no_sistema_see").value = value.no_sistema_see;
                    }
                });
            }
        });
    });

    $(document).on('click', '#btn-excluir', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja alterar o status deste tipo de chamado?',
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
                    type: "POST",
                    url: url,
                    success: function (retorno) {
                        const msgRetorno = JSON.parse(retorno);

                        if (msgRetorno.error) {
                            return helper.alertError("Erro ao alterar status!");
                        }

                        $('#btn-pesquisar-tipos-chamados').trigger("click");
                        helper.alertSuccess("Status alterado com sucesso!");
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }
                });
            }
        });
    });
});
