(function () {

    function init() {
        events();
        search();
    }

    function events() {
        $(document).on('click', '#btn-pesquisar-recurso', search);
        $(document).on('click', '#btn-limpar', limpaCamposPesquisa);
        // $(document).on('click', '#btn-inativar, #btn-ativar', handleStatus)
    }

    function search() {
        var url = $("#inputRotaPesquisar").val();
        $.ajax({
            type: 'POST',
            url: url,
            data: $('#form-pesquisar-recurso-inscricao').serializeArray(),
            success: function (retorno) {

                $('#retorno').html(retorno);
                $('#table-recurso').DataTable({
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
                $("#resultadoConsulta").show("speed,callback");
            },
            beforeSend: function () {
                $('#retorno').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                $('#carregar').html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest);
                console.log(textStatus);
                console.log(errorThrown);
                helper.alertError('Desculpe! Ocorreu um erro.');
            }
        });
    }

    function limpaCamposPesquisa() {
        // Limpa inputs text
        $("#dn_processo_seletivo").val("");
        $("#nr_inscricao").val("");
        $("#nr_matricula").val("");
        $("#no_usuario").val("");

        // Limpa select
        $("#cd_area_solicitante").find("option").prop("selected", function () {
            return this.defaultSelected;
        });
        $("select").trigger("change.select2");
    }

    init();
})();
