(function () {

    function init() {
        events();
    }

    function events() {
        $("#btnReprovarAnalise").on("click", isReprovado);
    }

    function isReprovado(e){
        $('#isReprovado').val('S')
    }

    init();
})();
