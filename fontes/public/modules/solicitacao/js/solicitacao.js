//Cadastro de Solicitações
var posicaoAnexo = 0;
var qtdPergunta = $('#qtdPergunta').val();

function addInput(valor){
   
    //Esconde o Input File Anterior
    $(valor).hide();
    var posicao = ++posicaoAnexo
        
    $("#inputAnexo").append('<input id="anexo_'+posicao+'" onchange="addInput(this)" name="no_anexo[]" accept="image/jpeg , application/pdf" type="file">')
    $("#sq_anexo_lista").show();

    var novaPosicao = posicao - 1;
    var estruturaAnexo = '<table class="tabelaAnexo" id="anexoTabela_'+novaPosicao+'"><thead> <tr><td><img class="imgAnexo" src="/img/file.png"><br>'+valor.files[0].name+'</td></tr></thead><tbody><tr><td><img id="'+novaPosicao+'" onclick="anexoDelete(this)" class="closeIcon" src="/img/close.svg"></td></tr></tbody></table>'
    
    $("#listaAnexo").append(estruturaAnexo);
}

function anexoDelete(valor){
    var id = valor.id
 
    $("#anexo_"+id).remove()
    $("#anexoTabela_"+id).remove()
}

function anexoCadastroDelete(valor){

    var id = valor.id;
 
    $("#anexo_"+id).remove()
    

    $("#listaAnexosDeletados").append('<input  name="lista_anexo_delete[]" style="display:none;" value="'+id+'" type="text">');
}


$(document).on('click', '#btnSalvarSolicitacao', function(e){
    e.preventDefault();
    $('#tipo_request').val('S')
    $('#formularioPesquisarSolicitacao').submit();
    
});

$(document).on('click', '#btnSalvarSolicitacaoAndAprova', function(e){
    e.preventDefault();
    $('#tipo_request').val('SA')
    $('#formularioPesquisarSolicitacao').submit();
    
});


