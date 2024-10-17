(function () {
    function init() {
        $(".competencia_comportamental").hide();
        $(".competencia_tecnica").hide();
        $(".competencia_responsabilidade").hide();
        events();
    }

    function events() {
        $(document).on(
            "click",
            "input[name='radio_importacao']",
            exibeTemplate
        );
    }

    function exibeTemplate(event) {
        var tpCompetencia = $(this).val();
        $(".competencia_comportamental").hide();
        $(".competencia_tecnica").hide();
        $(".competencia_responsabilidade").hide();

        if (tpCompetencia == "comportamental") {
            $(".competencia_comportamental").show();
        } else if (tpCompetencia == "tecnica") {
            $(".competencia_tecnica").show();
        } else {
            $(".competencia_responsabilidade").show();
        }
    }

    init();
})();
