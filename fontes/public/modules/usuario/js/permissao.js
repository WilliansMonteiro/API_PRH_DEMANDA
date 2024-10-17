$(document).ready(function(e){

   
   /* $('#btnPermissaoIncluir').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioIncluirPermissao").serializeArray(),
            success	: function(retorno) {
                
                window.location.href = "/modulo-admin/usuario";
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                $('#carregar').html("");		  
            },		
            error	: function(msg) {
                if (msg.status === 422) {
                    printErrorMsg(msg);
                }
            }
        });
    });*/

    $(document).on('click', '#btnPermissaoExcluir', function(e){
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
                    error: function(retorno) {
                        console.log(retorno);
                    }

                });
            }
        });
    });

    function printErrorMsg(msg) {
        $('.alert-danger').css('display', 'block');
        $('.help-block').css('display', 'block');
        $.each(msg.responseJSON.errors, function(key, value){
            $("#"+key).addClass('has-error');
            $("#"+key).find('.help-block').append(value);
        });
    }
});



$(document).on('change', '#cd_modulo', function(){
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
            cd_modulo : $(this).val()
        },
        beforeSend: function() {
            $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
        },
        complete: function(){
            $('#carregar').html("");
        },
        success	: function(retorno) {
            
           $('#cd_perfil').html('Carregando...')
                .find('option')
                .remove()
                .end();
            $('#cd_perfil').append('<option value="">Selecione</option>');
            $.each(retorno,function(key, value){
                $('#cd_perfil').append('<option value="'+ value.perfil.cd_perfil_acesso +'">'+ value.perfil.ds_perfil_acesso+'</option>');
            });

        }
    });
});