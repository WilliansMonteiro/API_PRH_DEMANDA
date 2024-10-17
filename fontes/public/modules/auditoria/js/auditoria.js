$(document).ready(function(e){
    const traducao_datepicker = {
        dateFormat: 'dd/mm/yy',
        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'],
        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        minDate: "-28m",
        maxDate: "+28m",
        changeMonth: true,
        changeYear: true,
    };

    $('#dt_auditoria_inicio').datepicker(traducao_datepicker).mask('00/00/0000');
    $('#dt_auditoria_final').datepicker(traducao_datepicker).mask('00/00/0000');

    $('#btnAuditoriaPesquisar').click(function(){
        let url = $(this).data('href');
        $.ajax({
            type	: "POST",
            url		: url,
            data	: $("#formularioPesquisarAuditoria").serializeArray(),
            
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


});


$(document).on('click', '#btnAbrirModalDescricao', function(e){

    let ds_complemento = $(this).data('ds-complemento');
   
    e.preventDefault();
    Swal.fire({
        title: 'Descrição',
        html: '<p>'+ds_complemento+'</p>',
        // showCancelButton: true,
        // cancelButtonText: 'Fechar',
        // confirmButtonText: 'Enviar',
    }).then((result) => {
        
        if (result.value) {
            let url = $(this).data('href');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type    : "PUT",
                url     : url,
                success : function(retorno) {
                    window.location.reload();
                }
            });
        }
        
    });
    
});


(function () {

    function init() {
        events();
    }

    function events() {
        $(document).on("change", "#cd_modulo", loadEventoModulo);
    }
 
    function loadEventoModulo() {
        var url = $(this).attr("data-href");

        if (typeof url === "undefined") {
            return;
        }

        $("#cd_evento").prop("disabled", true);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                cd_modulo: $(this).val(),
            },
            beforeSend: function () {
                $("#carregar").html(
                    "<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO EVENTOS"
                );
            },
            complete: function () {
                $("#carregar").html("");
            },
            success: function (retorno) {
                $("#cd_evento")
                    .html("Carregando...")
                    .find("option")
                    .remove()
                    .end();
                $("#cd_evento").append('<option value="">Selecione</option>');
                $.each(retorno, function (key, value) {
                    $("#cd_evento").append(
                        '<option value="' + value.cd_evento + '">' + value.ds_evento + "</option>"
                    );
                });
                $("#cd_evento").prop("disabled", false);
            },
        });
    }

    init();

})();