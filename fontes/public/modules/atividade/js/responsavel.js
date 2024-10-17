
$(document).ready(function(e){

    $('#btnResponsavelPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarResponsavel").serializeArray(),
            success	: function(retorno) {
                $('#retorno').html(retorno);
                $("#resultadoConsulta").show("speed,callback");			           
            },
            beforeSend: function() { 
                $('#carregar').html("<img src='./../img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                console.log('funcionou');
                $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });

    $(document).on('click', '#btnAtividadeResponsavelExcluir', function(e){
        e.preventDefault();

        //Pelo Menos um Responsável
        var lista = $('#lista_empregados_responsaveis').find('.row');
        if(lista.length == 1){
            swal.fire({
                icon: "warning",
                text: 'É necessário pelo menos um responsável na Atividade!'
            });
            return;
        }

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

    $(document).on('click', '#btnAddEmpregadoResponsavelAtividadeEditar', function(e){
        e.preventDefault();
        let nr_matricula = $('#nr_matricula option:selected').val();
        let no_responsavel = $('#nr_matricula option:selected').text();
        if (nr_matricula === ''){
            Swal.fire({
                icon: 'error',
                title: '',
                text: 'Selecione um responsável',
            });
            return;
        }
        
        
        //Valida se o Responsavel já esta na lista
        var lista = $('#lista_empregados_responsaveis').find('.row');
        for (let index = 0; index < lista.length; index++) {
            if(lista[index].id == nr_matricula){
                swal.fire({
                    icon: "warning",
                    text: 'Responsavel já consta na lista de Responsáveis!'
                });
                return;
            }
        }


        $('#lista_empregados_responsaveis').append('\r\n\
            <div id="'+nr_matricula+'" class="row">\r\n\
                <div class="col-md-8">\r\n\
                    <label>u'+nr_matricula+' - '+no_responsavel+'</label>\r\n\
                    <textarea name="ds_responsavel['+nr_matricula+']" class="form-control" rows="3"></textarea>\r\n\
                </div>\r\n\
                <div class="col-md-2" style="margin-top: 30px;">\r\n\
                    <button type="button" class="btn btn-danger" id="btnDeleteEmpregadoResponsavelAtividade" data-nr_matricula="'+nr_matricula+'"><i class="fas fa-trash"></i></button>\r\n\
                </div>\r\n\
            </div>\r\n\
        ');

        $('#nr_matricula').val(null).trigger('change');

    });

});
