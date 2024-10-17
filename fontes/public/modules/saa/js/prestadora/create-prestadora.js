$(document).ready(function(){
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
});