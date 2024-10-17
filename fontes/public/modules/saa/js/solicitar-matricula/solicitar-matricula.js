(function () {


    // function events() {
    //     $(document).on('click', '.btn_remove_anexo', remove_anexo);
    // }


    $("select").select2({
        theme: "bootstrap4",
    });

    $("#txtCep").focusout(function () {
        var cep = $("#txtCep").val();

        if(cep.length != 9){
            swal.fire({
                icon: "warning",
                text: "CEP Inválido"
            });

            return false;
        }

        var urlCep = "https://brasilapi.com.br/api/cep/v1/" + cep + "";

        $.ajax({
            url: urlCep,
            type: "get",
            dataType: "json",
            success: function (data) {

                var endereco = data.street;
                var cidade = data.city;
                var uf = data.state;
                $("#txtEndereco").val(endereco);
                $("#no_cidade").val(cidade);
                $('#cd_uf option[value="' + uf + '"]').attr({ selected: "selected" });
                $("#cd_uf").trigger("change.select2");
            },
            beforeSend: function () {
                $("#load").html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
            },
            complete: function () {
                //console.log("funcionou");
                $("#load").html("");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                swal.fire({
                    icon: "error",
                    text: "Ocorreu um Erro ao Consultar o Serviço de CEP"
                });
            },
        });
    });

    function TestaCPF(strCPF) {
        var Soma;
        var Resto;
        Soma = 0;
        if (strCPF == "00000000000") return false;
        for (i = 1; i <= 9; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;
        if (Resto == 10 || Resto == 11) Resto = 0;
        if (Resto != parseInt(strCPF.substring(9, 10))) return false;
        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;
        if (Resto == 10 || Resto == 11) Resto = 0;
        if (Resto != parseInt(strCPF.substring(10, 11))) return false;
        return true;
    }

    $("#nr_cpf").focusout(function () {
        var campoCpf = $("#nr_cpf").val();
        var replaceCPF = campoCpf.replace(/[.-]/g, "");
        verificarCPF = TestaCPF(replaceCPF);
        if (verificarCPF == true) {
            //console.log("cpf válido");
            $("#nr_cpf").removeClass("is-invalid");
            $("#nr_cpf").addClass("is-valid");
        } else {
            $("#nr_cpf").addClass("is-invalid");
        }
    });

    $("#btn-solicita-matricula").click(function () {
        $("#carregar").html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
    });

    $("#cd_empresa").change(function (e) {
        var url = $('#rotaGetDependencia').val()+'/'+$(this).val()
        var select = $('select[name=cd_dependencia_lotacao]');
        $.ajax({
            url: url,
            type: "get",
            success: function (data) {
                select.html('');
                $.each( data, function(key, val) {
                    select.append('<option value="' + val.cd_dependencia + '">' + val.sg_dependencia + ' - ' + val.nm_dependencia + "</option>'");
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });
    });

    $("#cd_empresa").change(function (e) {
        var urlTipo = $('#rotaGetTipoByEmpresa').val()+'/'+$(this).val();
        var selectTipo = $('select[name=cd_tipo_empregado]');
        $.ajax({
            url: urlTipo,
            type: "get",
            success: function (data) {
                selectTipo.html('');
                $.each( data, function(key, val) {
                    selectTipo.append('<option value="' + val.cd_tipo_empregado + '">' + val.ds_tipo_empregado + "</option>'");
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert_error("Erro, Desculpe!");
            },
        });
    });
   // Função para exibir nome do arquivo selecionado
})();

var labelInput = document.getElementsByClassName("label-anexo")[0];
var input = document.getElementById("anexos_recurso");

labelInput.addEventListener("click", function() {
    input.click();
});

input.addEventListener("change", function() {
    var nome = "Selecione um ou mais arquivos";
    if (input.isDefaultNamespace.length > 0) {
        nome = input.files[0].name;
        labelInput.innerHTML = nome;
    }

    addAnexos();
});

var recurso = {
    no_prestador: $('#no_prestador').val(),
    nr_cpf: $('#nr_cpf').val(),
    ds_email: $('#ds_email').val(),
    nr_ddd: $('#nr_ddd').val(),
    nr_cel: $('#nr_cel').val(),
    nr_cep: $('#txtCep').val(),
    ds_endereco: $('#txtEndereco').val(),
    no_cidade: $('#no_cidade').val(),
    cd_uf_rg: $('#cd_uf').val(),
    cd_empresa: $('#cd_empresa').val(),
    cd_dependencia_lotacao: $('#nr_dep_lotacao').val(),
    cd_tipo_empregado: $('#nr_tipo_empregado').val(),
    cd_cargo: $('#nr_cargo').val(),
    cd_empresa_prestadora: $('#nr_empresa_prestadora').val(),
    tp_jornada: $('#tp_jornada').val(),
    cd_contrato: $('#cd_contrato').val(),
    st_cadastramento: $('#st_cadastramento').val(),
    no_mae_prestador: $("#no_mae_prestador").val(),
    dt_nascimento: $('#dt_nascimento').val(),
    anexos: []
}

// Acessando o formulário criado no HTML
var formRecurso = $('#form_salva_recurso')[0];

// Criando o objeto FormData
var formData = new FormData(formRecurso);

function resetArrayAnexos() {
    return recurso.anexos = [];
}

var array_temp_anexos = [];



function addAnexos() {
    // Selecionando o elemento input
    var inputAnexos = $('input[type="file"]')[0];

    // Adicionando os arquivos à tabela
    $.each(inputAnexos.files, function(i, file) {
        // console.log(file);

        var cod_anexo = i + 1;
        var nome_anexo = file.name;

        if (recurso.anexos.length === 0) {
            $("#tb_anexos tbody>tr").remove();
            resetArrayAnexos();
        }

        recurso.anexos.push({
            cd_anexo: cod_anexo,
            no_anexo: nome_anexo
        });


        let linha = [];
        linha.push('<tr id="tr_anexo_' + cod_anexo + '">');
        linha.push('<td>' + nome_anexo + '</td>');
        linha.push('<td class="text-center"><i id="btn-remove-anexo" data-toggle="tooltip" data-placement="top" title="" data-original-title="Remover anexo" onclick="removerAnexo(event, '+ cod_anexo +');" class="btn btn-danger" style="cursor: pointer; color: #FFF;"><i class="fa fa-trash"></i><input type="hidden" name="anexos[]" value="' + cod_anexo + '"></i></td>');
        linha.push('</tr>');
        $(['#tb_anexos tbody'].join('')).append(linha.join(''));
    });

    // Adicionando os arquivos ao conjunto de dados
    $.each(inputAnexos.files, function(i, file) {
        // formData.append('anexo_' + i, file);
        array_temp_anexos.push(file);
    });
    // console.log('sem zero ', array_temp_anexos);
    // console.log('com zero', array_temp_anexos[0]);
    // array_temp_anexos.push(inputAnexos.files);
    // let valor_array = array_temp_anexos[0];
    // $.each(valor_array, function(i,file){
    //     console.log(file);
    // });
    $.each(array_temp_anexos, function(i){
        console.log(array_temp_anexos[i]);
        formData.append('anexos_' + i, array_temp_anexos[i]);
    });
    // formData.append('anexos', array_temp_anexos[0]);

    console.log(array_temp_anexos);





}




function removerAnexo(e, cod_anexo) {
    e.preventDefault();
    let linha = [];
    Swal.fire({
        title: 'Tem certeza que deseja remover este anexo?',
        text: '',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        cancelButtonColor: '#dc3c45',
        confirmButtonColor: '#55a846',
        confirmButtonText: 'Sim',
    }).then((result) => {

        if (result.value) {
            console.log('deleção: ' + 'anexo_' + cod_anexo);
            formData.delete('anexos_' + (cod_anexo - 1));

            removerAnexoArray(cod_anexo);
            removerAnexoTemp(cod_anexo);

            $("#tr_anexo_" + cod_anexo).remove();

            if (recurso.anexos.length === 0) {
                if ($("#tr_anexo_0").length === 0) {
                    linha.push('<tr id="tr_anexo_0">');
                    linha.push('<td class="text-center" colspan="3">Nenhum anexo adicionado</td>');
                    linha.push('</tr>');
                    $(['#tb_anexos tbody'].join('')).append(linha.join(''));
                    labelInput.innerHTML = "Selecione um ou mais arquivos";
                }
            }
        }
    });

}

function removerAnexoArray(indice) {
    // let arr = array;
    // for (let i = 0; i < arr.length; i++) {
    //     if (arr[i].cd_anexo == valueRemove) {
    //         arr.splice(i, 1);
    //         i--;
    //     }
    // }
    if (indice >= 0 && indice < recurso.anexos.length) {
        recurso.anexos.splice(indice, 1); console.log("Anexo removido com sucesso!");
     } else {
        console.log("O índice fornecido é inválido.");
    }
}

function removerAnexoTemp(indice) {
    if (indice >= 0 && indice < array_temp_anexos.length) {
        array_temp_anexos.splice(indice, 1); console.log("Anexo removido com sucesso!");
     } else {
        console.log("O índice fornecido é inválido.");
    }
 }


function solicitar_matricula(e,form)
{
    e.preventDefault();
    let rota = $('#rota-solicita-matricula').val();
    let _token = $('meta[name="csrf-token"]').attr('content');
    let arquivoInvalido = false;

    recurso.no_prestador = $('#no_prestador').val(),
    recurso.no_social = $('#no_social').val(),
    recurso.nr_cpf = $('#nr_cpf').val(),
    recurso.ds_email = $('#ds_email').val(),
    recurso.nr_ddd = $('#nr_ddd').val(),
    recurso.nr_cel = $('#nr_cel').val(),
    recurso.nr_cep = $('#txtCep').val(),
    recurso.ds_endereco = $('#txtEndereco').val(),
    recurso.no_cidade = $('#no_cidade').val(),
    recurso.cd_uf_rg = $('#cd_uf').val(),
    recurso.cd_empresa = $('#cd_empresa').val(),
    recurso.cd_dependencia_lotacao = $('#nr_dep_lotacao').val(),
    recurso.cd_tipo_empregado = $('#nr_tipo_empregado').val(),
    recurso.cd_cargo = $('#nr_cargo').val(),
    recurso.cd_empresa_prestadora = $('#nr_empresa_prestadora').val(),
    recurso.tp_jornada = $('#tp_jornada').val(),
    recurso.cd_contrato = $('#cd_contrato').val(),
    recurso.st_cadastramento = $('#st_cadastramento').val(),
    recurso.cd_situacao_sap = $('#cd_situacao_sap').val(),
    recurso.no_mae_prestador = $("#no_mae_prestador").val(),
    recurso.dt_nascimento = $("#dt_nascimento").val(),


    formData.append('no_prestador', recurso.no_prestador);
    formData.append('no_social', recurso.no_social);
    formData.append('nr_cpf', recurso.nr_cpf);
    formData.append('ds_email', recurso.ds_email);

    formData.append('nr_ddd', recurso.nr_ddd);
    formData.append('nr_cel', recurso.nr_cel);
    formData.append('nr_cep', recurso.nr_cep);

    formData.append('ds_endereco', recurso.ds_endereco);
    formData.append('no_cidade', recurso.no_cidade);
    formData.append('cd_uf_rg', recurso.cd_uf_rg);

    formData.append('cd_empresa', recurso.cd_empresa);
    formData.append('cd_dependencia_lotacao', recurso.cd_dependencia_lotacao);
    formData.append('cd_tipo_empregado', recurso.cd_tipo_empregado);

    formData.append('cd_cargo', recurso.cd_cargo);
    formData.append('cd_empresa_prestadora', recurso.cd_empresa_prestadora);
    formData.append('tp_jornada', recurso.tp_jornada);


    formData.append('cd_contrato', recurso.cd_contrato);
    formData.append('st_cadastramento', recurso.st_cadastramento);
    formData.append('cd_situacao_sap', recurso.cd_situacao_sap);

    formData.append('no_mae_prestador', recurso.no_mae_prestador);
    formData.append('dt_nascimento', recurso.dt_nascimento);




    if (recurso.no_prestador === '' || recurso.no_prestador === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo nome. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }


    if (recurso.nr_cpf === '' || recurso.nr_cpf === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo cpf. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (recurso.ds_email === '' || recurso.ds_email === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo e-mail. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (recurso.nr_ddd === '' || recurso.nr_ddd === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo ddd. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (recurso.nr_cel === '' || recurso.nr_cel === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo telefone. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }


    if (recurso.nr_cep === '' || recurso.nr_cep === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo cep. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (recurso.ds_endereco === '' || recurso.ds_endereco === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo endereço. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }


    if (recurso.no_cidade === '' || recurso.no_cidade === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo cidade. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    if (recurso.cd_uf_rg === '' || recurso.cd_uf_rg === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo UF. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }
    if (recurso.cd_cargo === '' || recurso.cd_cargo === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo cargo. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }
    if (recurso.tp_jornada === '' || recurso.tp_jornada === ' ') {
        Swal.fire({
            title: 'É obrigatório informar o campo jornada. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }

    console.log(recurso.anexos);

    if(array_temp_anexos.length === 0)
    {
        Swal.fire({
            title: 'É obrigatório inserir pelo menos um anexo. Verifique!',
            text: '',
            icon: 'warning'
        })
        return;
    }


    // $.each(recurso.anexos, function(i, file) {
    //     var nome_anexo = recurso.anexos[i].no_anexo;
    //     var extensao = nome_anexo.split(".")[1];

    //     if (extensao !== 'pdf') {
    //         Swal.fire({
    //             title: 'São permitidos apenas arquivos .pdf',
    //             text: '',
    //             icon: 'warning'
    //         });
    //         arquivoInvalido = true;
    //         return false;
    //     }
    // });



    if (!arquivoInvalido) {

        if ($("#" + form).valid()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: rota,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function(xhr) {
                    console.log('Requisição enviada:');
                    console.log($.param(xhr.data));
                    $("#carregar").html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
                },
                success: function (response) {
                    console.log(response);
                    if(response.status == true) {
                        console.log('Response: ' + response + " - Response Status: " + response.status);
                        resetArrayAnexos();
                        redirecionarSolicitarMatricula();
                        helper.alertSuccess('Solicitação de matrícula enviada com sucesso!');
                    } else {
                        console.log(response);
                        if (response.status == false) {
                            console.log('Response: ' + response + " - Response Status: " + response.status + " Message: " + response.message + " Exception: " + response.e + 'Erro: ' + response.error);
                            redirecionarSolicitarMatricula();
                            Swal.fire({
                                title: 'Erro na solicitação de matrícula',
                                text: response.error,
                                icon: 'warning'
                            });
                        }
                        // else {
                        //     if(response.status == 'servico_brs_404'){
                        //     console.log('Response: ' + response.status);
                        //     Swal.fire({
                        //         title: 'O Serviço BRSDOCUMENTOS, responsável por armazenar os arquivos está indisponível.',
                        //         text: '',
                        //         icon: 'warning'
                        //     });
                        // }
                        // }
                    }
                }
            });
        }

    }

    return false;

}


function redirecionarSolicitarMatricula() {
    let rotaPosSalvamento = $('#rota-cadastro').val();
    window.location = rotaPosSalvamento;
}
