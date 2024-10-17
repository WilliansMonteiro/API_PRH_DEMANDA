(function () {

    var nr_matricula = $("#nr_matricula").val();

    function init() {
        events();
        config();
    }

    function events() {
        $(document).on('click', "#btn-historico-funcao", loadModalHistoricoFuncao);
        $(document).on('click', "#btn-historico-lotacao", loadModalHistoricoLotacao);
        $(document).on('click', "#btn-historico-tramitacao", loadModalHistoricoTramitacao);
    }

    function config() {
        $('#tbHistoricoFuncao').DataTable({});
        $('#tbHistoricoLotacao').DataTable({});
        $('#tbHistoricoTramitacao').DataTable({});
    }
    function loadModalHistoricoLotacao(){
        var rota        = $(this).attr('data-href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "GET",
            url		: rota,
            dataType : 'html',
            data	: {
                nr_matricula: nr_matricula
            },
            success	: function(retorno) {
                $('.loading-historico-lotacao').html('');
                $('.loading-historico-lotacao').html(retorno);
            },
            beforeSend: function() {
                $('.loading-historico-lotacao').html('<img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO');
            }
        });
    }

    function loadModalHistoricoFuncao(){
        var rota        = $(this).attr('data-href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "GET",
            url		: rota,
            dataType : 'html',
            data	: {
                nr_matricula: nr_matricula
            },
            success	: function(retorno) {
                $('.loading-historico-funcao').html('');
                $('.loading-historico-funcao').html(retorno);
            },
            beforeSend: function() {
                $('.loading-historico-funcao').html('<img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO');
            }
        });
    }

    function loadModalHistoricoTramitacao(){
        var rota        = $(this).attr('data-href');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "GET",
            url		: rota,
            dataType : 'html',
            data	: {
                sq_solicitacao_movimentacao: $('#sq_solicitacao_movimentacao').val(),
                nr_matricula: nr_matricula
            },
            success	: function(retorno) {
                $('.loading-historico-tramitacao').html('');
                $('.loading-historico-tramitacao').html(retorno);
            },
            beforeSend: function() {
                $('.loading-historico-tramitacao').html('<img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO');
            }
        });
    }

    init();
})();