(function () {
    function init() {
        events();
        config();
        document.getElementById("btn-pesquisar-usuarios").click()
    }

    function events() {
        $(document).on('click', '#btn-pesquisar-usuarios', pesquisar_usuarios);

    }

    function config() {
        $("#input_matricula").mask("00000000");
    }

    function pesquisar_usuarios()
    {
        $('#tabela-usuarios').DataTable().clear().destroy();
        var url = $(this).data('href');
        // var permissao_solicitante       = $("#permissao_solicitante").val();
        var permissao_consulta_global   = $("#permissao_consulta_global").val();
        var permissao_gestor_modulo_saa = $("#permissao_gestor_modulo_saa").val();
        var permissao_adm_modulo_saa    = $("#permissao_adm_modulo_saa").val();
        var permissao_adm_nuadm         = $("#permissao_adm_nuadm").val();
        var permissao_aprovador_gerit   = $("#permissao_aprovador_gerit").val();
        var permissao_portaria_cms      = $("#permissao_portaria_cms").val();
        $.ajax({
            type: "POST",
            url: url,
            data: $("#formulario-pesquisar-usuarios").serializeArray(),
            success: function (retorno) {
                console.log(retorno);
                if(Number(permissao_consulta_global) === 1 || Number(permissao_adm_modulo_saa) === 1 || Number(permissao_adm_nuadm) === 1 || Number(permissao_aprovador_gerit) && Number(permissao_gestor_modulo_saa) === 0)
                {
                    populateTableUsuariosSolicitante(retorno);
                    console.log('segundo if');
                }
                if(Number(permissao_gestor_modulo_saa) === 1 && Number(permissao_consulta_global) === 0 && Number(permissao_adm_modulo_saa) === 0 && Number(permissao_adm_nuadm) === 0 && Number(permissao_aprovador_gerit) === 0){
                        populateTableUsuariosGestor(retorno);
                        //     console.log('primeiro  if');
                 }

                if(Number(permissao_portaria_cms) === 1){
                    populateTableUsuariosPortaria(retorno)
                    console.log('terceiro if');
                }
            },
            beforeSend: function () {
                var row = ['<tr>'];
                // row.push('<td align="center" colspan="11"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tabela-usuarios tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },

        });


    }
    function populateTableUsuariosSolicitante(retorno){

        if ($.fn.dataTable.isDataTable('#tabela-usuarios')) {
            table = $('#tabela-usuarios').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tabela-usuarios').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.fdematric + '-' + row.fdedgv;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorent01
                        return row.fdenome;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.fdecpf_formatado;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        if(row.dt_nascimento){
                            var data_nascimento = new Date(row.dt_nascimento);
                            var dataFormatada = data_nascimento.toLocaleDateString();
                            return dataFormatada;
                        }else{
                            return null;
                        }

                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.fdefiliacaomae;
                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.cardescricao;
                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.fncdescricao;
                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.nm_dependencia;
                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        if(row.fdesituacao === 0 || row.fdesituacao == 0){
                            return "Inativo";
                        }else{
                            return "Ativo";
                        }

                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.ds_tipo_empregado;
                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.nm_empresa;
                    }
                },

            ],
            //O codigo abaixo define o idioma PT_BR para a dataTable
            order:[[4,'asc']],
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

    function populateTableUsuariosGestor(retorno){

        if ($.fn.dataTable.isDataTable('#tabela-usuarios')) {
            table = $('#tabela-usuarios').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tabela-usuarios').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.fdematric + '-' + row.fdedgv;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.fdenome;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.cardescricao;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        return row.fncdescricao;

                    }
                },

                {
                    "render": function (data, type, row, meta) {
                        return row.nm_dependencia;
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


    function populateTableUsuariosPortaria(retorno)
    {
        if ($.fn.dataTable.isDataTable('#tabela-usuarios')) {
            table = $('#tabela-usuarios').DataTable();
            table.destroy();
        }

        // Populando a tabela com os dados recuperados da requisição!

        $('#tabela-usuarios').DataTable({

            "data": retorno,
            "columns": [
                {
                    "render": function (data, type, row, meta) {
                        return row.fdematric + '-' + row.fdedgv;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorent01
                        return row.fdenome;
                    }
                },
                {
                    "render": function (data, type, row, meta) {
                        // return row.jorhorsaida02
                        return row.sg_dependencia +'-'+ row.nm_dependencia;
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



    // $("#btnLimpar").click(function () {
    //     var url = $(this).data('href');
    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: $("#formulario-pesquisar-jornada").serializeArray(),
    //         success: function (retorno) {
    //             $('#jorcategoria_combo option:first').prop('selected', true);
    //             $('#cd_status_combo option:first').prop('selected', true);
    //             $("select").trigger("change.select2");
    //         },
    //         beforeSend: function () {
    //             $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    //         },
    //         complete: function () {
    //             $('#carregar').html("");
    //         },

    //     });

    // });

    init();



})();
