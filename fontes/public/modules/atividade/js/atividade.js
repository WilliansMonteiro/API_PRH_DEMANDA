
$(document).ready(function(e){

    $(function() {
        $(".dial").knob(
            {
                "fgColor":"#007bff",
            }
    
        );
    });

    



    $("#table-atividades").DataTable({
        language: {
            filter: "Pesquisar",
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
        },'aoColumnDefs': [{
            'bSortable': false,
            'aTargets': [-1] 
        }]
    });

    $("#usuario_hidden > span[class='select2 select2-container select2-container--bootstrap4']").hide()

    const traducao_datepicker = {
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        minDate: "-28m",
        maxDate: "+28m",
        changeMonth: true,
        changeYear: true,
    };

    // SELECT USUARIOS (Personalizar Combo Somente quando for Cadastro de Atividade)
    if($('#isCadastro').val() == 1){
        var select_1 = $('select[name="nr_matricula[]"]').bootstrapDualListbox({
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
    }

    $('#dt_prazo_atividade').datepicker(traducao_datepicker).mask('00/00/0000');

    $('#btnAtividadePesquisar').click(function(){
      
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarAtividade").serializeArray(),
            success	: function(retorno) {
                
                $('#retorno').html(retorno);
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
                    }
                    
                });
                $("#resultadoConsulta").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                console.log('funcionou');
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });


    //BOTÃO DE EXCLUSÃO DE ATIVIDADE
    $(document).on('click', '#btnAtividadeExcluir', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja cancelar esta atividade?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
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
                    }

                });
            }
        });
    });

    //BOTÃO DE EXCLUSÃO DE RESPOSÁVEL
    $(document).on('click', '#btnResponsavelExcluir', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja exluir este responsável?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
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
                        window.location.reload();
                    }

                });
            }
        });
    });

    $(document).on('click', '#btn-atividade-aprovar', function(e){
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
                $('#input-atividade-aprovar').val(retorno.data);   
                $('#modal-aprovar-atividade').modal('show');
            }
        });

           
    });

    
    $(document).on('click', '#btn-atividade-rejeitar', function(e){
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
                $('#input-atividade-rejeitar').val(retorno.data);   
                $('#modal-rejeitar-atividade').modal('show');
            }
        });        
    });

    
    $(document).on('click', '#btn-mensagem-atividade', function(e){
        $('#example-modal-mensagem').modal('show');   
    });
    
    $(document).on('click', '#btn-historico-atividade', function(e){
        $('#modal-historico-tramitacao').modal('show');   
    });

    $(document).on('click', '#btn-ver-mensagens-atividade', function(e){
        $('#modal-mensagem-atividade').modal('show');   
    });

    

    
    $(document).on('click', '#btn-ver-porcentagem', function(e){
        $('#example-modal-porcentagem').modal('show');   
    });

    
    $(document).on('click', '#btn-visualizar-porcentagem', function(e){
        $('#example-modal-visualizar-porcentagem').modal('show');   
    });



    $(document).on('click', '#btnAddEmpregadoResponsavelAtividade', function(e){
        e.preventDefault();
        let nr_matricula = $('#nr_matricula option:selected').val();
        let no_responsavel = $('#nr_matricula option:selected').text();
        if (nr_matricula === ''){
            Swal.fire({
                icon: 'error',
                title: '',
                text: 'Selecione um responsável',
            });
            return;
        }

        //Valida se o Responsavel já esta na lista
        var lista = $('#lista_empregados_responsaveis').find('.row');
        for (let index = 0; index < lista.length; index++) {
            if(lista[index].id == nr_matricula){
                alert("Responsavel já consta na lista de Responsáveis.")
                return;
            }
        }

       
        $('#lista_empregados_responsaveis').append('\r\n\
            <div id="'+nr_matricula+'" class="row">\r\n\
                <div class="col-md-7">\r\n\
                    <label>u'+nr_matricula+' - '+no_responsavel+'</label>\r\n\
                </div>\r\n\
                <div class="col-md-7">\r\n\
                    <textarea name="ds_responsavel['+nr_matricula+']" class="form-control" rows="3"></textarea>\r\n\
                </div>\r\n\
                <div class="col-md-2">\r\n\
                    <button type="button" class="btn btn-danger" id="btnDeleteEmpregadoResponsavelAtividade" data-nr_matricula="'+nr_matricula+'"><i class="fas fa-trash"></i></button>\r\n\
                </div>\r\n\
            </div>\r\n\
        ');


        $('#nr_matricula').val(null).trigger('change');


    });

    $(document).on('click', '#botao', function(e){
        e.preventDefault();
        console.log('ola');
    });
    
    
    $(document).on('click', '#btnDeleteEmpregadoResponsavelAtividade', function(e){
        e.preventDefault();
        let nr_matricula = $(this).data('nr_matricula');
        let url = $(this).data('href');
        if (typeof url !== typeof undefined && url !== false) {
            Swal.fire({
                title: 'Tem certeza que deseja excluir?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Não',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
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
                                $('#'+nr_matricula).remove();
                                window.location.reload();
                        }

                    });
                }
            });
        }
       
        $('#'+nr_matricula).remove();
    });


    $('#sq_meta').on('change', function(e){
        var urlMenuPrazo = $(this).attr('data-href-meta');
        loadMenuPaiPrazo($(this).val(), urlMenuPrazo);
    });

    function loadMenuPaiPrazo(cdMeta, url) {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sq_meta: cdMeta
            },
            beforeSend: function () {
                $('#dt_prazo_atividade').html('Carregando...');
            },
            complete: function () {
                $('#carregar').html("");
            },
            success: function (retorno) {
                $('#dt_prazo_atividade').html('Carregando...')
                    .find('input')
                    .remove()
                    .end();
                //$('#dt_prazo_atividade').val(retorno['dt_prazo']);
                //$('#dt_prazo_atividade').val($(retorno).get(0));
                //alert(retorno);
                $.each(retorno, function (key, value) {
                    var data = value.dt_prazo;
                    var dataFormatada = data.replace(/(\d*)-(\d*)-(\d*).*/,'$3/$2/$1');
                    $('#dt_prazo_atividade').val(dataFormatada);
                });
            }
        });
    }


    $('#sq_ciclo_avaliativo').on('change', function(e){
        var urlCicloMenu = $(this).attr('data-href-ciclo-avaliativo');
        loadMenuPai($(this).val(), urlCicloMenu);
    });

    function loadMenuPai(cdCiclo, url) {
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sq_ciclo_avaliativo: cdCiclo
            },
            beforeSend: function () {
                $('#sq_meta').html('Carregando...');
            },
            complete: function () {
                $('#carregar').html("");
            },
            success: function (retorno) {
                $('#sq_meta').html('Carregando...')
                    .find('option')
                    .remove()
                    .end();
                $('#sq_meta').append('<option value="">Selecione</option>');
                $.each(retorno, function (key, value) {
                    $('#sq_meta').append('<option value="' + value.sq_meta + '">' + value.ds_meta + '</option>');
                });
            }
        });
    }

    $(document).ready(function() {
        $("*[name='cd_tipo_atividade']").on("change", function() {
            var chosen = ($(this).attr('value'));
            if(chosen == 1){
                $("#div_meta").show();
            }else{
                $("#div_meta").hide();
                $('#sq_meta').empty(); 
            }   
        });
    });
});

