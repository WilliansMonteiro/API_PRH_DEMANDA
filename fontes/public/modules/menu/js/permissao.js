$(document).ready(function(e){
   /* $('#btnPermissaoIncluir').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formulario_permissao").serializeArray(),
            success	: function(retorno) {
                $('#retorno tbody').append(retorno);
                $("#perfil_listar").show("speed,callback");
                $("#cd_modulo").prop('selectedIndex', 0);
                $("#cd_perfil").prop('selectedIndex', 0);
                Swal.fire({
                    title: 'Registro adicionado com sucesso.',
                    text: '',
                    icon: 'success',
                });

                window.location.reload();
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='./../img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log("Erro, Desculpe!");
            }
        });
    });*/

    $(document).on('click', '#btnPermissaoExcluir', function(e){
        e.preventDefault();
        let url = $(this).data('href');
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


});