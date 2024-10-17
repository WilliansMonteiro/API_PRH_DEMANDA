(function () {
    function init() {
        events();
    }

    function events() {
        $(document).on('click', '.btn_remove_anexo', remove_anexo);
        $(document).on('click', '.btn-remove-anexo-cadastrado', remove_anexo_cadastrado);
    }
    // $(document).on("click", "#btn-editar-usuario", function (e) {
    //     e.preventDefault();
    //     $("#envia-formulario-update").trigger("click");
    // });
    $("#btn-editar-usuario").click(function(){
        console.log('clicou');
        $("#envia-formulario-update").trigger("click");
    });

    $('select').select2({
        theme: 'bootstrap4'
      });

    $("#txtCep").focusout(function () {
        var cep = $("#txtCep").val();
        console.log('entrou cep');
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

    $("#cd_empresa").change(function (e) {
        var url = $("#rotaGetDependencia").val() + "/" + $(this).val();
        var select = $("select[name=cd_dependencia_lotacao]");
        $.ajax({
            url: url,
            type: "get",
            success: function (data) {
                select.html("");
                $.each(data, function (key, val) {
                    select.append('<option value="' + val.cd_dependencia + '">' + val.sg_dependencia + ' - ' + val.nm_dependencia + "</option>'");
                });
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                // alert_error("Erro, Desculpe!");
                console.log(textStatus, errorThrown);
            },
        });
    });

    $("#btnSalvar").click(function(){
        $('#carregar').html("<img src='/img/preloader.gif' style='width: 40px;' style='display: none; text-align: center;'> CARREGANDO");
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

    $("#fdecpf").focusout(function () {
        var campoCpf = $("#fdecpf").val();
        var replaceCPF = campoCpf.replace(/[.-]/g, "");
        verificarCPF = TestaCPF(replaceCPF);

        if (verificarCPF == true) {
            $("#fdecpf").removeClass("is-invalid");
            $("#fdecpf").addClass("is-valid");
        } else {
            $("#fdecpf").addClass("is-invalid");
        }
    });


    function removeFileFromFileList(index) {
        const dt = new DataTransfer()
        const entrada = document.getElementById('termo_conduta_pdf')
        const { files } = entrada

        for (let i = 0; i < files.length; i++) {
          const file = files[i]
          if (index !== i)
            dt.items.add(file) // here you exclude the file. thus removing it.
        }

        entrada.files = dt.files // Assign the updates list
      }


        var label = document.getElementsByClassName("label-pdf")[0];
        var input = document.getElementById("termo_conduta_pdf");

        label.addEventListener("click", function() {
            input.click();
        });

        input.addEventListener("change", function() {
            var nome = "Selecione um termo";
            if (input.isDefaultNamespace.length > 0) {
                nome = input.files[0].name;
                label.innerHTML = "Selecione um termo";
                $.each(input.files, function (key,value){
                  $('#termo-conduta-selecionados').append(
                    '<div class="text-center" style="padding: 15px;">' +
                    '<a href="#" class="btn btn-danger"><i class="fas fa2x fa-file-pdf"></i></a> <br />' +
                    '<strong>' + value.name + '</strong>' +
                    '<br />' +
                   '<a style="border-radius: 70px;" class="btn btn-danger btn_remove_anexo" href="javascript:void(0)" data-id='+key+'> <i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir Anexo"></i></a>' +
                   '</div>'
                  );

                });
            }

        });

        function remove_anexo()
        {
            var indice = $(this).data('id');
            console.log(indice);
            removeFileFromFileList(indice);
            document.getElementById("termo-conduta-selecionados").innerText = "";

            $.each(input.files, function (key,value){
                console.log(key,value);

                 $('#termo-conduta-selecionados').append(
                   '<div class="text-center" style="padding: 15px;">' +
                   '<a href="#" class="btn btn-danger"><i class="fas fa2x fa-file-pdf"></i></a> <br />' +
                   '<strong>' + value.name + '</strong>' +
                   '<br />' +
                  '<a style="border-radius: 70px;" class="btn btn-danger btn_remove_anexo" href="javascript:void(0)" data-id='+key+'> <i class="fas fa-trash" data-toggle="tooltip" data-placement="top" title="Excluir Anexo"></i></a>' +
                  '</div>'
                 );

               });

        }


        function remove_anexo_cadastrado()
        {
            var url = $(this).data('href');

            Swal.fire({
                title: "Tem certeza que deseja remover o anexo?",
                text: "",
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: "Não",
                cancelButtonColor: "#dc3c45",
                confirmButtonColor: "#55a846",
                confirmButtonText: "Sim",
            }).then((result) => {
                if (result.value) {
                 console.log($(this).data('href'));


                $.ajaxSetup({
                     headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                 });

                 $.ajax({
                    type: "POST",
                    url: url,
                    success: function (retorno) {
                        window.location.reload();
                    },
                    });


                }
            });

        }
    init();
})();