//Reset da Pesquisa
$("[type='reset']").click(function(){
    $('#sq_ciclo_avaliativo').val(null).trigger('change');
    $('#nr_matricula').val(null).trigger('change');
    $('#sq_meta').val(null).trigger('change');
    $('#cd_situacao_atividade').val(null).trigger('change');
});

//Incluir Atividade
$('#btn-incluir-atividade').on('click', function(e){
    var existeDiv = $('#lista_empregados_responsaveis');
    var lista = $('#lista_empregados_responsaveis').find('.row');
    if(lista.length == 0 && existeDiv.length){
        e.preventDefault();
        swal.fire({
            icon: "warning",
            text: 'Inclua pelo menos um responsável!'
        });
        return;
    }
});

//Adiciona a Meta e Data na Modal e Atribuindo Valor Meta a Campo Hidden

$(document).on('click',"#AscEquipe", function(e){
    $('#sq_meta').val($(this).parent().parent().find('td:eq(0)').html())
    $('#meta-escolhida').html("Meta:"+$(this).parent().parent().find('td:eq(1)').html())
    $('#dt_prazo_atividade').val($(this).parent().parent().find('td:eq(2)').html())
});

//Inclusão de Atividade Sem Meta

$(document).on('click',"#AscSemMeta", function(e){
    limparCampo()
});

$('#inserirAtividadeModal').on('hidden.bs.modal', function () {
    limparCampo()
});

function limparCampo(){
    $('#meta-escolhida').html("")
    $('#dt_prazo_atividade').val("")
    $('#ds_atividade').val("")
    $('#sq_meta').val("");
    $("input[type=radio][name=cd_ponto_controle]").prop('checked', false);
}
