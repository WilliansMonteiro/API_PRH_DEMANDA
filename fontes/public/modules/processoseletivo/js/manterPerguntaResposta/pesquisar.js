(function() {

    function init() {
        events();
        pesquisarPergunta();
    }

    function events() {
        $(document).on('click', '#btn-pesquisar-pergunta', pesquisarPergunta);
        $(document).on('click', '#btnStatus', handleStatus);
    }


    function pesquisarPergunta() {
        var url = $("#inputRotaPesquisar").val();
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarPergunta").serializeArray(),
            success: function (retorno) {
                console.table(retorno)
                populateTablePergunta(retorno);
            },
            beforeSend: function () {
                $('#tbPergunta').DataTable().clear().destroy();
                var row = ['<tr>'];
                row.push('<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tbPergunta tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });
    }

    function populateTablePergunta(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rotaVisualizar  = $("#inputRotaVisualizar").val();
        var rotaEditar      = $("#inputRotaEditar").val();
        var rotaStatus     = $("#inputRotaStatus").val();

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tbPergunta')) {
            table = $('#tbPergunta').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tbPergunta').DataTable({

            "data": retorno,
            "columns": [
                {"data": "no_pergunta_formulario"},
                {"data": "vl_maximo_pergunta"},
                {
                    "render": function (data, type, row, meta){
                        let stObrigatorio = 'Não';
                        if (row.st_obrigatorio == 'S'){
                            stObrigatorio = 'Sim';
                        }
                        return stObrigatorio;
                    }
                },
                {
                    "render": function (data, type, row, meta){
                        let status = 'Inativc';
                        if (row.st_registro_ativo == 'A'){
                            status = 'Ativo';
                        }
                        return status;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var sqVisualizar = rotaVisualizar  + '/' + row.sq_pergunta_formulario;
                        var sqEditar     = rotaEditar   + '/' + row.sq_pergunta_formulario;
                        var sqStatus    = rotaStatus   + '/' + row.sq_pergunta_formulario;


                        var visualizar  = '<a class="btn btn-small btn-primary" href="' + sqVisualizar + '"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Visualizar Pergunta"></i></a>&nbsp;';
                        var editar      = '<a class="btn btn-small btn-info" href="' + sqEditar + '"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar Pergunta"></i></a>&nbsp;';

                        if(row.st_registro_ativo == 'A'){
                            var status    = '<a class="btn btn-small btn-danger" href="" id="btnStatus" data-id-pergunta="' + row.sq_pergunta_formulario + '" data-status="I"><i class="fas fa-thumbs-down" data-toggle="tooltip" data-placement="top" title="Inativar Pergunta"></i></a>';
                        }else{
                            var status    = '<a class="btn btn-small btn-success" href="" id="btnStatus" data-id-pergunta="' + row.sq_pergunta_formulario + '" data-status="A"><i class="fas fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="Ativar Pergunta" style="color: white;"></i></a>';
                        }

                        var botoes = visualizar + editar + status;
                        return botoes;
                    }

                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
            "language": {
                "lengthMenu": "Mostrando _MENU_ registros por página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "Nenhum registro disponível",
                "infoFiltered": "(filtrado de _MAX_ registros no total)",
                "search": "Pesquisar",
                "paginate": {
                    "next": "Próximo",
                    "previous": "Anterior",
                    "first": "Primeiro",
                    "last": "Último"
                },
            }
        });
    }

    function handleStatus(e) {
        e.preventDefault();
        let idPergunta = $(this).data('id-pergunta');
        let rotaStatusPergunta = $('#inputRotaStatus').val();
        let status = $(this).data('status');
        let msgStatus = 'reativar';
        if (status == 'I') {
            msgStatus = 'inativar';
        }
        let msgTitle = 'Deseja ' + msgStatus + ' a pergunta?'

        let row = [];

        Swal.fire({
            title: msgTitle,
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
                    url: rotaStatusPergunta,
                    type: "POST",
                    data: {'sq_pergunta_formulario': idPergunta, 'st_registro_ativo' : status},
                    success: function (response) {
                        if (response.status) {
                            helper.alertSuccess(response.msg)
                            pesquisarPergunta()
                        } else {
                            helper.alertError(response.msg)
                        }
                    }
                });
            }
        });

    }

    init();
})();
