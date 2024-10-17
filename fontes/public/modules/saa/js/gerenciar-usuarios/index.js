(function () {
    function init() {
        config();
        events();
        document.getElementById("btnUsuariosPesquisar").click()
    }

    function events() {
        $(document).on('click', '#btnUsuariosPesquisar', pesquisar_usuarios);
    }

    function config() {
        $("#input_matricula").mask("00000000");
    }

      function pesquisar_usuarios(){
        $('#tb-usuarios').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioPesquisarUsuarios").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTableUsuarios(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb-usuarios tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });

      }

      function populateTableUsuarios(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar_usuario = $("#input-rota-visualizar-usuario").val();
        var rota_editar_usuario     = $("#input-rota-editar-usuario").val();
        var rota_ativar_usuario     = $("#input-rota-ativar-usuario").val();
        var rota_inativar_usuario   = $("#input-rota-inativar-usuario").val();
        var empresa                 = "Sem empresa";
        var prestadora              = "Sem prestadora";

        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-usuarios')) {
            table = $('#tb-usuarios').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tb-usuarios').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        if(row.nm_empresa === null || row.nm_empresa === "")
                        {
                            return empresa
                        }else{
                            return row.nm_empresa
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.pstnome === null || row.pstnome === "")
                        {
                            return prestadora
                        }else{
                            return row.pstnome
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.fdematric + '-' + row.fdedgv
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.fdenome
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.sg_dependencia
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.fdesituacaonome
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var sqVisualizar   = rota_visualizar_usuario  + '/' + row.fdematric;
                        var sqEditar       = rota_editar_usuario + '/' + row.fdematric;
                        var sqAtivar       = rota_ativar_usuario   + '/' + row.fdematric;
                        var sqInativar     = rota_inativar_usuario + '/' + row.fdematric;

                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Visualizar usuário"></i></a>&nbsp;';
                        var editar  = '<a class="btn btn-success btn-sm" href="' + sqEditar + '"><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar usuário"></i></a>&nbsp;';

                        if(row.fdesituacao === 0 || row.fdesituacao == 0){
                            var status   = '<a class="btn btn-success btn-sm" id="pesquisarAtivar" data-href="' + sqAtivar + '"><i class="fas fa-user" data-toggle="tooltip" data-placement="top" title="Ativar usuário"></i></a>&nbsp;';
                        }else{
                            var status   = '<a class="btn btn-danger btn-sm" id="pesquisarInativar" data-href="' + sqInativar + '"><i class="fas fa-user" data-toggle="tooltip" data-placement="top" title="Inativar usuário"></i></a>&nbsp;';
                        }



                        var botoes = visualizar + editar +  status;
                        return botoes;
                    }

                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
            order:[[3,'asc']],
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


        $(document).on('click', '#pesquisarAtivar', function(e){
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
                  console.log(retorno);
                  var matricula = retorno.data.fdematric;
                  var nome = retorno.data.fdenome;
                  console.log(matricula, nome);
                  $('#ativarNome').html(nome);
                  $('#ativarMatricula').html(matricula + '-' +  retorno.data.fdedgv);
                  $('#exampleModal').modal('show');
                  $('#inputmatric').val(matricula);


                }

            });
        });

        $(document).on('click', '#pesquisarInativar', function(e){
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
                  console.log(retorno);
                  var matricula = retorno.data.fdematric;
                  var nome = retorno.data.fdenome;
                  console.log(matricula, nome);
                  $('#InativarNome').html(nome);
                  $('#InativarMatricula').html(matricula + '-' + retorno.data.fdedgv);
                  $('#modalInativarModal').modal('show');
                  $('#inputmatricInativar').val(matricula);

                }

            });
        });
        $('#cd_empresa').select2({
            theme: 'bootstrap4'
          });
        $('#st_situacao').select2({
            theme: 'bootstrap4'
          });
        $('#cd_area').select2({
            theme: 'bootstrap4'
          });
        $('#btnLimpar').click(function(){

            var url = $(this).data('href');
            $.ajax({
                type: "POST",
                url: url,
                data: $("#formularioPesquisarUsuarios").serializeArray(),
                success: function (retorno) {
                    $('#cd_empresa_prestadora_combo option:first').prop('selected', true);
                    $('#st_situacao_combo option:first').prop('selected', true);
                    $('#cd_area_combo option:first').prop('selected', true);
                    $('#cd_tipo_empregado_combo option:first').prop('selected', true);
                    $('#cd_area_combo option:first').prop('selected', true);
                    $('#input_cpf').val('');
                    $('#input_nome').val('');
                    $('#input_matricula').val('');
                    $('#cd_empresa_combo option:first').prop('selected', true);
                    $("select").trigger("change.select2");
                },
                beforeSend: function () {
                    $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                },
                complete: function () {
                    $('#carregar').html("");
                },

            });


        });


        init();
})();
