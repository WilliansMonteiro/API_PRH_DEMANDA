$(document).ready(function() {
    // Função que faz a requisição para pesquisa de processos seletivos.
    $('#btn-pesquisar-processo-seletivo').click(function() {
        let url = $(this).data('href');
        $.ajax({
            type   : 'POST',
            url    :  url,
            data   :  $('#formularioPesquisarProcessoSeletivo').serializeArray(),
            success: function(retorno) {
                $('#retorno').html(retorno);
                $.fn.dataTable.moment('DD/MM/YYYY');
                $('#table-processo-seletivo').DataTable({
                    language: {
                        url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
                    },
                });
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function() {
                $('#carregar').html("");
            },
            error   : function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error('Desculpe! Ocorreu um erro.');
            }
        });
    });

    $(document).on('click', '#btnCadastrarProcesso', function(e){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioCadastrarProcesso").serializeArray(),
            beforeSend: function() {
                $('#carregar2').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar2').html("");
            },
            success	: function(retorno) {
              $("#mensagemErro").html('');
                var sq_processo_seletivo = retorno.data;

             if(retorno.data != null ){
                var display = document.getElementById('control_display').style.display;
                if(display == "none"){
                    document.getElementById('control_display').style.display = 'block';
                }
                var display = document.getElementById('control_display2').style.display;
                if(display == "none"){
                    document.getElementById('control_display2').style.display = 'block';
                }
                var botao = document.getElementById('botoes').style.display;
                if(botao == "block"){
                    document.getElementById('botoes').style.display = 'none';
                }
                var numero = document.getElementById('idNumero');
                if(numero != null){
                    document.getElementById('idNumero').disabled = 'true';
                }
                var nome = document.getElementById('no_processo_seletivo');
                if(nome != null){
                    document.getElementById('no_processo_seletivo').disabled = 'true';
                }
                var descricao = document.getElementById('ds_processo_seletivo');
                if(descricao != null){
                    document.getElementById('ds_processo_seletivo').disabled = 'true';
                }
                var data = document.getElementById('dataInicioInscricoes');
                if(data != null){
                    document.getElementById('dataInicioInscricoes').disabled = 'true';
                }
                var data2 = document.getElementById('dataFimInscricoes');
                if(data2 != null){
                    document.getElementById('dataFimInscricoes').disabled = 'true';
                }
                var data3 = document.getElementById('dataDivulgacao');
                if(data3 != null){
                    document.getElementById('dataDivulgacao').disabled = 'true';
                }
                document.getElementById("sq_processo_seletivo").value = retorno.data;
             }else{
                $("#mensagemErro").html(retorno.data);
             }
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });

    });

    $('#checkRecurso').change(function() {
        let dataArea = $('#datasRecurso');

        if ($(this).is(':checked')){
            $(this).attr('value', 'S');
            dataArea.show();
           } else {
            $(this).attr('value', 'N');
            dataArea.hide();
        }
    });

    $(document).on('click', '#btnCadastrarGrupoPorProcesso', function(e){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioCadastrarGrupoPorProcesso").serializeArray(),
            beforeSend: function() {
                $('#carregar3').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar3').html("");
            },
            success	: function(retorno) {
              $("#mensagemErro").html('');
                $('#cd_empresa_dependencia_dir').val('0').select2();
                $('#cd_empresa_dependencia_sup').val('0').select2();
                $('#cd_empresa_dependencia_ger').val('0').select2();
                $('#cd_funcao').val('0').select2();
                $('#sq_etapa_processo_seletivo').val('0').select2();

                var lista = document.getElementsByClassName("col-12");
                for(var i = lista.length - 1; i >= 0; i--)
                {
                    lista[i].remove()
                }

             if(retorno.data != null ){
                document.getElementById("sq_processo_seletivo").value = retorno.data;
                var display = document.getElementById('retorno-grupos').style.display;
                if(display == "none"){
                    document.getElementById('retorno-grupos').style.display = 'block';
                }
             }else{
                $("#mensagemErro").html(retorno.data);
             }
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    // $(document).on('click', '#btn-save-grupo', function(e) {
    //     let url = $(this).data('href');
    //     $.ajax({
    //         type   : 'POST',
    //         url    :  url,
    //         data   :  $('#form-cadastro-grupo').serializeArray(),
    //         success: function(retorno) {
    //             $('#retorno').html(retorno);
    //             $.fn.dataTable.moment('DD/MM/YYYY');
    //             $('#table-processo-seletivo').DataTable({
    //                 language: {
    //                     url: "//cdn.datatables.net/plug-ins/1.10.22/i18n/Portuguese-Brasil.json"
    //                 },
    //             });
    //             $("#resultadoConsulta").show("speed,callback");
    //         },
    //         beforeSend: function() {
    //             $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    //         },
    //         complete: function() {
    //             $('#carregar').html("");
    //         },
    //         error   : function(XMLHttpRequest, textStatus, errorThrown) {
    //             alert_error('Desculpe! Ocorreu um erro.');
    //         }
    //     });
    // });

    $(document).on('click', '#btn-campo-funcao', function(e){
        var IdElemento = $('#ItemFuncao').val();
        var proxElemento = parseInt(IdElemento) + 1;
        $('#ItemFuncao').val(proxElemento);

        var funcao = document.getElementById("cd_funcao").value;
        var nome_funcao = funcao.split("~");
        var cd_funcao = nome_funcao[0];
        var no_funcao = nome_funcao[1];

        // Limpa select de funções após função ser adicionada
        $("#cd_funcao").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("select").trigger("change.select2");

        // $('#cd_funcao').val('0').select2();
        $('#bloco_funcoes').append(
        "<div class='row' id='" +
        proxElemento +
        "'><div class='row col-12'><div class='col-md-6'><div class='form-group'><label for=''>Função adicionada</label><a class='list-group-item form-control' id='funcao"+
        proxElemento +
        "' name='grupo[funcao"+proxElemento+"]" +
        "'>"+ no_funcao +"</a><input type='hidden' name='verifica_existencia_funcao' value='"+1+"'><input type='hidden' class='form-control' name='grupo[ItemFuncao"+proxElemento+"]" +
        "' id='ItemFuncao" +
        proxElemento +
        "' value='"+ cd_funcao +"'><input type='hidden' class='form-control' name='grupo[NomeFuncao"+proxElemento+"]" + "' value='"+ no_funcao + "'></div></div><div class='col-md-4'><label for=''>Quantidade de vagas</label><input type='text' class='form-control' id='funcao_vagas"+
        "' name='grupo[funcao_vagas"+proxElemento+"]"+
        "'' required></div><div class='col-md-2' style='display: flex; justify-content: center; flex-direction: column; padding-top: 3%;'><button type='button' class='btn btn-danger btn-mm float-right' style='width: fit-content;' data-toggle='tooltip' data-placement='rigth' id='remover-anexo' title='Remover função' onclick='RemAnexo(" +
        proxElemento +
        ")'><i class= 'fa fa-minus-square' aria-hidden='true'></i></button></div></div>"
        );
    });

    $(document).on('click', '#btn-campo-etapa', function(e){
        var IdElemento = $('#ItemEtapa').val();
        var proxElemento = parseInt(IdElemento) + 1;
        $('#ItemEtapa').val(proxElemento);
        var indice_grupo  = $('#indice_grupo :selected').val();
        var cd_tipo_etapa_processo_seletivo = $('#cd_tipo_etapa_processo_seletivo :selected').val();

        var etapa = document.getElementById("cd_tipo_etapa_processo_seletivo").value;
        var nome_etapa = etapa.split("~");
        var cd_etapa = nome_etapa[0];
        var no_etapa = nome_etapa[1];

        // Limpa select de etapas após etapa ser adicionada
        $("#cd_tipo_etapa_processo_seletivo").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("select").trigger("change.select2");

        // $('#cd_tipo_etapa_processo_seletivo').val('0').select2();
        $('#bloco_etapa').append(
        "<div class='row' id='" +
        proxElemento +
        "'>" + "<input type='hidden' value='" + indice_grupo + "' name='cronograma"+proxElemento+"[indice_grupo]>" +
        "'<div class='row col-12'><div class='col-md-4'><div class='form-group'><label for=''>Etapa adicionada</label><a class='list-group-item form-control' id='etapa"+
        proxElemento +
        "' name='cronograma"+proxElemento+"[documento]" +
        proxElemento +
        "'>"+ no_etapa +"</a><input type='hidden' class='form-control' name='cronograma"+proxElemento+"[cd_tipo_etapa_processo_seletivo]" +
        proxElemento +
        "' id='ItemEtapa" +
        proxElemento +
        "' value='"+ cd_etapa +"'><input type='hidden' class='form-control' name='cronograma"+proxElemento+"[nome_etapa]" + "' value='"+ no_etapa + "'></div></div><div class='col-md-3'><label for=''>Início</label><br><input type='datetime-local' class='form-control' id='dataInicioEtapa"+
        proxElemento +
        "' name='cronograma"+proxElemento+"[dt_inicio_etapa]"+
        proxElemento +
        "' required></div><div class = 'col-md-3'><label for=''>Fim</label><span style='color:#FF1A1A'>*</span><br><input type='datetime-local' class='form-control' id='dataFimEtapa"+
        proxElemento +
        "' name='cronograma"+proxElemento+"[dt_fim_etapa]"+
        proxElemento +
        "' required></div><div class='col-md-1' style='display: flex; justify-content: center; flex-direction: column;'><br><button type='button' class='btn btn-danger btn-mm float-right' data-toggle='tooltip' data-placement='rigth' id='remover-anexo' title='Remover anexo' onclick='RemAnexo(" +
        proxElemento +
        ")'><i class= 'fa fa-minus-square' aria-hidden='true'></i></button></div></div>"
        );
    });

    // FILTRO PARA MUDAR SUPERINTENDÊNCIAS DE ACORDO COM DIRETORIA SELECIONADA
    $(document).on('change', '#cd_empresa_dependencia_dir', function(){
        var url = $(this).attr('data-href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "POST",
            url		: url,
            data    : {
                _token: $('meta[name="csrf-token"]').attr('content'),
                cd_empresa_dependencia : $(this).val()
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            },
            success	: function(retorno) {
                var nome_diretoria = $('#cd_empresa_dependencia_dir :selected').text();
                $('#empresa_dependencia_dir').val(nome_diretoria);

                $('#cd_empresa_dependencia_sup').val("");
                $('#empresa_dependencia_sup').val("");
                $('#cd_empresa_dependencia_sup').html('Carregando...')
                .find('option')
                .remove()
                .end();
                $('#cd_empresa_dependencia_sup').append('<option value="">Selecione</option>');
                $('#cd_empresa_dependencia_ger').val("");
                $('#empresa_dependencia_ger').val("");
                $('#cd_empresa_dependencia_ger').html('Carregando...')
                .find('option')
                .remove()
                .end();
                $('#cd_empresa_dependencia_ger').append('<option value="">Selecione</option>');
                $.each(retorno,function(key, value){
                    if(value === null){
                        $('#cd_empresa_dependencia_sup').append('<option value="">'+"Diretoria sem Superintendências vinculadas"+'</option>');
                    }else{
                        $('#cd_empresa_dependencia_sup').append('<option value="'+ value.cd_empresa_dependencia +'">'+ value.sg_dependencia+ ' - '+ value.nm_dependencia+ ' </option>');
                        document.getElementById("empresa_dependencia_sup").value = '';
                    }
                });
            }
        });
    });

    // FILTRO PARA MUDAR GERÊNCIAS DE ACORDO COM SUPERINTENDÊNCIA SELECIONADA
    $(document).on('change', '#cd_empresa_dependencia_sup', function(){
        var url = $(this).attr('data-href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "POST",
            url		: url,
            data    : {
                _token: $('meta[name="csrf-token"]').attr('content'),
                cd_empresa_dependencia : $(this).val()
            },
            beforeSend: function() {
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            },
            success	: function(retorno) {
                var nome_superintendencia = $('#cd_empresa_dependencia_sup :selected').text();
                var cd_superintendencia   = $('#cd_empresa_dependencia_sup :selected').val();
                $('#cd_empresa_dependencia_sup').val(cd_superintendencia);
                $('#empresa_dependencia_sup').val(nome_superintendencia);

                $('#cd_empresa_dependencia_ger').val("");
                $('#empresa_dependencia_ger').val("");
                  $('#cd_empresa_dependencia_ger').html('Carregando...')
                  .find('option')
                    .remove()
                   .end();
                   $('#cd_empresa_dependencia_ger').append('<option value="">Selecione</option>');
                 $.each(retorno,function(key, value){
                     if(value === null){
                        $('#cd_empresa_dependencia_ger').append('<option value="">'+"Diretoria sem Superintendências vinculadas"+'</option>');
                     }else{
                        $('#cd_empresa_dependencia_ger').append('<option value="'+ value.cd_empresa_dependencia +'">'+ value.sg_dependencia+ ' - '+ value.nm_dependencia+ ' </option>');
                        document.getElementById("empresa_dependencia_ger").value = '';
                     }
                 });
            }
        });
    });

    // PREENCHE INPUTS COM DADOS DE GERÊNCIA APÓS ONCHANGE DE SELECT DE GERÊNCIA
    $(document).on('change', '#cd_empresa_dependencia_ger', function() {
        var nome_gerencia = $('#cd_empresa_dependencia_ger :selected').text();
        var cd_gerencia   = $('#cd_empresa_dependencia_ger :selected').val();
        $('#cd_empresa_dependencia_ger').val(cd_gerencia);
        $('#empresa_dependencia_ger').val(nome_gerencia);
    });

    $(document).on('click', '#btn-inativar-processo-seletivo', function(e) {
        let url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type   : 'POST',
            url    : url,
            success: function(retorno) {
                $.each(retorno.data, function(key, value) {
                    var numero_processo_seletivo = value.dn_processo_seletivo;
                    var nome_processo_seletivo = value.no_processo_seletivo;
                    var descricao_processo_seletivo = value.ds_processo_seletivo;
                    var sq_processo_seletivo = value.sq_processo_seletivo;

                    $('#inativarNumero').html(numero_processo_seletivo);
                    $('#inativarNome').html(nome_processo_seletivo);
                    $('#inativarDescricao').html(descricao_processo_seletivo);
                    $('#modal-inativar-processo-seletivo').modal('show');
                    $('#inputInativar').val(sq_processo_seletivo);
                });
            }
        });
    });

    $(document).on('click', '#btn-ativar-processo-seletivo', function(e) {
        let url = $(this).data('href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type    : 'POST',
            url     : url,
            success : function(retorno) {
                $.each(retorno.data, function(key, value) {
                    var numero_processo_seletivo = value.dn_processo_seletivo;
                    var nome_processo_seletivo = value.no_processo_seletivo;
                    var descricao_processo_seletivo = value.ds_processo_seletivo;
                    var sq_processo_seletivo = value.sq_processo_seletivo;

                    $('#ativarNumero').html(numero_processo_seletivo);
                    $('#ativarNome').html(nome_processo_seletivo);
                    $('#ativarDescricao').html(descricao_processo_seletivo);
                    $('#modal-ativar-processo-seletivo').modal('show');
                    $('#inputAtivar').val(sq_processo_seletivo);
                })
            }
        })
    });
    $(document).on('click', '#btn-excluir', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#dc3c45',
            confirmButtonColor: '#55a846',
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
                    },
                    error   : function(XMLHttpRequest, textStatus, errorThrown) {
                        console.log('Erro: ', errorThrown);
                    }

                });
            }
        });
    });
    $(document).on('click', '#icone_head', function(e){
        var display = document.getElementById('control_display').style.display;

        if(display == "none")
            document.getElementById('control_display').style.display = 'block';
        else
            document.getElementById('control_display').style.display = 'none';
        $('#' + 'icone_head').toggleClass('fa-plus-square fa fa-minus-square fa');
    });
});

function RemAnexo(IdElemento) {
    console.log($('#' + IdElemento));
    $('#' + IdElemento ).remove();
};

