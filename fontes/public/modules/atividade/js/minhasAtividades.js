$(document).ready(function(e){

    
    $(function() {
        $(".dial").knob(
            {
                "fgColor":"#007bff",
            }
    
        );
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

    
   //SELECT USUARIOS (Personalizar Combo Somente quando for Cadastro de Atividade)
   $('select[name="nr_matricula[]"]').bootstrapDualListbox({
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


$('#dt_prazo_atividade').datepicker(traducao_datepicker).mask('00/00/0000');


//Caso Venha sem Filtro, já pesquisar
if($("input[name=ciclo_especifico]").val() == 1){    
    let url = $('#btnMinhasAtividadePesquisar').data('href');
    retornaMinhasAtividades(url);
}

$('#btnMinhasAtividadePesquisar').click(function(){
    let url = $(this).data('href');
    retornaMinhasAtividades(url);
});

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

});

$(document).on('click', '#btn-ver-mensagens-atividade', function(e){
    $('#modal-mensagem-atividade').modal('show');   
});

$(document).on('click', '#btn-visualizar-porcentagem', function(e){
    $('#example-modal-visualizar-porcentagem').modal('show');   
});


function retornaMinhasAtividades(url){

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
                },'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': [-1] 
                }]
                
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

}

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
            $.each(retorno, function (key, value) {
                var data = value.dt_prazo;
                var dataFormatada = data.replace(/(\d*)-(\d*)-(\d*).*/,'$3/$2/$1');
                $('#dt_prazo_atividade').val(dataFormatada);
            });
        }
    });
}

//Reset da Pesquisa
$("[type='reset']").click(function(){
    $('#sq_ciclo_avaliativo').val(null).trigger('change');
    $('#nr_matricula').val(null).trigger('change');
    $('#sq_meta').val(null).trigger('change');
    $('#cd_situacao_atividade').val(null).trigger('change');
});

$('#sq_meta').on('change', function(e){
    var urlMenuPrazo = $(this).attr('data-href-meta');
    loadMenuPaiPrazo($(this).val(), urlMenuPrazo);
});