//CHANGE de TIPO SOLICITAÇÂO
$(document).on('change', '#tipoSolicitacao', function(e){

    //Limpando Inputs e Checks e Labels
    $('[class="help-block"]').remove();
    $('[class="alert alert-danger"]').remove();
    $('[name="dt_inicio"]').val('');
    $('[name="dt_fim"]').val('');
    $('[name="ds_orgao"]').val('');
    $("input:radio[name='tipoCessao']").each(function(i) {
        this.checked = false;
    });

    $('[name="cd_motivo_solicitacao"]')[0].selectedIndex = 0;

    //CESSÃO
    if(this.value == 5){

        $('#tipoCessao').show()
        $('#orgao').show()
        $('#dataInicio').show()
        $('#email').show()
        $('#dataFim').hide()
        $('#listaAnexosEstaticos').show()
        $('#motivo').hide()
        $('#matricula').hide()
        $('#ds_solicitacao').show()
        $('#inputAnexo').show()

        $('#ds_curso').hide()
        $('#data_inicio_curso').hide()
        $('#dt_fim_curso').hide()
        $('#ds_carga_horaria').hide()
        $('#ds_justificativa').hide()
        $('#ds_observacao').hide()
        $('#no_empresa').hide()
        $('#cnpj_empresa').hide()
        $('#telefone').hide()
        $('#ddd_telefone').hide()
        $('#email_empresa').hide()
        $('#local_evento').hide()
        $('#quantidade').hide()
        $('#valor_estimado').hide()
        $('#valor_diarias').hide()
        $('#valor_passagens').hide()
        $('#conta_debito').hide()
        $('#dt_inicio_licenca').hide()
        $('#dias_licenca').hide()
        $('#dt_fim_licenca').hide()
    }
    //LICENÇA INTERESSE
    else if(this.value == 3){
        $('#motivo').show()
        $('#dataInicio').hide()
        $('#matricula').show()
        $('#dataFim').show()
        $('#email').show()
        $('#tipoCessao').hide()
        $('#orgao').hide()
        $('#listaAnexosEstaticos').hide()
        $('#ds_solicitacao').show()
        $('#inputAnexo').show()
        $('#dt_inicio_licenca').show()
        $('#dias_licenca').show()
        $('#dt_fim_licenca').show()

        $('#ds_curso').hide()
        $('#data_inicio_curso').hide()
        $('#dt_fim_curso').hide()
        $('#ds_carga_horaria').hide()
        $('#ds_justificativa').hide()
        $('#ds_observacao').hide()
        $('#no_empresa').hide()
        $('#cnpj_empresa').hide()
        $('#telefone').hide()
        $('#ddd_telefone').hide()
        $('#email_empresa').hide()
        $('#local_evento').hide()
        $('#quantidade').hide()
        $('#valor_estimado').hide()
        $('#valor_diarias').hide()
        $('#valor_passagens').hide()
        $('#conta_debito').hide()
        

    }

    //EVENTO EXTERNO
    else if(this.value == 6){
        
        $('#ds_curso').show()
        $('#data_inicio_curso').show()
        $('#dt_fim_curso').show()
        $('#ds_carga_horaria').show()
        $('#ds_justificativa').show()
        $('#ds_observacao').show()
        $('#no_empresa').show()
        $('#cnpj_empresa').show()
        $('#telefone').show()
        $('#ddd_telefone').show()
        $('#email_empresa').show()
        $('#local_evento').show()
        $('#quantidade').show()
        $('#valor_estimado').show()
        $('#inputAnexo').show()

        $('#listaAnexosEstaticos').hide()
        $('#ds_solicitacao').hide()
        $('#tipoCessao').hide()
        $('#orgao').hide()
        $('#dataInicio').hide()
        $('#motivo').hide()
        $('#dataInicio').hide()
        $('#dataFim').hide()
        $('#dt_inicio_licenca').hide()
        $('#dias_licenca').hide()
        $('#dt_fim_licenca').hide()
    }
    
    else{
        $('#motivo').hide()
        $('#dataInicio').hide()
        $('#dataFim').hide()
        $('#tipoCessao').hide()
        $('#orgao').hide()
        $('#matricula').hide()
        $('#listaAnexosEstaticos').hide()
        $('#ds_solicitacao').show()
        $('#inputAnexo').show()

        $('#ds_curso').hide()
        $('#data_inicio_curso').hide()
        $('#dt_fim_curso').hide()
        $('#ds_carga_horaria').hide()
        $('#ds_justificativa').hide()
        $('#ds_observacao').hide()
        $('#no_empresa').hide()
        $('#cnpj_empresa').hide()
        $('#telefone').hide()
        $('#ddd_telefone').hide()
        $('#email_empresa').hide()
        $('#local_evento').hide()
        $('#quantidade').hide()
        $('#valor_estimado').hide()
        $('#valor_diarias').hide()
        $('#valor_passagens').hide()
        $('#conta_debito').hide()
        $('#dt_inicio_licenca').hide()
        $('#dias_licenca').hide()
        $('#dt_fim_licenca').hide()

    }
    
});

//CHANGE de local evento
$(document).on('change', '#local_evento_options', function(e){
    if(this.value == 1){
        $('#valor_diarias').hide()
        $('#valor_passagens').hide()
        $('#conta_debito').hide()
        $('#inputAnexo').show()
    }else if(this.value == 2){
        $('#valor_diarias').show()
        $('#valor_passagens').show()
        $('#conta_debito').show()
    }else{
        $('#valor_diarias').hide()
        $('#valor_passagens').hide()
        $('#conta_debito').hide()
    }  
});






//Cadastro da licença interesse(soma dias)
var dataInicio = document.getElementById("dt_inicio_licenca_input");
var dataFinal = document.getElementById("dt_fim_licenca_input");


$(document).on('change', '#dias_licenca_input', function(e){
    var diasLicenca = $('#dias_licenca_input').val();
   
    var offset = new Date().getTimezoneOffset();
    var data = new Date(dataInicio.value);
    data.setMinutes(data.getMinutes() + offset);
    data.setDate(data.getDate() + parseInt(diasLicenca));

    dataFinal.value = data.toISOString().substring(0, 10);
});


