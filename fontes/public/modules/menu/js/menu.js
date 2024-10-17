$(document).ready(function(){
    $('#nr_prioridade').mask('00');

    $('#btnMenuPesquisar').on('click', function(e){
        e.preventDefault();
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarMenu").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#menuTable").DataTable({
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


    $('#cd_modulo').on('change', function(e){
        var urlMenuPai = $(this).attr('data-href-menu-pai');
        var urlFuncionalidade = $(this).attr('data-href-funcionalidade');
        loadMenuPai($(this).val(), urlMenuPai);
        loadFuncionalidade($(this).val(), urlFuncionalidade);
    });

    function loadMenuPai(cdModulo, url) {

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
                cd_modulo: cdModulo
            },
            beforeSend: function () {
                $('#sq_item_menu_pai').html('Carregando...');
            },
            complete: function () {
                $('#carregar').html("");
            },
            success: function (retorno) {
                $('#sq_item_menu_pai').html('Carregando...')
                    .find('option')
                    .remove()
                    .end();
                $('#sq_item_menu_pai').append('<option value="">Selecione</option>');
                $.each(retorno, function (key, value) {
                    $('#sq_item_menu_pai').append('<option value="' + value.sq_item_menu + '">' + value.no_item_menu + '</option>');
                });
            }
        });
    }

    function loadFuncionalidade(cdModulo, url) {

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
                cd_modulo: cdModulo
            },
            beforeSend: function () {
                $('#cd_funcionalidade').html('Carregando...');
            },
            complete: function () {
                $('#carregar').html("");
            },
            success: function (retorno) {
                $('#cd_funcionalidade').html('Carregando...')
                    .find('option')
                    .remove()
                    .end();
                $('#cd_funcionalidade').append('<option value="">Selecione</option>');
                $.each(retorno, function (key, value) {
                    $('#cd_funcionalidade').append('<option value="' + value.cd_funcionalidade + '">' + value.no_funcionalidade + '</option>');
                });
            }
        });
    }


    $(document).on('click', '#btnMenuExcluir', function(e){
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
});