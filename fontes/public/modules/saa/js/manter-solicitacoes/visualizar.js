$(document).ready(function () {
    $('#cep').mask('00000-000');
    $('#cpf').mask('000.000.000-00', {reverse: true});
    $('#telefone').mask('(00) 0000-0000');
});
