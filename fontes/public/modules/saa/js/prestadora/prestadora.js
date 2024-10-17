(function () {
    function init() {
        events();
        document.getElementById("btn-prestadora").click()
    }

    function events() {
        $(document).on('click', '#btn-prestadora', pesquisar_prestadora);
        // $(document).on('click', '#btnStatusCargo', statusCargo);

    }

    function pesquisar_prestadora()
    {
        $('#tb-prestadora').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario-pesquisar-prestadora").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTablePrestadora(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb-prestadora tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTablePrestadora(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar  = $("#input-rota-visualizar").val();
        var rota_editar      = $("#input-rota-editar").val();
        var rotaStatus       = $("#rota-busca-dados-ativar-inativar").val();
        // var rota_excluir      = $("#input-rota-editar").val();
        var gestor_saa       = $("#gestor_saa").val();



        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-prestadora')) {
            table = $('#tb-prestadora').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tb-prestadora').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.pstcodigo
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.pstnome
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.nr_contrato
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.pstend
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.pstativa === "S")
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
                        var sqVisualizar   = rota_visualizar  + '/' + row.pstcodigo;
                        var sqEditar   = rota_editar + '/' + row.pstcodigo;
                        var buscar_situacao = rotaStatus + '/' + row.pstcodigo;

                        // var sq_    = rota_excluir_solicitacao   + '/' + row.sq_solic_cadastramento;

                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Visualizar prestadora"></i></a>&nbsp;';
                        var editar  = '<a class="btn btn-success btn-sm" href="' + sqEditar + '"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar prestadora"></i></a>&nbsp;';


                        if(row.pstativa === 'S'){
                            var status    = '<a class="btn btn-sm btn-danger" id="inativar" data-href="' + buscar_situacao + '"><i class="fas fa-thumbs-down" data-toogle="tooltip" data-placement="top" style=\'color: white;\'></i></a>';
                        }else{
                            var status    = '<a class="btn btn-sm btn-success" id="ativar" data-href="' + buscar_situacao + '"><i class="fas fa-thumbs-up" data-toogle="tooltip" data-placement="top" style=\'color: white;\'></i></a>';
                        }


                        if(Number(gestor_saa) === 0) {
                            var botoes = visualizar + editar +  status;
                        }else{
                            var botoes = visualizar;
                        }
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

    function statusCargo(e){
        let msg = $(this).data('msg');
        e.preventDefault();
        Swal.fire({
            title: msg,
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
                let status = $(this).data('status');
                let carcodigo = $(this).data('carcodigo');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type    : "POST",
                    url     : url,
                    data    : {
                        'status' : status,
                        'carcodigo' : carcodigo
                    },
                    success : function(retorno) {
                        console.log(retorno);
                        // if (retorno.status)
                        //     helper.alertSuccess(retorno.msg)
                        // else
                        //     helper.alertError(retorno.msg)
                        if(retorno.status)
                        window.location.reload()
                        // document.getElementById("btnPesquisarTerminal").click()
                    }

                });
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
                var contrato   = value.nr_contrato;
                // var fornecedor = value.no_fornecedor;
                var nome = value.pstnome;
                var pstcodigo = value.pstcodigo
              $('#prestadora-nome-inativar').html(nome);
              $('#prestadora-contrato-inativar').html(contrato);
            //   ('#prestadora-fornecedor-inativar').html(fornecedor);
              $('#modal-inativar-prestadora').modal('show');
              $('#prestadora-codigo-inativar').val(pstcodigo);

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
                 var contrato   = value.nr_contrato;
                // var fornecedor = value.no_fornecedor;
                var nome = value.pstnome;
                var pstcodigo = value.pstcodigo
                $('#prestadora-nome-ativar').html(nome);
                $('#prestadora-contrato-ativar').html(contrato);
            //   ('#prestadora-fornecedor-inativar').html(fornecedor);
                $('#modal-ativar-prestadora').modal('show');
                $('#prestadora-codigo-ativar').val(pstcodigo);

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
                $('#combo_codigo option:first').prop('selected', true);
                $('#combo_endereco option:first').prop('selected', true);
                $('#combo_situacao option:first').prop('selected', true);
                $('#combo_contrato option:first').prop('selected', true);
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
