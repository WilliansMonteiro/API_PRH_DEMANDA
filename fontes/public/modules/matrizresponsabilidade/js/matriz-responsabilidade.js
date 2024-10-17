(function(){

    function init(){
        events();
    }

    $(document).on('click', "td:last-child > input[type='checkbox']", function(e){
        if($(this).is(":checked")){
            $(this).parent().parent().find("input[type='checkbox']").prop("checked", true)
        }else{
            $(this).parent().parent().find("input[type='checkbox']").prop("checked", false)
        }
    });

    function events(){
        $(document).on('click', '#btnMatrizResponsabilidadePesquisar', pesquisar);
        $(document).on('click', '#btnMatrizResponsabilidadeExcluir', excluir);
    }

    function pesquisar(e){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarMatrizResponsabilidade").serializeArray(),
            success	: function(retorno) {

                populateTableMatrizResponsabilidade(retorno);
            },
            beforeSend: function() {
                $('#tbMatrizResponsabilidade').DataTable().clear().destroy();
                var row = ['<tr>'];
                row.push('<td align="center" colspan="5"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
                row.push('</tr>');
                $(['#tbMatrizResponsabilidade tbody'].join('')).append(row.join(''));
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function(){
                $('#carregar').html("");
            },
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    }

    function populateTableMatrizResponsabilidade(retorno){
        $("#tbMatrizResponsabilidade tbody>tr").remove();
        $.each(retorno.data, function(key,value){
            console.log( value.escala.ds_escala);
            var visualizar  = '<a class="btn btn-small btn-primary" href="/modulo-avaliacao/administrativo/matrizes/matriz-responsabilidade/visualizar/'+value.sq_matriz_responsabilidade+'"><i class="fas fa-folder" data-toggle="tooltip" data-placement="top" title="Visualizar Ciclo Avaliativo"></i></a>&nbsp;';
            var editar      = '<a class="btn btn-small btn-info" href="/modulo-avaliacao/administrativo/matrizes/matriz-responsabilidade/editar/'+value.sq_matriz_responsabilidade+'"><i class="fas fa-pencil-alt" data-toggle="tooltip" data-placement="top" title="Editar Matriz Responsabilidade"></i></a>&nbsp;';
            var inativar    = '<a class="btn btn-small btn-danger" href="" id="btnMatrizResponsabilidadeExcluir" data-href="/modulo-avaliacao/administrativo/matrizes/matriz-responsabilidade/excluir/'+value.sq_matriz_responsabilidade+'"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Inativar Matriz Responsabilidade"></i></a>';

            var row = ['<tr>'];
            row.push('<td class="text-center">' + value.sq_matriz_responsabilidade + '</td>');
            row.push('<td>' + value.ds_matriz_responsabilidade + '</td>');
            row.push('<td>' + value.ciclo.ds_ciclo_avaliativo  + '</td>');
            row.push('<td>' + value.escala.ds_escala + '</td>');
            row.push('<td class="project-actions text-center">' + visualizar + editar + inativar +'</td>');
            row.push('</tr>');
            $(['#tbMatrizResponsabilidade tbody'].join('')).append(row.join(''));
        });
        $('#tbMatrizResponsabilidade').DataTable({
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

    function excluir(e){
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
        }),
        Swal.then((result) => {
            if (result.value) {
            var url = $(this).data('href');
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
    }


    init();

})();

