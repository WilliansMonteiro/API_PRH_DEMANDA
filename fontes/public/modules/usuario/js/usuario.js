(function () {

    function init() {
        events();
        config();
    }

    function events() {
        $(document).on('click', '#btnUsuarioPesquisar', pesquisarUsuario);
        $(document).on('click', '#btnImportarUsuario', importarBenner);
        $(document).on('click', '#btnUsuarioExcluir', excluirUsuario);
    }

    function config() {
        $('#nr_matricula').mask('00000000');
        $('#ds_telefone').mask('(00) 00000-0000'); 

    }

    function pesquisarUsuario() {
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarUsuario").serializeArray(),
            success: function (retorno) {
                populateTableUsuario(retorno);
            },
            beforeSend: function () {
                $('#tbUsuario').DataTable().clear().destroy();
                var row = ['<tr>'];
                row.push('<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tbUsuario tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });
    }

    function populateTableUsuario(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rotaDetalhes    = $("#inputRotaDetalhes").val();
        var rotaAlterar     = $("#inputRotaAlterar").val();
        var rotaPermissao   = $("#inputRotaPermissao").val();
        var rotaExcluir     = $("#inputRotaExcluir").val();

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tbUsuario')) {
            table = $('#tbUsuario').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tbUsuario').DataTable({

            "data": retorno,
            "columns": [
                {"data": "nr_matricula"},
                {"data": "no_usuario"},
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var matriculaDetalhes   = rotaDetalhes  + '/' + row.nr_matricula;
                        var matriculaAlterar    = rotaAlterar   + '/' + row.nr_matricula;
                        var matriculaPermissao  = rotaPermissao + '/' + row.nr_matricula;
                        var matriculaExcluir    = rotaExcluir   + '/' + row.nr_matricula;


                        var visualizar  = '<a class="btn btn-small btn-primary" href="' + matriculaDetalhes + '"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Detalhes Usuário"></i></a>&nbsp;';
                        var editar      = '<a class="btn btn-small btn-info" href="' + matriculaAlterar + '"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar Usuário"></i></a>&nbsp;';
                        var permissao   = '<a class="btn btn-small btn-primary" href="' + matriculaPermissao + '"><i class="fas fa-user-plus" data-toggle="tooltip" data-placement="top" title="Gerenciar Permissões"></i></a>&nbsp;';
                        var inativar    = '<a class="btn btn-small btn-danger" href="" id="btnUsuarioExcluir" data-href="' + matriculaExcluir + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir Usuário"></i></a>';
                        var botoes = visualizar + editar + permissao + inativar;
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
            },'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': [-1] 
            }]
        });
    }


    function importarBenner() {
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarUsuario").serializeArray(),
            success: function (retorno) {
                console.info(retorno);
                if (retorno.status == true) {
                    swal.fire({
                        icon: "success",
                        text: retorno.msg
                    });
                    $('#carregar').html("");
                } else {
                    swal.fire({
                        icon: "error",
                        text: retorno.msg
                    });
                }
            },
            beforeSend: function () {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });
    }


    function excluirUsuario(e){
        e.preventDefault();
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
                }

            });
        }
    });

    }



    init();


})();
