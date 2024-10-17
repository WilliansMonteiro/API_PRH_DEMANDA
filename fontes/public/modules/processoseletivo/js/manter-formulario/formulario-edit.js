(function() {
    function init() {
        // events();
        // loadPerguntas();
        // loadCientes();
    }

    function events() {


    }

    // function loadPerguntas(){
    //     // let rota = $("#rotaSalvarPergunta").val();
    //     let rotaCarregaTabelaPergunta = $("#rotaCarregaTabelaPergunta").val();
    //     let sq_formulario = $('#sq_formulario').val();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         url: rotaCarregaTabelaPergunta,
    //         type: "GET",
    //         data: { 'sq_formulario' :sq_formulario },
    //         success: function (response) {
    //             console.log(response);
    //             populateTablePerguntas(response)
    //         },
    //         beforeSend: function () {
    //             var row = ['<tr>'];
    //             row.push('<td align="center" colspan="3"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
    //             row.push('</tr>');
    //             $(['#tb_perguntas tbody'].join('')).append(row.join(''));
    //         },
    //         error: function (response) {
    //             console.info(response)
    //         }
    //     });
    // }

    // function loadCientes(){
    //     let rotaCarregaTabelaCiente = $("#rotaCarregaTabelaCiente").val();
    //     let sq_formulario = $('#sq_formulario').val();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    //     $.ajax({
    //         url: rotaCarregaTabelaCiente,
    //         type: "GET",
    //         data: { 'sq_formulario' :sq_formulario },
    //         success: function (response) {
    //             console.log(response);
    //             populateTableCientes(response);
    //         },
    //         beforeSend: function () {
    //             var row = ['<tr>'];
    //             row.push('<td align="center" colspan="4"><img src="/img/preloader.gif" style="width: 40px;" style="display: none; text-align: center;"> CARREGANDO</td>');
    //             row.push('</tr>');
    //             $(['#tb_cientes tbody'].join('')).append(row.join(''));
    //         },
    //         error: function (response) {
    //             console.info(response)
    //         }
    //     });
    // }

    // function populateTableCientes(arrCientes) {
    //     $('#tb_cientes tbody').html('');
    //     let rotaExcluirCiente = $("#rotaExcluirCiente").val();
    //     let row = [];
    //     $.each(arrCientes, function(i, value){
    //         console.log('array:', arrCientes);
    //         console.log('value: ', value);
    //         row.push('<tr id="' + value.sq_ciente + '">');
    //         row.push('<td>' + value.ciente.no_ciente + '</td>');
    //         if(value.st_obrigatorio === 'S') {
    //             row.push('<td>' + 'Sim' + '</td>');
    //         } else {
    //             row.push('<td>' + 'Não' + '</td>');
    //         }
    //         row.push('<td>' + value.nr_ordem + '</td>');
    //         // row.push('<td class="text-center"><i class="btn btn-small btn-danger" href="" data-href="'`${rotaExcluirCiente + value.sq_ciente_formulario}`'" id="btn-excluir-ciente" data-id-ciente="' + value.sq_ciente_formulario + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir ciente"></i></a></td>');
    //         // row.push('<td class="text-center"><i id="btn-remove-pergunta" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover pergunta" onclick="removerPergunta(event, '+ value.sq_ciente_formulario+');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" value="' + value.sq_ciente_formulario + '"></i></td>');

    //         row.push('</tr>');
    //         $(['#tb_cientes tbody'].join('')).append(row.join(''));
    //     })

    //     row.length = 0;
    // }

    // function populateTablePerguntas(arrPerguntas) {
    //     $('#tb_perguntas tbody').html('');
    //     let rotaExcluirPergunta = '';
    //     let row = [];
    //     $.each(arrPerguntas, function(i, value){

    //         rotaExcluirPergunta = $("#rotaExcluirPergunta").val() + "/" + value.sq_formulario_pergunta;
    //         let cod_pergunta_formulario = value.sq_formulario_pergunta;

    //         row.push('<tr id="' + value.sq_formulario_pergunta + '">');
    //         row.push('<td>' + value.pergunta.no_pergunta + '</td>');
    //         row.push('<td>' + value.nr_ordem + '</td>');
    //         row.push('<td class="text-center"><a class="btn btn-small btn-danger" href="" data-href="rotaExcluirPergunta" id="btn-excluir-pergunta" data-id-pergunta="' + cod_pergunta_formulario + '"><i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir pergunta"></i></a></td>');
    //         // row.push('<td class="text-center"><i id="btn-remove-pergunta" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover pergunta" onclick="removerPergunta(event, ' + rotaExcluirPergunta + ');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" value="' + value.sq_ciente_formulario + '"></i></td>');

    //         row.push('</tr>');
    //         $(['#tb_perguntas tbody'].join('')).append(row.join(''));
    //     })

    //     row.length = 0;
    // }

    $(document).on('click', '#btn-excluir-pergunta', function(e) {
        e.preventDefault();
        let url = $(this).data('href');
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#55a846',
            confirmButtonColor: '#dc3c45',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if (result.value) {
                let url = $(this).data('href');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type    : "DELETE",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    }

                });
            }
        });
    });


    $(document).on('click', '#btn-excluir-ciente', function(e) {
        e.preventDefault();
        let url = $(this).data('href');
        Swal.fire({
            title: 'Tem certeza que deseja excluir?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#55a846',
            confirmButtonColor: '#dc3c45',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if (result.value) {
                let url = $(this).data('href');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type    : "DELETE",
                    url     : url,
                    success : function(retorno) {
                        if (retorno)
                            window.location.reload();
                    }

                });
            }
        });
    });



    init();

})();

