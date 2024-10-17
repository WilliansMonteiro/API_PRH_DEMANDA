(function () {
    function init() {
        events();
        document.getElementById("btn-equipamento").click()
    }

    function events() {
        $(document).on('click', '#btn-equipamento', pesquisar_equipamento);
        // $(document).on('click', '#btnStatusCargo', statusCargo);

    }

    function pesquisar_equipamento()
    {
        $('#tb-equipamento').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario-pesquisar-equipamento").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTableEquipamento(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb-equipamento tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTableEquipamento(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar  = $("#input-rota-visualizar").val();
        var rota_editar      = $("#input-rota-editar").val();
        var rotaStatus       = $("#rota-busca-dados-ativar-inativar").val();
        // var rota_excluir      = $("#input-rota-editar").val();


        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-equipamento')) {
            table = $('#tb-equipamento').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tb-equipamento').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.equcod
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.teqdes
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.ds_marca_equipamento
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.ds_modelo_equipamento
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.st_registro_ativo === "S")
                        {
                            return "Registro ativo"
                        }else{
                            return "Registro inativo"
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var sqVisualizar   = rota_visualizar  + '/' + row.sq_equipamento;
                        var sqEditar   = rota_editar + '/' + row.sq_equipamento;
                        var buscar_situacao = rotaStatus + '/' + row.sq_equipamento

                        // var sq_    = rota_excluir_solicitacao   + '/' + row.sq_solic_cadastramento;

                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Visualizar equipamento"></i></a>&nbsp;';
                        var editar  = '<a class="btn btn-success btn-sm" href="' + sqEditar + '"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar equipamento"></i></a>&nbsp;';


                        if(row.st_registro_ativo === 'S' || row.st_registro_ativo === null){
                            var status    = '<a class="btn btn-sm btn-danger" id="inativar" data-href="' + buscar_situacao + '"><i class="fas fa-thumbs-down" data-toogle="tooltip" data-placement="top" style=\'color: white;\'></i></a>';
                        }else{
                            var status    = '<a class="btn btn-sm btn-success" id="ativar" data-href="' + buscar_situacao + '"><i class="fas fa-thumbs-up" data-toogle="tooltip" data-placement="top" style=\'color: white;\'></i></a>';
                        }


                        var botoes = visualizar + editar +  status;
                        return botoes;
                    }

                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
            order:[[0,'desc']],
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


    
    $(document).on('click', '#inativar', function(e){
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
                // console.log(retorno.data);
              $.each(retorno.data, function(key,value){
                console.log(value);
                var marca   = value.ds_marca_equipamento;
                var modelo  = value.ds_modelo_equipamento;       
                var sq_equipamento = value.sq_equipamento;
                var codigo_equipamento = value.equcod
               $('#equipamento-nome-inativar').html(codigo_equipamento);
               $('#equipamento-marca-inativar').html(marca);
               $('#equipamento-modelo-inativar').html(modelo);
           //   $('#prestadora-contrato-inativar').html(contrato);
           //   ('#prestadora-fornecedor-inativar').html(fornecedor);
               $('#modal-inativar-equipamento').modal('show');
               $('#equipamento-codigo-inativar').val(sq_equipamento);
            

              });
              
                                   
            }

        });
    });

    $(document).on('click', '#ativar', function(e){
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
              $.each(retorno.data, function(key,value){
                 var marca   = value.ds_marca_equipamento;
                 var modelo  = value.ds_modelo_equipamento;       
                 var sq_equipamento = value.sq_equipamento;
                 var codigo_equipamento = value.equcod
                $('#equipamento-nome-ativar').html(codigo_equipamento);
                $('#equipamento-marca-ativar').html(marca);
                $('#equipamento-modelo-ativar').html(modelo);
            //   $('#prestadora-contrato-inativar').html(contrato);
            //   ('#prestadora-fornecedor-inativar').html(fornecedor);
                $('#modal-ativar-equipamento').modal('show');
                $('#equipamento-codigo-ativar').val(sq_equipamento);
              
              });
              
                                   
            }

        });
    });






    // $("#cd_empresa").select2({
    //     theme: "bootstrap4",
    // });
    // console.log('chegou aqui publicado');
    // $("#cd_situacao").select2({
    //     theme: "bootstrap4",
    // });
    $("#btnLimpar").click(function () {
        // $("select").find("option").prop("selected", function () {
        //         return this.defaultSelected;
        // });
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario-pesquisar-prestadora").serializeArray(),
            success: function (retorno) {
                $('#combo_tipo option:first').prop('selected', true);
                $('#combo_situacao option:first').prop('selected', true);
                $('#input_modelo').val('');
                $('#input_marca').val('');
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
