(function () {
    function init() {
        events();
        document.getElementById("btnCargoPesquisar").click()
    }

    function events() {
        $(document).on('click', '#btnCargoPesquisar', pesquisar_cargo);
        $(document).on('click', '#btnStatusCargo', statusCargo);

    }

    function pesquisar_cargo()
    {
        $('#tb-cargo').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formularioGerenciarCargo").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTableCargo(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tb-cargo tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTableCargo(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar  = $("#input-rota-visualizar").val();
        var rota_editar      = $("#input-rota-editar").val();
        var rotaStatus       = $("#input-rota-situacao").val();
        var gestor_saa       = $("#gestor_saa").val();


        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tb-cargo')) {
            table = $('#tb-cargo').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tb-cargo').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.carcodigo
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.cardescricao
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.carativo === "S")
                        {
                            return "Ativo"
                        }else{
                            return "Inativo"
                        }
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        //As varaiveis abaixo estao concatenando as rotas com as variaveis para serem incluidas nos botões
                        var sqVisualizar   = rota_visualizar  + '/' + row.carcodigo;
                        var sqEditar   = rota_editar + '/' + row.carcodigo;

                        // var sq_    = rota_excluir_solicitacao   + '/' + row.sq_solic_cadastramento;

                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Visualizar cargo"></i></a>&nbsp;';
                        var editar  = '<a class="btn btn-primary btn-sm" href="' + sqEditar + '"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar cargo"></i></a>&nbsp;';


                        if(row.carativo === 'S'){
                            var status    = '<a class="btn btn-sm btn-danger" href="" id="btnStatusCargo"   data-msg="Tem certeza que deseja inativar o cargo?" data-status="S"  data-carcodigo = "' + row.carcodigo + '" data-href="' + rotaStatus + '"><i class=\'fas fa-thumbs-down\' data-toggle=\'tooltip\' data-placement=\'top\' title=\'Inativar o terminal\' style=\'color: white;\'></i></a>';
                        }else{
                            var status    = '<a class="btn btn-sm btn-success" href="" id="btnStatusCargo"  data-msg="Tem certeza que deseja ativar o cargo?" data-status="N" data-carcodigo = "' + row.carcodigo + '" data-href="' + rotaStatus + '"><i class=\'fas fa-thumbs-up\' data-toggle=\'tooltip\' data-placement=\'top\' title=\'Ativar o terminal\' style=\'color: white;\'></i></a>';
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
            data: $("#formularioGerenciarCargo").serializeArray(),
            success: function (retorno) {
                $('#status_combo option:first').prop('selected', true);
                $('#codigo_input').val('');
                $('#nome_input').val('');
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
