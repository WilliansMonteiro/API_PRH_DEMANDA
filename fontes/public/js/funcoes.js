(function () {
    function init() {
        events();
        config();
        loadImgModulo();
    }

    function events() {
        $(document).on("change", "#cd_modulo", loadPerfilModulo);
    }

    function config() {
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
        $("select:not(.competencia_tecnica)").select2({
            theme: "bootstrap4",
        });
        $("#ds_telefone").mask("(00) 00000-0000");

        $.datepicker.regional["pt-BR"] = {
            closeText: "Fechar",
            prevText: "&#x3c;Anterior",
            nextText: "Pr&oacute;ximo&#x3e;",
            currentText: "Hoje",
            monthNames: [
                "Janeiro",
                "Fevereiro",
                "Mar&ccedil;o",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro",
            ],
            monthNamesShort: [
                "Jan",
                "Fev",
                "Mar",
                "Abr",
                "Mai",
                "Jun",
                "Jul",
                "Ago",
                "Set",
                "Out",
                "Nov",
                "Dez",
            ],
            dayNames: [
                "Domingo",
                "Segunda-feira",
                "Ter&ccedil;a-feira",
                "Quarta-feira",
                "Quinta-feira",
                "Sexta-feira",
                "Sabado",
            ],
            dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            dayNamesMin: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
            weekHeader: "Sm",
            dateFormat: "dd/mm/yy",
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: "",
            changeMonth: true,
            changeYear: true,
            maxDate: "0d",
        };
        $.datepicker.setDefaults($.datepicker.regional["pt-BR"]);
    }

    function loadPerfilModulo() {
        var url = $(this).attr("data-href");

        if (typeof url === "undefined") {
            return;
        }

        $("#cd_perfil").prop("disabled", true);
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
                    "<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO"
                );
            },
            complete: function () {
                $("#carregar").html("");
            },
            success: function (retorno) {
                $("#cd_perfil")
                    .html("Carregando...")
                    .find("option")
                    .remove()
                    .end();
                $("#cd_perfil").append('<option value="">Selecione</option>');
                $.each(retorno, function (key, value) {
                    $("#cd_perfil").append(
                        '<option value="' +
                            value.perfil.cd_perfil_acesso +
                            '">' +
                            value.perfil.ds_perfil_acesso +
                            "</option>"
                    );
                });
                $("#cd_perfil").prop("disabled", false);
            },
        });
    }


    function loadImgModulo(){

        const imagemDefault = "<img src='/img/banco-brb-bsli3bsli4.jpg' />"

        document.querySelectorAll('.imagem-modulo').forEach(function(elemento) {

            if(elemento.id){
                getImagemBrs(elemento.id).then(function(response) {
                    response.text().then(function(result) {
                            $(elemento).html('<img src="data:image/png;base64, '+JSON.parse(result).obArquivo+' " />')
                        })
                }).catch((error) => {
                    $(elemento).html(imagemDefault)
                  });
            }else{
                $(elemento).html(imagemDefault)
            }

        });
    }

    function getImagemBrs(id){

        const URL_TO_FETCH = retornaBaseUrl()+'/documentos/api/v1.0.0/documentos/download/'+id;

        return fetch(URL_TO_FETCH)
    }

    function retornaBaseUrl(){

        if(window.location.host.match('dsv') == 'dsv'){
            return 'https://brsdocumentosdsv.brb.com.br';
        }else if(window.location.host.match('hmo') == 'hmo'){
            return 'https://brsdocumentoshmo.brb.com.br';
        }else{
            return 'https://brsdocumentos.brb.com.br';
        }
    }



    init();
})();
