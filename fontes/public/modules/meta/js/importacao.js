(function () {

    function init() {

        events();
    }

    function events() {
        $(document).on('click', '#btn-exportar', exportarMetasCicloAvaliativo);
        $(document).on('change', '#file_importacao', verificaExtensao);
    }

    function exportarMetasCicloAvaliativo(event) {
        event.preventDefault();
        event.stopPropagation();

        if($('#sq_ciclo_avaliativo').val() == ''){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Selecione o ciclo avaliativo'
            });
            return false;
        }

        var cicloAvaliativo = $('#sq_ciclo_avaliativo').val();
        var url = $('#rota_exportacao').val();

        setTimeout(function (event) {

            window.open(url + '/' + cicloAvaliativo);

        }, 500);


    }


    function verificaExtensao(event) {
        var anexo = $(this)[0].files[0];
        var mimeTypesPermitidos = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
        var mimeTypeArquivo = anexo.type;
        if(typeof mimeTypesPermitidos.find(function(ext){ return mimeTypeArquivo == ext; }) == 'undefined') {
            $('form')[0].reset();
            $('.error-mime-type').show();
        } else {
            $('.error-mime-type').hide();
        }
    }


    init();
})();