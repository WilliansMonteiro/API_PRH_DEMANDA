(function(){

    function init(){
        events();
        config();
    }

    function events(){
        $(document).on('change', '#cd_modulo', loadPerfilModulo);

    }

    function config(){
        $('#ds_telefone').mask('(00) 00000-0000');
    }

    function loadPerfilModulo(){
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
    }


    init();
})()