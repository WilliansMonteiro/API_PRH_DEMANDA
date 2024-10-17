$(document).ready(function(){
   
      $('#btnLimpar').click(function(){
        $('select').find('option').prop('selected', function() {
          return this.defaultSelected;
      });
      $('select').trigger('change.select2');
      });

      $('#tabelaFaixa').DataTable({
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
        }
   
         });
     

         /* $(document).on('click', '#btnFaixaPesquisar', function(e){
            let url = $(this).data('href');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "POST",
                url     : url,
                data	: $("#formularioPesquisarFaixa").serializeArray(),
                success : function(retorno) {
                    console.log(retorno);
                    
                      if ( $.fn.dataTable.isDataTable( '#tabelaFaixa' ) ) {
                    table = $('#tabelaFaixa').DataTable();               
                    table.destroy();
                }

                 
                 $('#tabelaFaixa').DataTable( {  
                        "data": retorno,
                        "columns": [
                       { "data": "empresa" },
                       { "data": "tipo" },
                       { "data": "fmanumini" },
                       { "data": "fmanumfim" },
                       { "data": "fmaultmatric" },
                     
                      
                      ],
                      //O codigo abaixo define o idioma PT_BR para a dataTable
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
                } );	
                 
                  
                }

            });
        });*/
    
    
    });
