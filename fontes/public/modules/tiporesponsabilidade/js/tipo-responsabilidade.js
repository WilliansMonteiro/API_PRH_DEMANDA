$(document).ready(function(e){
    $('#btnTipoResponsabilidadePesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarTipoResponsabilidade").serializeArray(),
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

    $(document).on('click', '#btnTipoResponsabilidadeExcluir', function(e){
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

    $(document).on('click', '#btnItemResponsabilidadeCriar', function(e){
        e.preventDefault();
        let lastItem = $('.item:last').attr('id');
        let nextIndex = Number(lastItem.split('_')[1])+1;

        $('.item:last').after('<div class="row item" id="responsabilidade_'+nextIndex+'"></div>');
        $('#responsabilidade_'+nextIndex).append('\r\n\
        <div class="col-md-8"><div class="form-group"><input type="text" maxlength=255 name="item['+nextIndex+'][ds_responsabilidade]" class="form-control" placeholder="Descrição da responsabilidade" /></div></div>\r\n\
        <div class="col-md-2"><div class="form-group">\r\n\
        <button type="button" class="btn btn-fill btn-danger pull-right btn-marg-left" id="btnItemResponsabilidadeExcluir"><i class="fa fa-minus"></i></button>\r\n\
        <button type="button" class="btn btn-fill btn-primary pull-right btn-marg-left" id="btnItemResponsabilidadeCriar"><i class="fa fa-plus"></i></button>\r\n\
        </div>\r\n\
        ');
    });
    $(document).on('click', '#btnItemResponsabilidadeExcluir', function(e) {
        e.preventDefault();
        let id = $(this).closest('div.item').attr('id');
        let deleteIndex = Number(id.split('_')[1]);
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
                                $('#responsabilidade_'+deleteIndex).remove();
                                window.location.reload();
                        }
                    });
                }
            });
        } else{
            $('#responsabilidade_'+deleteIndex).remove();
        }
    });
});