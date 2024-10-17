$(document).ready(function(){
    
    $('#btn-pesquisa-usuarios').click(function(){
      
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formulario-pesquisar-usuarios").serializeArray(),
            success	: function(retorno) {
                $('#mensagem-erro').html('');

                if(retorno == 1){
                    console.log(retorno);
                    $('#mensagem-erro').html('Por favor, selecione um tipo de consulta');

                }
                
                if(retorno == 2){
                    console.log(retorno);
                    $('#mensagem-erro').html('Por favor, selecione um tipo de parâmetro');
                }

                if(retorno == 3)
                {
                    console.log(retorno);
                    $('#mensagem-erro').html('Por favor, digite o valor do parametro Matrícula ou Cpf');
                }
                if(retorno == 4)
                {
                    console.log(retorno);
                    $('#mensagem-erro').html('Não é possivel consultar dados do AD pelo tipo de parâmetro CPF');
                }
            

                 if(retorno != 1 && retorno != 2 && retorno != 3 && retorno != 4)
                 {
                  $('#retorno').html(retorno);
                   $('#minhaTabela').DataTable({
                    language: {
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
                    },
                    
                   });
                }


                

             $("#resultadoConsulta").show("speed,callback");			           
            },
              beforeSend: function() { 
                     $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },  
            complete: function(){ 
                //   console.log('funcionou');
                    $('#carregar').html("");		  
            },		
            error	: function(XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            }
        });
    });


   





    
    $("#btnLimpar").click(function () {
        $("select")
        .find("option")
        .prop("selected", function () {
         return this.defaultSelected;
         });
          $("select").trigger("change.select2");
      });



      $( "#cd_tipo_parametro" ).change(function() {
        var cd_tipo_parametro = $("#cd_tipo_parametro").val();
        if(cd_tipo_parametro == 2){
            $("#no_parametro").mask("000.000.000-00");
        }else{
            $("#no_parametro").mask("00000000000");
        }
    });

});
