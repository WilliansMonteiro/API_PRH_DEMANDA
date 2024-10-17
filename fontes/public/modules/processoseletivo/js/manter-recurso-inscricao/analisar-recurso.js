$(document).ready(function() {
    $('input[value="1"]').change(function() {
        if ($(this).is(':checked')) {
            $('#btn-decisao').addClass('btn-success');
            $('#btn-decisao').removeClass('btn-danger', '');
            $('#btn-decisao').removeClass('btn-secondary', '');
         } });

    $('input[value="2"]').change(function() {
        if ($(this).is(':checked')) {
            $('#btn-decisao').addClass('btn-danger');
            $('#btn-decisao').removeClass('btn-success', '');
            $('#btn-decisao').removeClass('btn-secondary', '');
        }
    });

    // Verifica quando o campo de justificativa é preenchido
    $('#ds_justificativa').on('input', function() {
        checkFields();
    });

    // Verifica quando um dos radio buttons é selecionado
    $('input[type="radio"]').on('change', function() {
        checkFields();
    });

    function checkFields() {
        // Verifica se o campo textarea está preenchido
        if ($('#ds_justificativa').val().length > 0) {
            // Verifica se um dos radio buttons está selecionado
            if ($('input[type="radio"]:checked').length > 0) {
                // Habilita o botão
                $('#btn-decisao').prop('disabled', false);
            } else {
                // Desabilita o botão
                $('#btn-decisao').prop('disabled', true);
            }
        } else {
            // Desabilita o botão
            $('#btn-decisao').prop('disabled', true);
        }
    }
});
