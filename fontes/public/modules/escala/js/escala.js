$(document).ready(function(e){
    $('.mask_vl_item_escala_nota').mask('00');
    $('.mask_pc_item_escala').mask('000');

    $('#btnEscalaPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarEscala").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#resultadoConsulta").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='./img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    $(document).on('click', '#btnEscalaExcluir', function(e){
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

    $(document).on('click', '#btnItemEscalaCriar', function(e){
        e.preventDefault();
        let lastItem = $('.item:last').attr('id');
        let nextIndex = Number(lastItem.split('_')[1])+1;

        $('.item:last').after('<div class="row item" id="itemEscala_'+nextIndex+'"></div>');
        $('#itemEscala_'+nextIndex).append('\r\n\
        <div class="col-md-6"><div class="form-group"><input type="text" name="item['+nextIndex+'][ds_item_escala]" class="form-control" placeholder="Descrição do item" /></div></div>\r\n\
        <div class="col-md-2"><div class="form-group"><input type="text" name="item['+nextIndex+'][vl_item_escala_nota]" class="form-control" maxlength="3" /></div></div>\r\n\
        <div class="col-md-2"><div class="form-group"><input type="text" name="item['+nextIndex+'][pc_item_escala]" class="form-control" maxlength="3" /></div></div>\r\n\
        <div class="col-md-2"><div class="form-group">\r\n\
        <button type="button" class="btn btn-fill btn-danger pull-right btn-marg-left" id="btnItemEscalaExcluir"><i class="fa fa-minus"></i></button>\r\n\
        </div>\r\n\
        ');
    });
    $(document).on('click', '#btnItemEscalaExcluir', function(e) {
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
                
                //Verifica a Quantidade de Itens antes de Excluir
                if(result.value){

                    verificaQuantidadeDescricao($('input[name="id_escala"]').val()).then( function (e) {
                        if(e == 1){
                            swal.fire({
                                icon: "warning",
                                text: 'Deve existir pelo menos um item!'
                            });

                        }else{

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
                                        $('#itemEscala_'+deleteIndex).remove();
                                        window.location.reload();
                                }

                            });
                            
                        }
                    })
                    
                    
                    return false;
     
                }
            
            });

            return;
        }

        $('#itemEscala_'+deleteIndex).remove();
    });
});

//Reset da Pesquisa
$("[type='reset']").click(function(){
    $('#cd_perspectiva').val(null).trigger('change');
});

function verificaQuantidadeDescricao(escala){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   return $.ajax({
        type    : "GET",
        url     : '/modulo-avaliacao/administrativo/escala/quantidade/'+escala
    });
    
}