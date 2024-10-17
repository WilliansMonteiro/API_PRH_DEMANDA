(function () {

    var usuariosBrb = [];
    var usuariosTerceirizados = [];

    function init() {
        events();
        config();
        // loadDataTableControleAcesso();
        loadDataTableSolicitacoesPendentes();
        usuariosBrb = JSON.parse(JSON.stringify(primeiraPesquisaUsuariosBrb));
        usuariosTerceirizados = JSON.parse(JSON.stringify(primeiraPesquisaUsuTerceirizados));
        usuariosTerceirizados.forEach(function (usuario){
            delete usuario.cd_area_usuario;
        });
    }

    function events() {
        $(document).on(
            "click",
            "#btnPesquisarSolicitacoesAcessoPendentes",
            pesquisar
        );

        $(document).on("click", "#btnUsuarioBrb2Csv", function (){
            gerarCsv(usuariosBrb, 1);
        });

        $(document).on("click", "#btnUsuarioTerceiros2Csv", function (){
            gerarCsv(usuariosTerceirizados, 2);
        });

        $("#acesso-brb-div").css("display", "block");
        $("#btnUsuarioBrb2Csv").css("display", "block");

        $('#tbSolicitacoesPendentesTerceiros').DataTable({
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
            }
        });
        $('#tbControleAcessoTerceiros').dataTable({
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
            }
        });


        $('#tb_usuarios_brb_controle').dataTable({
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
            }
        });

    }

    function config() {
        $("#nr_matricula").mask("00000000");
    }

    function gerarCsv(usuarios, tipoUsuarios){
        var nrMatriculas = [];
        var url = '';
        var nomeCsv = '';
        var carregamentoTela = '';

        if(tipoUsuarios == 1){
            url = $('#infoAcessosUsuarioBrb').val();
            nomeCsv = "Usuarios-BRB.csv";
            carregamentoTela = $('#carregar');
        }else if(tipoUsuarios == 2){
            url = $('#infoAcessosUsuarioTerceiro').val();
            nomeCsv = "Usuarios-Terceiros.csv";
            carregamentoTela = $('#carregar-terceiros');
        }else{
            swal.fire({
                icon: "error",
                text: "Erro ao gerar o CSV!"
            });
            return false;
        }

        usuarios.forEach(usuario => {
            nrMatriculas.push(usuario.nr_matricula);
        });
        
        if(nrMatriculas.length > 0){
            if(nrMatriculas.length >= 1000){

                let chunks = [];

                for (let i = 0; i < nrMatriculas.length; i += 1000) {
                    chunks.push(nrMatriculas.slice(i, i + 1000));
                }

                console.log(chunks);

                let promise = chunks.map(chunk => {
                    return $.ajax({
                        type: "POST",
                        url: url,
                        data: {"nrMatriculas": chunk},
                        dataType: "json",
                        beforeSend: function () {
                            carregamentoTela.html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                        },
                        complete: function () {
                            carregamentoTela.html("");
                        }
                    }).then(results => {
                        console.log(results)
                        let usuariosFiltrados = usuarios.filter(usuario => Object.keys(results.modulos).includes(usuario.nr_matricula));
                        let array = [];

                        usuariosFiltrados.forEach(usuario => {
                            usuario.modulos = results.modulos[usuario.nr_matricula];
                            usuario.acessos = results.acessos[usuario.nr_matricula];

                            array.push(usuario);                       
                        });
                        return array;
                    });
                });

                Promise.all(promise).then(results =>{
                    arrayToCsv(results.flat() ,nomeCsv);
                }).catch(error => {
                    swal.fire({
                        icon: "error",
                        text: "Erro em uma das solicitações: "+error
                    });
                    return false;
                });

            }else{
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {"nrMatriculas": nrMatriculas},
                    dataType: "json",
                    success: function (retorno) {
                        var usuariosFormatados = usuarios.map(usuario => {
                            usuario.modulos = retorno.modulos[usuario.nr_matricula];
                            usuario.acessos = retorno.acessos[usuario.nr_matricula];
                            return usuario;                       
                        });
    
                        arrayToCsv(usuariosFormatados,nomeCsv);
                    },
                    beforeSend: function () {
                        carregamentoTela.html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                    },
                    complete: function () {
                        carregamentoTela.html("");
                    }
                });
            }
        }else{
            swal.fire({
                icon: "error",
                text: "Erro ao gerar o CSV!"
            });
            return false;
        }
    }

    function arrayToCsv(array, fileName) {
        if (!array || !array.length) {
            return;
        }

        const csvContent = array.map(row => {
            
            return row.modulos.map((modulo, i) =>{
                return Object.keys(array[0]).map(field => {
                    if(field == "modulos"){
                        return JSON.stringify(row.modulos[i], (key, value) => value === null ? '' : value);
                    }else if(field == "acessos"){
                        return JSON.stringify(row.acessos[i], (key, value) => value === null ? '' : value);
                    }else{
                        return JSON.stringify(row[field], (key, value) => value === null ? '' : value);
                    }
                }).join(';');
            }).join('\n');
            
        });
        
        const headers = ['Matrícula', 'Nome', 'Área', 'Módulo', 'Perfil'];

        csvContent.unshift(headers.join(';'));

        const csvString = '\uFEFF'+csvContent.join('\r\n');

        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', fileName);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function pesquisar() {
        var url = $("#rotaSolicitacoesPendentes").val();
        if ($("#tp_consulta").val() == 2) {
            var url = $("#rotaControleAcesso").val();
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                cd_modulo: $("#cd_modulo").val(),
                ds_area: $("#ds_area").val(),
                nr_matricula: $("#nr_matricula").val(),
            },
            success: function (retorno) {
                if ($("#tp_consulta").val() == 1) {
                    populateTableSolicitacaoPendente(retorno);
                } else {
                    usuariosBrb = retorno.usuarios;
                    // populateTableControleAcessso(retorno);
                    $("#acesso-brb-div").html("");
                    $('#acesso-brb-div').html(retorno.table);
                       //   $.fn.dataTable.moment('DD/MM/YYYY');
                          $('#tabela_controle_parametrizado_brb').DataTable({
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
                }
            },
            beforeSend: function () {
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                if ($("#tp_consulta").val() == 1) {
                    $(["#tbSolicitacoesPendentes tbody"].join("")).append(
                        row.join("")
                    );
                }
            },
        });
    }


    function loadDataTableSolicitacoesPendentes() {
        var url = $("#rotaSolicitacoesPendentes").val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (retorno) {
                populateTableSolicitacaoPendente(retorno);
            },
            beforeSend: function () {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                var row = ["<tr>"];
                row.push(
                    '<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>'
                );
                row.push("</tr>");
                $(["#tbSolicitacoesPendentes tbody"].join("")).append(
                    row.join("")
                );
            },
            complete: function(){
                //   console.log('funcionou');
              $('#carregar').html("");
            },
        });
    }

    function populateTableSolicitacaoPendente(retorno) {
        $("#tbSolicitacoesPendentes tbody>tr").remove();
        if (retorno.count > 0) {
            $.each(retorno.data, function (key, value) {
                console.log(value);
                var informacao =
                    '<a class="btn btn-primary btn-sm" href="gerenciarAcesso/informacoes/' +
                    value.sq_usuario_perfil +
                    '"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Informações do Usuário"></i></a>&nbsp';
                var aprovado =
                    '<a class="btn btn-success btn-sm" href="gerenciarAcesso/aprovar/' +
                    value.sq_usuario_perfil +
                    '"><i class="fas fa-thumbs-up" data-toggle="tooltip" data-placement="top" title="Aprovar Solicitação de Acesso"></i></a>&nbsp';
                var reprovado =
                    '<a class="btn btn-danger btn-sm" href="gerenciarAcesso/reprovar/' +
                    value.sq_usuario_perfil +
                    '"><i class="fas fa-thumbs-down" data-toggle="tooltip" data-placement="top" title="Reprovar  Solicitação de Acesso"></i></a>&nbsp';
                var area =
                    value.ds_area_benner != null ? value.ds_area_benner : "";

                var row = ["<tr>"];
                row.push(
                    '<td class="text-center">' + value.nr_matricula + "</td>"
                );
                row.push("<td>" + value.no_usuario + "</td>");
                row.push('<td class="text-center">' + area + "</td>");
                row.push(
                    '<td class="text-center">' + value.ds_modulo + "</td>"
                );
                row.push(
                    '<td class="project-actions text-center">' +
                        informacao +
                        aprovado +
                        reprovado +
                        "</td>"
                );
                row.push("</tr>");
                $(["#tbSolicitacoesPendentes tbody"].join("")).append(
                    row.join("")
                );
            });
            if ($.fn.dataTable.isDataTable("#tbSolicitacoesPendentes")) {
                table = $("#tbSolicitacoesPendentes").DataTable();
            } else {
                $("#tbSolicitacoesPendentes").DataTable({
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
            }
        } else {
            var row = ["<tr>"];
            row.push(
                '<td align="center" colspan="5">Nenhum Registro encontrado!</td>'
            );
            row.push("</tr>");
            $(["#tbSolicitacoesPendentes tbody"].join("")).append(row.join(""));
        }
    }













    //////////////////////////////////////// terceiros //////////////////////////

    $('#btnPesquisarSolicitacoesAcessoPendentesTerceiros').click(function(){

        // if ($("#tp_consulta_terceiros").val() == 2) {
        //     var url = $("#rotaControleAcessoTerceiros").val();
        // }

        var url = $(this).data('href');
        if ($("#tp_consulta_terceiros").val() == 2) {
            var url = $("#rotaControleAcessoTerceiros").val();
        }
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioSolicitacaoAcessoPendenteTerceiros").serializeArray(),
            success	: function(retorno) {
                if ($("#tp_consulta_terceiros").val() == 2) {
                retorno = JSON.parse(retorno);

                usuariosTerceirizados = retorno.usuarios;
                usuariosTerceirizados.forEach(function (usuario){
                    delete usuario.cd_area_usuario;
                });
                $("#acesso-terceiros-div").html("");
                $('#acesso-terceiros-div').html(retorno.table);
                   //   $.fn.dataTable.moment('DD/MM/YYYY');
                      $('#minhaTabelaTerceirosAcesso').DataTable({
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
             }else{
                $("#pendentes-terceiros-div").html("");
                $('#pendentes-terceiros-div').html(retorno);
                   //   $.fn.dataTable.moment('DD/MM/YYYY');
                      $('#minhaTabela').DataTable({
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

             }


            $("#resultadoConsulta").show("speed,callback");
            },
              beforeSend: function() {
                  $('#carregar-terceiros').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                //   console.log('funcionou');
              $('#carregar-terceiros').html("");
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    init();
})();