let ciente = {
    sq_formulario: null,
    sq_ciente: null,
    nr_ordem: null,
    st_obrigatorio: null
}

let pergunta = {
    sq_formulario: null,
    sq_pergunta_formulario: null,
    nr_ordem: null
}


function salvarPergunta(e) {

    pergunta.sq_formulario = $('#sq_formulario').val();
    pergunta.sq_pergunta_formulario = $('#sq_pergunta_formulario').val();
    pergunta.nr_ordem = $('#nr_ordem_pergunta').val();

    let rota = $('#rotaSalvaPergunta').val();
    let rotaPosSalvamento = $('#rotaEditarFormulario').val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    if (pergunta.sq_pergunta_formulario === '') {
        Swal.fire({
            title: 'É obrigatório selecionar uma pergunta. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: rota,
        type: 'POST',
        data: {
            _token: _token,
            'pergunta': pergunta
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.status) {
                console.log('Response: ' + response + " - Response Status: " + response.status);
                window.location = rotaPosSalvamento;
            } else {
                if (response.status == false) {
                    console.log('Response: ' + response + " - Response Status: " + response.status + " Message: " + response.message);
                    window.location = rotaPosSalvamento;
                } else {
                    console.log('Response: ' + response);
                    return;
                }
            }
        }
    });
    e.preventDefault();
    return false;
}

function salvarCiente(e) {

    ciente.sq_formulario = $('#sq_formulario').val();
    ciente.sq_ciente = $('#sq_ciente').val();
    ciente.nr_ordem = $('#nr_ordem_ciente').val();
    ciente.st_obrigatorio = $('#st_obrigatorio').val();

    let rota = $('#rotaSalvaCiente').val();
    let rotaPosSalvamento = $('#rotaEditarFormulario').val();
    let _token = $('meta[name="csrf-token"]').attr('content');

    if (ciente.sq_ciente === '') {
        Swal.fire({
            title: 'É obrigatório selecionar um ciente. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: rota,
        type: 'POST',
        data: {
            _token: _token,
            'ciente': ciente
        },
        dataType: 'json',
        success: function (response) {
            console.log(response);
            if (response.status) {
                console.log('Response: ' + response + " - Response Status: " + response.status);
                window.location = rotaPosSalvamento;
            } else {
                if (response.status == false) {
                    console.log('Response: ' + response + " - Response Status: " + response.status + " Message: " + response.message);
                    window.location = rotaPosSalvamento;
                } else {
                    console.log('Response: ' + response);
                    return;
                }
            }
        }
    });
    e.preventDefault();
    return false;
}