/*//Eventos de Data
$(document).on('change', '[name="dt_inicio"]', function(e){

    var tipoSolicitacao = $('#tipoSolicitacao').val();
    var data = "";
    var soma = 0;
    //Licença Interesse ou Cessão
    if(tipoSolicitacao == 3 || tipoSolicitacao == 5){
        
        if($(this).val().length != 10){
            alert("Data Inválida");
        }else{
            //Licença Interesse
            if(tipoSolicitacao == 3){
                data = $(this).val().split("/");
                soma = parseInt(data[2]) + 5;
                $('[name="dt_fim"]').val(data[0]+"/"+data[1]+"/"+soma);
            }
        }
          
    }
   
});

$(document).on('change', '[name="dt_fim"]', function(e){

    var tipoSolicitacao = $('#tipoSolicitacao').val();
    var data = "";
    var soma = 0;
    //Licença Interesse 
    if(tipoSolicitacao == 3){
        
        if($(this).val().length != 10){
            alert("Data Inválida");
            $('[name="dt_fim"]').val('');
        }
          
    }
   
});*/


//SOLICITAÇÕES PESSOAIS
$(document).ready(function(e){

    //Escondendo Inputs
    $("#sq_anexo_lista").hide()
    $("#tipo_request").hide()
    

    $('#btnSolicitacaoPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarSolicitacao").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#resultadoConsulta").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    
});


//SOLICITAÇÕES GERAIS


$(document).ready(function(e){

    //Mascaras
    $('#data_inicio').mask('00/00/0000');
    $('#data_fim').mask('00/00/0000');
    $('[name="dt_inicio"]').mask('00/00/0000');
    $('[name="dt_fim"]').mask('00/00/0000');

    $('[name="dias_licenca_input"]').mask('0#');

    $('[name="data_inicio_curso"]').mask('00/00/0000');
    $('[name="dt_fim_curso"]').mask('00/00/0000');
    $('[name="ds_carga_horaria"]').mask('0#');
    $('[name="cnpj_empresa"]').mask('AA.AAA.AAA/AAAA-00', {
        translation: {
            'A': { pattern: /[A-Za-z0-9]/ }
        },
        transform: function(v) {
            return v.toUpperCase();
        }
    });

    $('[name="cnpj_empresa"]').on('input', function (){
        $(this).val($(this).val().toUpperCase());
    });
    $('[name="conta_debito"]').mask('0#');
    $('[name="quantidade"]').mask('0#');
    $('[name="telefone"]').mask('0#');
    $('[name="ddd_telefone"]').mask('0#');
    $('[name="valor_estimado"]').mask('00.000.000,00',{reverse: true});
    $('[name="valor_diarias"]').mask('00.000.000,00',{reverse: true});
    $('[name="valor_passagens"]').mask('00.000.000,00',{reverse: true});

    
    $('#btnSolicitacaoGeralPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarSolicitacaoGeral").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#resultadoConsulta").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });


});


//Açãoes Solicitações Pessoais
$(document).on('click', '#btnAbrirModalAprovacaoEnviar', function(e){

    let status = $(this).data('tipo-solicitacao');
   
    e.preventDefault();
    Swal.fire({
        title: 'Enviar para Aprovação',
        html: '<p><h6>'+status+'</h6>\n <hr>Deseja Enviar a Solicitação para Aprovação?</p>',
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Enviar',
    }).then((result) => {
        
        //REQUISIÇÃO
        if (result.value) {
            let url = $(this).data('href');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "PUT",
                url     : url,
                success : function(retorno) {
                    window.location.reload();
                }

            });
        }

        
    });
    
});


$(document).on('click', '#btnAbrirModalCancelar', function(e){

    let status = $(this).data('tipo-solicitacao');

    e.preventDefault();
    Swal.fire({
        title: 'Cancelar Solicitação',
        html: '<p><h6>'+status+'</h6>\n <hr>Tem certeza que deseja cancelar a solicitação ?</p>',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonText: 'Sim',
    }).then((result) => {

        //REQUISIÇÃO
        if (result.value) {
            let url = $(this).data('href');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "PUT",
                url     : url,
                success : function(retorno) {
                    window.location.reload();
                }

            });
        }
        
    });
    
});

