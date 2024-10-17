$(document).ready(function(e){
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


    $('#an_referencia_ciclo_avaliativo').mask('0000');
    $('#nr_periodo_referencia_cic_aval').mask('00');
    $('#dt_inicio_periodo_pre_apuracao').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_pre_apuracao').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_inicio_periodo_avaliativo').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_avaliativo').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_inicio_periodo_apuracao').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_apuracao').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_inicio_periodo_feedback').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_feedback').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_inicio_periodo_acordo_atvd').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_acordo_atvd').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_inicio_periodo_acomp_atvd').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_acomp_atvd').datepicker(traducao_datepicker).mask('00/00/0000');

    $('#dt_inicio_periodo_avaliativo_pesquisa').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_fim_periodo_avaliativo_pesquisa').datepicker(traducao_datepicker).mask('00/00/0000');

    $('#btnCicloAvaliativoPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarCicloAvaliativo").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#myTable").DataTable({
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
                        'aTargets': [-1,-2]
                    }]
                });
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

    $(document).on('click', '#btnCicloAvaliativoExcluir', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
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

    $(document).on('click', '#cd_perspectiva', function(){
        if ($(this).is(':checked')){
            $('#div_perspectiva_escala_'+$(this).val()).show();
        } else{
            $('#div_perspectiva_escala_'+$(this).val()).hide();
            $('#div_perspectiva_escala_'+$(this).val()).find('select').val(0);
        }
    });
});

//Reset da Pesquisa
$("[type='reset']").click(function(){
    $('#cd_tipo_ciclo_avaliativo').val(null).trigger('change');
});