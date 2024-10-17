$(document).ready(function(){

    //Escondendo Campos do Select2
    $("#funcoes_hidden > span[class='select2 select2-container select2-container--bootstrap4']").hide()
    $("#cargos_hidden > span[class='select2 select2-container select2-container--bootstrap4']").hide()

    // jQuery Steps
    var form = $('#formCreateFormularioAvaliacao360').show();
    $('#wizard').steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        enableKeyNavigation: false,
        enableCancelButton: true,
        labels: {
            cancel: 'Cancelar',
            next: 'Próximo',
            previous: 'Anterior',
            finish: 'Salvar',
            loading: 'Carregando...',
        },
        onCanceled: function (event) {
            window.location.href = '/modulo-avaliacao/formularios-avaliacao';
        },
        onStepChanging: function(event, currentIndex, newIndex){
            if ($('#sq_ciclo_avaliativo').find('option:selected').val() == 0){
                Swal.fire({
                    icon: 'error',
                    title: '',
                    text: 'Selecione um ciclo avaliativo.',
                });
                return false;
            }
            if ($('#table_natureza_funcao > tbody > tr').length == 0){
                Swal.fire({
                    icon: 'error',
                    title: '',
                    text: 'Pelo menos 1 registro deve ser adicionado.',
                });
                return false;
            }

            if (currentIndex > newIndex){
                return true;
            }
            if (currentIndex < newIndex){
                form.find(".body:eq("+newIndex+") label.error").remove();
                form.find(".body:eq("+newIndex+") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onStepChanged: function(event, currentIndex, newIndex){},
        onFinishing: function(event, currentIndex){
            form.validate({
                lang: 'pt-BR',
            }).settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex){
            form[0].submit();
        }
    });
    var result = $('ul[aria-label=Pagination]').children().find('a');
    $(result).each(function(){
        if ($(this).text() == 'Salvar'){
            $(this).css('background', '#55a846');
        }
        if ($(this).text() == 'Cancelar'){
            $(this).css('background', '#dc3c45')
        }
    });

    // SELECT FUNÇÕES
   var select_1 = $('select[name="passo1_no_funcao_usuario"]').bootstrapDualListbox({
        filterTextClear: 'mostrar tudo',
        filterPlaceHolder: 'Filtro',
        moveSelectedLabel: 'Mover selecionados',
        moveAllLabel: 'Mover todos',
        removeSelectedLabel: 'Remover selecionados',
        removeAllLabel: 'Remover todos',
        infoText: 'Mostrando todos {0}',
        infoTextFiltered: '<span class="label label-warning">Filtrando</span> {0} de {1}',
        infoTextEmpty: 'Lista vazia',
        moveOnSelect: false
    });

    // SELECT CARGOS
    var select_2 = $('select[name="passo1_no_cargo_usuario"]').bootstrapDualListbox({
        filterTextClear: 'mostrar tudo',
        filterPlaceHolder: 'Filtro',
        moveSelectedLabel: 'Mover selecionados',
        moveAllLabel: 'Mover todos',
        removeSelectedLabel: 'Remover selecionados',
        removeAllLabel: 'Remover todos',
        infoText: 'Mostrando todos {0}',
        infoTextFiltered: '<span class="label label-warning">Filtrando</span> {0} de {1}',
        infoTextEmpty: 'Lista vazia',
        moveOnSelect: false
    });

    // Passo 1
    $(document).on('click', 'input[name="passo1_cd_lideranca"]', function(){
        $('#div_tipo_lideranca').hide();
        if ($(this).val() === 'S'){
            $('#div_tipo_lideranca').show();
        }
    });

    var arr_dados_session_php_json = {};
    var arr_dados = [];
    arr_dados_session_php_json.arr_dados = arr_dados;
    var linha = $('#table_natureza_funcao > tbody > tr').length-1;
    var funcoes = null;
    var cdFuncoes = null;
    var cargos = null;
    var cdCargos = null;
    var cd_lideranca = null;
    var ds_lideranca = null;
    var cd_tipo_lideranca = null;
    var ds_tipo_lideranca = null;

    $(document).on('click', '#btnAddNaturezaAtividadeFuncao', function(e) {
        e.preventDefault();
        
        
        $('button[class="btn btn-sm clear1"]').trigger('click');
        

        funcoes = $('select#bootstrap-duallistbox-selected-list_passo1_no_funcao_usuario option').map(function(){
            return $(this).text();
        }).get().join(", ");

        cdFuncoes = $('select#bootstrap-duallistbox-selected-list_passo1_no_funcao_usuario option').map(function(){
            //$('select#bootstrap-duallistbox-selected-list_passo1_no_funcao_usuario option').remove();
            return $(this).val();
        }).get().join(", ");

        cargos = $('select#bootstrap-duallistbox-selected-list_passo1_no_cargo_usuario option').map(function(){
            return $(this).text();
        }).get().join(", ");

        cdCargos = $('select#bootstrap-duallistbox-selected-list_passo1_no_cargo_usuario option').map(function(){
            //$('select#bootstrap-duallistbox-selected-list_passo1_no_cargo_usuario option').remove();
            return $(this).val();
        }).get().join(", ");

       
        //Obrigatoriedade de Ciclo Avaliativo
        if($('select[name="sq_ciclo_avaliativo"]').val() == ""){
            Swal.fire({
                icon: 'warning',
                title: '',
                text: 'Selecione um Ciclo Avaliativo.',
            });
            return;
        }
        
        
        //Obrigatoriedade de Lider
        if($('input[name="passo1_cd_lideranca"]:checked').val() == undefined){
            Swal.fire({
                icon: 'warning',
                title: '',
                text: 'Selecione uma Liderança.',
            });
            return;
        }

        //Obrigatoriedade de Tipo Liderança
        if($('input[name="passo1_cd_lideranca"]:checked').val() == 'S'){
            if($('input[name="passo1_cd_tipo_lideranca"]:checked').val() == undefined){
                Swal.fire({
                    icon: 'warning',
                    title: '',
                    text: 'Selecione um Tipo de Liderança.',
                });
                return;
            }
        }

        //Obrigatóriedade de Cargo ou Função
        if ((funcoes.length === 0) && (cargos.length === 0)){
            Swal.fire({
                icon: 'warning',
                title: '',
                text: 'Selecione pelo menos uma Função ou Cargo.',
            });
            return;
        }

        //Recriando Select Principal de Funções
        recriandoSelectFuncoes(select_1)
        //Recriando Select Principal de Cargos
        recriandoSelectCargo(select_2)
        

        linha = linha + 1;

        cd_lideranca = $('input[name="passo1_cd_lideranca"]').is(':checked') ? $('input[name="passo1_cd_lideranca"]:checked').data('value') : '';
        ds_lideranca = $('input[name="passo1_cd_lideranca"]').is(':checked') ? $('input[name="passo1_cd_lideranca"]:checked').data('label') : '';
        cd_tipo_lideranca = $('input[name="passo1_cd_tipo_lideranca"]').is(':checked') ? $('input[name="passo1_cd_tipo_lideranca"]:checked').data('value') : '';
        ds_tipo_lideranca = $('input[name="passo1_cd_tipo_lideranca"]').is(':checked') ? $('input[name="passo1_cd_tipo_lideranca"]:checked').data('label') : '';

        $('input[name="passo1_cd_lideranca"]').prop('checked', false);
        $('input[name="passo1_cd_tipo_lideranca"]').prop('checked', false);

        arr_dados_session_php_json.sq_ciclo_avaliativo = $('#sq_ciclo_avaliativo').val();
        arr_dados_session_php_json.arr_dados.push(
            {
                'cdFuncoes': cdFuncoes,
                'cdCargos' : cdCargos,
                'cd_lideranca': cd_lideranca,
                'cd_tipo_lideranca': cd_tipo_lideranca
            }
        );

        $('#table_natureza_funcao').find('tbody:last').append(
            '<tr id="table_natureza_funcao_tr_'+linha+'">\r\n\
                <td style="display:none;">'+cdFuncoes+'</td>\r\n\
                <td>'+funcoes+'</td>\r\n\
                <td style="display:none;">'+cdCargos+'</td>\r\n\
                <td>'+cargos+'</td>\r\n\
                <td>'+ds_lideranca+'</td>\r\n\
                <td>'+ds_tipo_lideranca+'</td>\r\n\
                <td>\r\n\
                    <button type="button" class="btn btn-fill btn-danger" id="btnDeleteNaturezaAtividadeFuncao">\r\n\
                        <i class="fas fa-trash"></i>\r\n\
                    </button>\r\n\
                </td>\r\n\
            </tr>'
        );

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type    : "POST",
            url     : $(this).data('href'),
            data    : arr_dados_session_php_json,
            success : function(retorno) {
                console.log(retorno);
            }

        });
    });
    $(document).on('click', '#btnDeleteNaturezaAtividadeFuncao', function(e){
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
                let id = $(this).closest('tr').attr('id');
                let deleteIndex = Number(id.split('_')[4]);
                let url = $(this).data('href');
                let tr = $('#table_natureza_funcao_tr_'+deleteIndex);
                //Funções
                let td_funcoes_ids = tr.find("td").eq(0).html();
                let td_funcoes_nome = tr.find("td").eq(1).html();
                //Cargos
                let td_cargos_ids = tr.find("td").eq(2).html();
                let td_cargos_nome = tr.find("td").eq(3).html();
                
                funcoes_id = td_funcoes_ids.split(',');
                funcoes_nome = td_funcoes_nome.split(',');

                cargos_id = td_cargos_ids.split(',');
                cargos_nome = td_cargos_nome.split(',');

    
                
                //Laço para Função
                for (let index = 0; index < funcoes_id.length; index++) {
                    if(funcoes_id[index] != ""){
                        reAdicionandoFuncao(select_1,funcoes_id[index],funcoes_nome[index])
                    }
                    
                }

                //Laço para Cargo
                for (let index = 0; index < cargos_id.length; index++) {
                    if(cargos_id[index] != ""){
                        reAdicionandoCargo(select_2,cargos_id[index],cargos_nome[index])
                    }
                }

                //Caso seja Criação de Formulário, interrompe a execução do Script
                if (typeof url === 'undefined'){
                    tr.remove();
                    return;
                }
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "DELETE",
                    url: url,
                    success: function(retorno) {
                        console.log(retorno);
                        $('#table_natureza_funcao_tr_'+deleteIndex).remove();
                        window.location.reload();
                    }
                });
            }
        });
    });

    // Passos 2, 3, 4 e 5
    var lowerBound = 0;
    var upperBound = 100;

    $('.pct').mask('000');
    $('.pct').prop({'min': lowerBound, 'max': upperBound});

    $('.pct').keyup(function(){
        if (parseInt($(this).val()) < lowerBound)
            $(this).val(lowerBound);
        if (parseInt($(this).val()) > upperBound)
            $(this).val(upperBound);
    });
});

