(function(){

    function init(){
        events();
    }

    function events(){
        $(document).on('click', '#btnAvaliacaoPesquisar', searchAvaliacao);
        $(document).on('click', '.btnMembroEquipe', loadAvaliacao);
    }

    function searchAvaliacao()
    {
        var url = $(this).attr('data-href');
        var urlShow = $(this).attr('data-href-show');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "POST",
            url		: url,
            data	: {
                nr_matricula: $("#nr_matricula").val(),
                sq_ciclo_avaliativo: $("#sq_ciclo_avaliativo").val()
            },
            success	: function(retorno) {

                if(retorno.status == true){
                    $("#tbEmpregados tbody>tr").remove();
                    $.each(retorno.data, function(key,value){
                        var index = key + 1;
                        var row = ['<tr>'];
                        row.push('<td>' + index + '</td>');
                        row.push('<td>' + 'u' + value.nr_matricula + ' - ' + value.no_usuario + '</td>');
                        row.push('<td>' + value.ds_ciclo_avaliativo + '</td>');
                        row.push('<td align="center">' + value.percentual_conclusao + '%</td>');
                        if(value.percentual_conclusao  == '0.00'){
                            row.push('<td><button class="btn btn-small btn-primary" disabled="disabled" ><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Visualizar Formulário"></i></button></td>');
                        }else{
                            row.push('<td><a class="btn btn-small btn-primary" href="'+urlShow+'/'+value.nr_matricula+'/'+value.sq_ciclo_avaliativo+'"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Visualizar Formulário"></i></a></td>');
                        }
                        row.push('</tr>');
                        $(['#tbEmpregados tbody'].join('')).append(row.join(''));
                    });
                    $('#tbEmpregados').DataTable({
                        "language": {
                            "lengthMenu": "Mostrando _MENU_ registros por página",
                            "zeroRecords": "Nada encontrado",
                            "info": "Mostrando página _PAGE_ de _PAGES_",
                            "infoEmpty": "Nenhum registro disponível",
                            "infoFiltered": "(filtrado de _MAX_ registros no total)"
                        }
                    });
                }else{
                    var row = ['<tr>'];
                    row.push('<td align="center" colspan="5">Nenhum Registro encontrado!</td>');
                    row.push('</tr>');
                    $(['#tbEmpregados tbody'].join('')).append(row.join(''));
                }
            },beforeSend: function() {
                $('#tbEmpregados').DataTable().clear().destroy();
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                var row = ['<tr>'];
                row.push('<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tbEmpregados tbody'].join('')).append(row.join(''));
            },complete: function(){
                $('#carregar').html("");
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    }

    function loadAvaliacao(){
        $(this).removeClass('btnMembroEquipe');
        var divRetorno   = $(this).attr('data-div-retorno');
        var nr_matricula = $(this).attr('data-matricula') ? $(this).attr('data-matricula') : $('#membroEquipe0').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "GET",
            url		: '/modulo-avaliacao/avaliacao/loadAvaliacao',
            dataType : 'html',
            data	: {
                nr_matricula: nr_matricula,
                sq_membro_equipe_avaliador: $(this).attr('data-sqMembroEquipe'),
                cd_tipo_avaliacao: $(this).attr('data-tipo-avaliacao'),
                sq_ciclo_avaliativo: $(this).attr('data-ciclo-avaliativo'),
                tp_modulo: '2'
            },
            success	: function(retorno) {
                $("#"+divRetorno).html('');
                $("#"+divRetorno).html(retorno);
                $(':radio:not(:checked)').attr('disabled', true);
            },
            beforeSend: function() {
                $("#"+divRetorno).html('<img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO');
            }
        });
    }

    init();
})()

//Reset da Pesquisa
$("[type='reset']").click(function(){
    $('#sq_ciclo_avaliativo').val(null).trigger('change');
    $('#nr_matricula').val(null).trigger('change');
});