//Açãoes Solicitações Gerais


$(document).on('click', '#btnAbrirModalAprovar', function(e){

    let status = $(this).data('tipo-solicitacao');

    //Mostra Upload
    var showInput = $('#showAnexo').val();
    var tipoSolicitacao = $('#tipoSolicitacao').val();
    if(showInput == "S"){
        var inputFile = '<input type="file" id="no_anexo" name="no_anexo" multiple>';
    }else{
        inputFile = ""
    }

    //Validação do Questionário (Somente CESEP pode responder/Editar e o Tipo ser Licença Interesse ou Cessão) 
    if( (showInput == "S" && tipoSolicitacao == 3) || (showInput == "S" && tipoSolicitacao == 5) ){
        if(questionarioValidacao() == 0){
            return false;
        }
    }
    

    e.preventDefault();
    Swal.fire({
        title: 'Aprovar Solicitação',
        html: '<p><h6>'+status+'</h6> \n Parecer:</p> <textarea class="swal2-textarea" id="parecer" maxlength="2000" name="parecer" style="display: flex;"></textarea> '+inputFile+' ',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonText: 'Aprovar',
    }).then((result) => {
        
        //REQUISIÇÃO
        var parecer = $('#parecer').val()

        if (parecer != "" && result.isConfirmed == true) {
            
            let url = $(this).data('href');

            var dataForm = new FormData()
            dataForm.append('idSolicitacao', $(this).data('id-solicitacao'))
            dataForm.append('idTipoSolicitacao',$(this).data('id-tipo-solicitacao'))
            dataForm.append('parecer',parecer)

            if(showInput == "S"){

                //Datas Original e Alterada para Licença Intersse e Cessão
                var dataInicio = $('[name="dt_inicio"]').val()
                var dataInicioOriginal = $('[name="dt_inicio_original"]').val()
                var dataFim = $('[name="dt_fim"]').val()
                var dataFimOriginal = $('[name="dt_fim_original"]').val()

                
                if(dataInicio == "" && (tipoSolicitacao == 3 || tipoSolicitacao == 5) ){
                    alert("Data Inicio Obrigatória");
                    return false;
                }

                if(dataFim == "" && tipoSolicitacao == 3 ){
                    alert("Data Fim Obrigatória");
                    return false;
                }

                
                if(dataInicio != "" && (tipoSolicitacao == 3 || tipoSolicitacao == 5) ){

                    
                    if(dataInicio.length != 10){
                        alert("Data Inicio Inválida");
                        return false;
                    }

                    dataForm.append('dt_inicio' , dataInicio)
                    dataForm.append('dt_inicio_original' , dataInicioOriginal)
                }

                if(dataFim != "" && tipoSolicitacao == 3 ){

                    if(dataFim.length != 10){
                        alert("Data Fim Inválida");
                        return false;
                    }

                    dataForm.append('dt_fim' , dataFim)
                    dataForm.append('dt_fim_original' , dataFimOriginal)
                }
                
               

                //Upload de Multiplos Arquivos (Somente pela CESEP)
                $.each($("input[type='file']")[0].files, function(i, file) {
                    dataForm.append('no_anexo[]', file);
                });

                //Questionario
                for (var i = 0; i < qtdPergunta; i++) {

                    var resposta = "";
                    if($("#resposta_"+i).is(":checked") == true){
                        resposta = "S";
                    }else{
                        resposta = "N";
                    }

                    

                    dataForm.append('respostas[]',resposta)
                    dataForm.append('observacao[]',$("#obs_"+i).val());
                   
                 }

            }
            
            
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type    : "POST",
                url     : url,
                processData: false,
                contentType: false,
                data    : dataForm,
                success : function(retorno) {
                    window.location.href = "/modulo-solicitacao/solicitacoes/caixa-entrada-gerais";
                }

            });
        }

    });
    
});


