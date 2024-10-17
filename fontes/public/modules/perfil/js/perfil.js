(function () {

    function init() {
        events();
        config();
    }

    function events() {
        $(document).on('click', '#btnPerfilPesquisar', pesquisarPerfil);
        $(document).on('click', '#btnPerfilExcluirFuncionalidade', excluirPerfil);
    }

    function config() {
        $('#nr_matricula').mask('00000000');
        $('#ds_telefone').mask('(00) 00000-0000');

    }

    function pesquisarPerfil() {
        var url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarPerfil").serializeArray(),
            success	: function(retorno) {
                console.log(retorno);
                populateTablePerfil(retorno);
            },
            beforeSend: function() {
                $('#formularioPesquisarPerfil')[0].reset();
                $("select").select2({
                    theme: "bootstrap4",
                });
                $('#tbPerfil').DataTable().clear().destroy();
                var row = ['<tr>'];
                row.push('<td align="center" colspan="6"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tbPerfil tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    }

    function populateTablePerfil(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rotaDetalhes    = $("#inputRotaVisualizar").val();
        var rotaExcluir     = $("#inputRotaExcluir").val();

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tbPerfil')) {
            table = $('#tbPerfil').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tbPerfil').DataTable({
            'columnDefs': [
                {
                    "targets": [0,1,2,3,4],
                    "className": "text-center",
                    "width": "15%"
                },
                {
                    "targets": [5],
                    "className": "text-center",
                    "width": "10%"
                }
                ],
            "data": retorno,
            "columns": [
                {"data": "sq_permissao"},
                {"data": "cd_perfil_acesso"},
                {"data": "ds_perfil_acesso"},
                {"data": "cd_modulo"},
                {"data": "ds_modulo"},
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var visualizarDetalhes   = rotaDetalhes  + '/' + row.cd_perfil_acesso;
                        var inativarPerfil       = rotaExcluir   + '/' + row.sq_permissao + '/' + row.cd_perfil_acesso;


                        var visualizar  = '<a class="btn btn-small btn-primary" href="' + visualizarDetalhes + '"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Visualizar Vínculo"></i></a>&nbsp;';
                        var inativar    = '<a class="btn btn-small btn-danger" href="" id="btnPerfilExcluir" data-href="' + inativarPerfil + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Inativar Perfil"></i></a>';
                        var botoes = visualizar + inativar;
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

    function excluirPerfil(e){
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
                type    : "GET",
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
