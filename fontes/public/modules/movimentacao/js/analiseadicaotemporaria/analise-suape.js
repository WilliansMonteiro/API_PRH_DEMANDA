(function () {


    function init() {
        events();
    }

    function events() {
        $("#btnSolicitarDipesAnalise").on("click", aprovacaoParaDipes);
        $("#btnReprovarAnalise").on("click", isReprovado);
    }


    function aprovacaoParaDipes(e){
        $('#tipoAprovacao').val('S')
    }

    function isReprovado(e){
        $('#isReprovado').val('S')
    }

    init();
})();