$(document).on('click', '#btnAbrirModalFinalizar', function(e){

    let status = $(this).data('tipo-solicitacao');

    //Mostra Upload
    var showInput = $('#showAnexo').val();
    if(showInput == "S"){
        var inputFile = '<input type="file" id="no_anexo" name="no_anexo" multiple>';
    }

    
    e.preventDefault();
    Swal.fire({
        title: 'Finalizar Solicitação',
        html: '<p><h6>'+status+'</h6> \n Parecer:</p> <textarea class="swal2-textarea" id="parecer" maxlength="2000" name="parecer" style="display: flex;"></textarea> '+inputFile+' ',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonText: 'Finalizar',
    }).then((result) => {
        
        //REQUISIÇÃO
        var parecer = $('#parecer').val()

        if (parecer != "" && result.isConfirmed == true) {
            
            let url = $(this).data('href');
  
            var dataForm = new FormData()
            dataForm.append('idSolicitacao', $(this).data('id-solicitacao'))
            dataForm.append('idTipoSolicitacao',$(this).data('id-tipo-solicitacao'))
            dataForm.append('parecer',parecer)

            if(showInput == "S"){
                
                //Upload de Multiplos Arquivos
                $.each($("input[type='file']")[0].files, function(i, file) {
                    dataForm.append('no_anexo[]', file);
                });
 
                if(status != 6){
                    //Questionario
                    for (var i = 0; i < qtdPergunta; i++) {

                        var resposta = "";
                        if($("#resposta_"+i).is(":checked") == true){
                            resposta = "S";
                        }else{
                            resposta = "N";
                        }
                        dataForm.append('respostas[]',resposta)
                        dataForm.append('observacao[]',$("#obs_"+i).val());
                    
                    }
                }

            }
            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "POST",
                url     : url,
                processData: false,
                contentType: false,
                data    : dataForm,
                success : function(retorno) {
                   window.location.href = "/modulo-solicitacao/solicitacoes/caixa-entrada-gerais";
                }

            });
        }

    });
    
});

$(document).on('click', '#btnAbrirModalConcluir', function(e){

    let status = $(this).data('tipo-solicitacao');
    
    //Mostra Upload
    var showInput = $('#showAnexo').val();
    if(showInput == "S"){
        var inputFile = '<input type="file" id="no_anexo" name="no_anexo" multiple>';
    }
      
    e.preventDefault();
    Swal.fire({
        title: 'Concluir Solicitação',
        html: '<p><h6>'+status+'</h6> \n Retorno Efetivo:</p> <input class="form-control" id="parecer" type="date" name="parecer" style="display: flex;"> ',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonText: 'Concluir',
    }).then((result) => {
        
        //REQUISIÇÃO
        var parecer = $('#parecer').val()

        if (parecer != "" && result.isConfirmed == true) {
            
            let url = $(this).data('href');
           
            var concluir = 1;

            var dataForm = new FormData()
            dataForm.append('idSolicitacao', $(this).data('id-solicitacao'))
            dataForm.append('idTipoSolicitacao',$(this).data('id-tipo-solicitacao'))
            dataForm.append('parecer',parecer)
            dataForm.append('concluir',concluir)

            if(showInput == "S"){
                
                //Upload de Multiplos Arquivos
                $.each($("input[type='file']")[0].files, function(i, file) {
                    dataForm.append('no_anexo[]', file);
                });
 
                if(status != 6){
                    //Questionario
                    for (var i = 0; i < qtdPergunta; i++) {

                        var resposta = "";
                        if($("#resposta_"+i).is(":checked") == true){
                            resposta = "S";
                        }else{
                            resposta = "N";
                        }
                        dataForm.append('respostas[]',resposta)
                        dataForm.append('observacao[]',$("#obs_"+i).val());
                    
                    }
                }

            }
            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "POST",
                url     : url,
                processData: false,
                contentType: false,
                data    : dataForm,
                success : function(retorno) {
                    window.location.href = "/modulo-solicitacao/solicitacoes/caixa-entrada-gerais";
                }

            });
        }

    });
    
});