//Limpando Tipo de Lidernaça ao Mudar a Liderança
$(document).on('change', 'input[name="passo1_cd_lideranca"]', function(e) {
    $('input[name="passo1_cd_tipo_lideranca"]').prop('checked', false);
});


//Recriação do Input Esquerdo
function recriandoSelectFuncoes(select_1){
    select_1.find('option').remove();
    funcoes = $('select#bootstrap-duallistbox-nonselected-list_passo1_no_funcao_usuario option').map(function(){
        select_1.append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
    }).get();

    select_1.bootstrapDualListbox('refresh');
}

function recriandoSelectCargo(select_2){
    select_2.find('option').remove();
    funcoes = $('select#bootstrap-duallistbox-nonselected-list_passo1_no_cargo_usuario option').map(function(){
        select_2.append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
    }).get();

    select_2.bootstrapDualListbox('refresh');
}

//Voltando Itens Excluidos para Listagem
function reAdicionandoFuncao(select_1,id_funcao,nome_funcao){
    select_1.append('<option value="'+id_funcao+'">'+nome_funcao+'</option>');
    select_1.bootstrapDualListbox('refresh');
}

function reAdicionandoCargo(select_2,id_cargo,nome_cargo){
    select_2.append('<option value="'+id_cargo+'">'+nome_cargo+'</option>');
    select_2.bootstrapDualListbox('refresh');
}

