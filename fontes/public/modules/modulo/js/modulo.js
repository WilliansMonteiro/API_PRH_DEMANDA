$(document).ready(function(e){

    const url_estatica = $('#url_estatica')
    const tipo_modulo = $('#cd_tipo_modulo');
    const descricao_url = $('#ds_url');


    //VALIDAÇÃO DE CLASSE ESTÁTICA
    if(url_estatica.hasClass('has-error') || (tipo_modulo.val() == 2)){
        url_estatica.show();
    }else{
        url_estatica.hide();
        descricao_url.val('');
    }



    $('#cd_modulo').mask('00');

    $('#btnModuloPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarModulo").serializeArray(),
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
                        'aTargets': [-1]
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

    $(document).on('click', '#btnModuloExcluir', function(e){
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


    tipo_modulo.change(function(e){

        //NOVO MÓDULO
        if(e.target.value == '2'){
            url_estatica.show();
        }else{
            url_estatica.hide();
            descricao_url.val('');
        }

    });

});