$(document).on('click', '#btnAbrirModalProrrogar', function(e){

    let status = $(this).data('tipo-solicitacao');

    //Mostra Upload
    var showInput = $('#showAnexo').val();
    if(showInput == "S"){
        var inputFile = '<input type="file" id="no_anexo" name="no_anexo" multiple> <br/>';
    }

    var inputdate = '<input class="form-control" id="dt_solicitada" type="date" name="dt_solicitada" style="display: flex;"> ';
    var textobs = '<p><h6> Ao atualizar a data fim do afatamento essa solicitação será submetida à aprovação da SUAPE e DIPES. </h6></p>';
    
    e.preventDefault();
    Swal.fire({
        title: 'Prorrogar Solicitação',
        html: '<p><h6>'+status+'<p></h6> \n Data fim solicitada:</p>'+inputdate+' \n Parecer:</p> <textarea class="swal2-textarea" id="parecer" maxlength="2000" name="parecer" style="display: flex;"></textarea> '+inputFile+' '+textobs+' ',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonText: 'Prorrogar',
    }).then((result) => {
        
        //REQUISIÇÃO
        var parecer = $('#parecer').val()
        var dt_solicitada = $('#dt_solicitada').val()

        if (parecer != "" && dt_solicitada != "" && result.isConfirmed == true) {
            
            let url = $(this).data('href');

            var concluir = 0;
           
            var dataForm = new FormData()
            dataForm.append('idSolicitacao', $(this).data('id-solicitacao'))
            dataForm.append('idTipoSolicitacao',$(this).data('id-tipo-solicitacao'))
            dataForm.append('parecer',parecer)
            dataForm.append('dt_solicitada',dt_solicitada)
            dataForm.append('concluir',concluir)

            if(showInput == "S"){
                
                //Upload de Multiplos Arquivos
                $.each($("input[type='file']")[0].files, function(i, file) {
                    dataForm.append('no_anexo[]', file);
                });
 
                if(status != 6){
                    //Questionario
                    for (var i = 0; i < qtdPergunta; i++) {

                        var resposta = "";
                        if($("#resposta_"+i).is(":checked") == true){
                            resposta = "S";
                        }else{
                            resposta = "N";
                        }
                        dataForm.append('respostas[]',resposta)
                        dataForm.append('observacao[]',$("#obs_"+i).val());
                    
                    }
                }

            }
            

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "POST",
                url     : url,
                processData: false,
                contentType: false,
                data    : dataForm,
                success : function(retorno) {
                  window.location.href = "/modulo-solicitacao/solicitacoes/caixa-entrada-gerais";
                }

            });
        }

    });
    
});


$(document).on('click', '#btnAbrirModalDevolver', function(e){

    let status = $(this).data('tipo-solicitacao');

    //Mostra Upload
    var showInput = $('#showAnexo').val();
    if(showInput == "S"){
        var inputFile = '<input type="file" id="no_anexo" name="no_anexo" multiple>';
    }else{
        inputFile = ""
    }
    
    e.preventDefault();
    Swal.fire({
        title: 'Devolver Solicitação',
        html: '<p><h6>'+status+'</h6> \n Parecer:</p> <textarea class="swal2-textarea" id="parecer" maxlength="2000" name="parecer" style="display: flex;"></textarea> '+inputFile+' ',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonText: 'Devolver',
    }).then((result) => {
        
        //REQUISIÇÃO
        var parecer = $('#parecer').val()

        if (parecer != "" && result.isConfirmed == true) {
            
            let url = $(this).data('href');

            var dataForm = new FormData()
            dataForm.append('idSolicitacao', $(this).data('id-solicitacao'))
            dataForm.append('parecer',parecer)

            if(showInput == "S"){
                //Upload de Multiplos Arquivos
                $.each($("input[type='file']")[0].files, function(i, file) {
                    dataForm.append('no_anexo[]', file);
                });
            }


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "POST",
                url     : url,
                data    : dataForm,
                processData: false,
                contentType: false,
                success : function(retorno) {
                    
                    window.location.href = "/modulo-solicitacao/solicitacoes/caixa-entrada-gerais";
                }

            });
        }
        
    });
    
});



function questionarioValidacao(){

    var retorno = 1;
    for (var i = 0; i < qtdPergunta; i++) {
        if($("input[name='resposta_"+i+"']:checked").length == 0){
            retorno = 0;
        }
     }

     if(retorno == 0){
        alert("Responda todas as perguntas.")
        return false;
     }

}


 







