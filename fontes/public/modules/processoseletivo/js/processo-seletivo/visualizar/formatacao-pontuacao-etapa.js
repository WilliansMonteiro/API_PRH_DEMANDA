$(document).ready(function() {
    $('.money').each(function() {
        var valor = parseFloat($(this).val().replace(',', '.')).toFixed(2);
        $(this).val(valor.replace('.', ','));
    });
    // $('.money').mask('000,00', {
    //     reverse: true,
    //     // placeholder: '0,00'
    // });
});
