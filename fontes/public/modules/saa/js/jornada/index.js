(function () {
    function init() {
        events();
        document.getElementById("btn-pesquisar-jornada").click()
    }

    function events() {
        $(document).on('click', '#btn-pesquisar-jornada', pesquisar_jornada);
        $(document).on('click', '#btnStatusCargo', statusJornada);

    }
    function pesquisar_jornada()
    {
        $('#tabela-jornada').DataTable().clear().destroy();
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario-pesquisar-jornada").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                populateTableJornada(retorno);
            },
            beforeSend: function () {
                var row = ['<tr>'];
                row.push('<td align="center" colspan="8"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tabela-jornada tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTableJornada(retorno){
        // Abaixo estão variaveis com os input hidden da view. Esses inputs guardam o nome das rotas para serem concatenados com as matriculas

        var rota_visualizar  = $("#input-rota-visualizar").val();
        var rota_editar      = $("#input-rota-editar").val();
        var rotaStatus       = $("#input-rota-situacao").val();
        var gestor_saa       = $("#gestor_saa").val();


        // O if abaixo faz uma verificação! Se a datatable existe, ela é destruida para gerar uma nova datatable com dados diferntes
        if ($.fn.dataTable.isDataTable('#tabela-jornada')) {
            table = $('#tabela-jornada').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tabela-jornada').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.jorcategoria
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorent01
                        var data_entrada = new Date(row.jorhorent01);
                        var horario_entrada = data_entrada.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        return horario_entrada;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        var data_saida = new Date(row.jorhorsaida02);
                        var horario_saida= data_saida.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        return horario_saida;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        if(row.cd_status_jornada == 1)
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
                        var sqVisualizar = rota_visualizar  + '/' + row.jorcategoria;
                        var sqEditar     = rota_editar + '/' + row.jorcategoria;


                        var visualizar  = '<a class="btn btn-primary btn-sm" href="' + sqVisualizar + '"><i class="fas fa-search" data-toggle="tooltip" data-placement="top" title="Visualizar jornada"></i></a>&nbsp;';
                        var editar  = '<a class="btn btn-success btn-sm" href="' + sqEditar + '"><i class="fa fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar jornada"></i></a>&nbsp;';
                        if(row.cd_status_jornada == 1){
                            var status    = '<a class="btn btn-sm btn-danger" href="" id="btnStatusCargo"   data-msg="Tem certeza que deseja inativar a jornada?" data-status="1"  data-jorcategoria = "' + row.jorcategoria + '" data-href="' + rotaStatus + '"><i class=\'fas fa-thumbs-down\' data-toggle=\'tooltip\' data-placement=\'top\' title=\'Inativar a jornada\' style=\'color: white;\'></i></a>';
                        }else{
                            var status    = '<a class="btn btn-sm btn-success" href="" id="btnStatusCargo"  data-msg="Tem certeza que deseja ativar a jornada?" data-status="2" data-jorcategoria = "' + row.jorcategoria + '" data-href="' + rotaStatus + '"><i class=\'fas fa-thumbs-up\' data-toggle=\'tooltip\' data-placement=\'top\' title=\'Ativar a jornada\' style=\'color: white;\'></i></a>';
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


    function statusJornada(e){
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
                let jorcategoria = $(this).data('jorcategoria');
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
                        'jorcategoria' : jorcategoria
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





    $("#jorcategoria_combo").select2({
        theme: "bootstrap4",
    });

    $("#btnLimpar").click(function () {
        var url = $(this).data('href');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario-pesquisar-jornada").serializeArray(),
            success: function (retorno) {
                $('#jorcategoria_combo option:first').prop('selected', true);
                $('#cd_status_combo option:first').prop('selected', true);
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
