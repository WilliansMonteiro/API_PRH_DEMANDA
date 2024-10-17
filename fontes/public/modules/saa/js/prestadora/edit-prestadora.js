(function(){

    function init(){
        config();
    }

    function config(){
        $('#nr_cnpj').mask('AA.AAA.AAA/AAAA-00', {
            translation: {
                'A': { pattern: /[A-Za-z0-9]/ }
            },
            transform: function(v) {
                return v.toUpperCase();
            }
        });
        $('#nr_cnpj').on('input', function (){
            $(this).val($(this).val().toUpperCase());
        });
        // $("#data_emissao").datepicker().mask("00/00/0000");
        // $("#hora_emissao").mask("00:00:00");
        $("#nr_ddd").mask("00");
        $("#nr_tel").mask("0000-0000");

    }

    init();

})();
