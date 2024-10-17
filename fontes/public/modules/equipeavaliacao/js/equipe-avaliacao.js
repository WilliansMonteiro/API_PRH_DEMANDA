(function(){

    var matriculaMembro = [];
    var sqSubequipe = [];

    function init(){
        events();
        config();
        loadValuesArrayMatricula();
        loadValuesArraySubequipe();
        loadAllMembrosEquipeAvaliacao();
    }

    function events(){
        $(document).on('click', '#btnEquipeAvaliacaoPesquisar', searchEquipeAvaliacao);
        $(document).on('click', '#btnAddMembroEquipeAvaliacao', addMembroEquipeAvaliacao);
        $(document).on('click', '#btnExcluirCard', removeMembroEquipeAvaliacao);
        $(document).on('click', '#btnEquipeAvaliacaoConfirmar', confirmMembroEquipeAvaliacao);
        $(document).on('click', '#btnEquipeAvaliacaoSalvar', saveMembroEquipeAvaliacao);
        $(document).on('click', '#gerenciarSubequipe', loadMembrosSubEquipeAvaliacao);
    }

    function config(){

        $(document).on({
            ajaxStart: function(){
                $('.carregando').html("<img src='/img/preloader.gif' class='img-responsive center-block d-block mx-auto' style='width: 40px;' style='display: none; text-align: center;'> <p class='text-center'>Carregando...</p>");
                $('#carregando').show();
                $('#resultadoConsulta').hide();
                $('.subequipe').hide();

            },
            ajaxStop: function(){
                $('.carregando').html("");
                $('#carregando').hide();
                $('#resultadoConsulta').show();
                $('.subequipe').show();
            }
        });
    }

    var el = document.getElementById('div1');
    console.log(el);
    var t  = el.children[0];
    var jq = $("#div1").find("input").val();


    $(document).on('click', '#botaoTeste', function(e){
        e.preventDefault();
        var url = $("#inputRotaSalvarSubequipe").val();
        var matricula1 = $("#matricula1").find("input").val();
        var matricula2 = $("#matricula2").find("input").val();
        var matricula3 = $("#matricula3").find("input").val();

        var matricula4 = $("#matricula4").find("input").val();
        var matricula5 = $("#matricula5").find("input").val();
        var matricula6 = $("#matricula6").find("input").val();

        var matricula7 = $("#matricula7").find("input").val();
        var matricula8 = $("#matricula8").find("input").val();
        var matricula9 = $("#matricula9").find("input").val();

        var primeiraSubequipe = [matricula1,matricula2,matricula3];
        var segundaSubequipe =  [matricula4,matricula5,matricula6];
        var terceiraSubequipe = [matricula7,matricula8,matricula9];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "POST",
            url		: url,
            data    : {
                _token: $('meta[name="csrf-token"]').attr('content'),
                sq_equipe_avaliacao : $('#sq_equipe_avaliacao').val(),
                nr_matricula_primeira: primeiraSubequipe,
                nr_matricula_segunda: segundaSubequipe,
                nr_matricula_terceira : terceiraSubequipe,
            },
            success	: function(retorno) {
                window.location.reload();
            }
        });

    });


    function loadMembrosSubEquipeAvaliacao()
    {
        $('#divSubequipe').show();
    }


    function reloadPage()
    {
        var url = "/modulo-avaliacao/validar-equipe/pesquisar/"+$("#sq_ciclo_avaliativo").val();
        setTimeout(function(){
            $('.carregando').html("<img src='/img/preloader.gif' class='img-responsive center-block d-block mx-auto' style='width: 40px;' style='display: none; text-align: center;'> <p class='text-center'>Carregando...</p>");
            $('#carregando').show();
            $('#resultadoConsulta').hide();
            $('.subequipe').hide();
            window.location.href= url;
        }, 1000);
    }


    function searchEquipeAvaliacao(e)
    {
        e.preventDefault();
        var url = $(this).data('href')+"/"+$("#sq_ciclo_avaliativo").val();

        setTimeout(function(){
            $('.carregando').html("<img src='/img/preloader.gif' class='img-responsive center-block d-block mx-auto' style='width: 40px;' style='display: none; text-align: center;'> <p class='text-center'>Carregando...</p>");
            $('#carregando').show();
            $('#resultadoConsulta').hide();
            $('.subequipe').hide();
            window.location.href= url;
        }, 1000);

    }

    function removeMembroEquipeAvaliacao(e){
        var data_matricula = $(this).attr('data-matricula');
        e.preventDefault();
        if (typeof data_matricula !== typeof undefined && data_matricula !== false) {
            Swal.fire({
                title: 'Tem certeza que deseja excluir?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Não',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Sim',
            }).then((result) => {
                if(result.isConfirmed){
                $('.div-'+data_matricula).remove();
                $('input[name="nr_matricula_membro[]').each(function (key, value) {
                    if (data_matricula == $(value).val()) {
                        $(value).remove();
                    }
                });
                loadValuesArrayMatricula();
                var totalAlteracao = parseInt($('#total_alteracao_equipe_avaliacao').val()) - 1;
                $('#total_alteracao_equipe_avaliacao').val(totalAlteracao);
            }else{
                return;
            }
        });
        }
    }

    function loadValuesArrayMatricula() {
        matriculaMembro = $("input[name='nr_matricula_membro[]']")
            .map(function(){
                return $(this).val();
            }).get();
    }

    function loadValuesArraySubequipe(){
        sqSubequipe = $("input[name='sq_subequipe_avaliacao[]']")
            .map(function(){
                return $(this).val();
            }).get();
    }


    function loadAllMembrosEquipeAvaliacao(){
        var stCarregaSubordinados = $('#st_carrega_subordinados').val();
        var sq_equipe_avaliacao = $('#sq_equipe_avaliacao').val();
        var url =  $('#route_carrega_subordinados').val();
        
        //Verifica se Tem Equipe
        if(sq_equipe_avaliacao == ""){
            sq_equipe_avaliacao = 0;
        }
        
        //if(stCarregaSubordinados == 'S' || sq_equipe_avaliacao != ''){return}
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "GET",
            url		: url,
            data    : {
                nr_matricul_gestor : $('#nr_matricula_gestor').val(),
                sq_equipe_avaliacao : sq_equipe_avaliacao,
            },
            success	: function(retorno) {
                console.log(retorno);
                $.each(retorno, function (key, value) {
                    //var totalAlteracao = parseInt($('#total_alteracao_equipe_avaliacao').val()) + 1;
                    var arrasta = "";
                    //$('#total_alteracao_equipe_avaliacao').val(totalAlteracao);

                    //Verifica se permite arrastar
                    if(value.is_confirmado == "S"){
                        arrasta = false;
                    }else{
                        arrasta = true;
                    }

                    var row = ['<div class = "col-xs-4 col-sm-3 col-md-3 div-'+value.nr_matricula+'">'];
                    row.push('<div id='+value.nr_matricula+' draggable="'+arrasta+'" ondragstart="dragStart(event)" class = "card div-'+value.nr_matricula+'">');
                    row.push('<div class = "card-body text-xs-center div-'+value.nr_matricula+'">');
                    row.push('<input type="hidden" name="matricula_subequipe[]" value='+value.nr_matricula+'>');
                    row.push('<div align = "center" style="padding: 1rem"><img  src="/img/'+value.no_arquivo_foto+'" style="width: 70px; height: 70px; border-radius: 50%; pointer-events: none;"></div>');
                    row.push('<div align = "center"  style="padding-bottom: 0.5rem; min-height: 4rem; font-size: 0.9em">'+'u'+value.nr_matricula+' - '+value.ds_area+'<br>'+value.no_usuario+'</div>');

                    //Caso ainda não haja Confirmação, permite Deletar
                    if(value.is_confirmado == "N"){
                        row.push('<div align = "center"><button type="button" data-matricula="'+value.nr_matricula+'" id="btnExcluirCard" class = "btn btn-danger btn-sm">Excluir</button></div>');
                    }

                    row.push('</div></div></div>');


                    $(['#equipe-avaliacao'].join('')).append(row.join(''));
                    $('#lista-membros').append('<input type="hidden" id="nr_matricula_membro" name="nr_matricula_membro[]" value="' + value.nr_matricula + '">');
                    loadValuesArrayMatricula();
                    $('#st_carrega_subordinados').val('S');
                    $('#total_equipe_avaliacao').val(matriculaMembro.length);

                });
            }
        });
    }

    function addMembroEquipeAvaliacao(){
        let url = $(this).attr('data-href');

        var matricula = $('#nr_matricula').val();
        

        if(matricula == ''){
            swal.fire({
                icon: "warning",
                text: 'Campo adicionar empregado é obrigatório!'
            });
            return;
        }

        if(matriculaMembro.indexOf(matricula) >= 0 ){
            swal.fire({
                icon: "warning",
                text: 'O Empregado encontra-se vinculado. verifique!'
            });
            return;
        }


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "POST",
            url		: url,
            data    : {
                nr_matricula : matricula
            },
            success	: function(retorno) {

                //Verifica a Existencia de Membro em Outras Equipes
            ajaxVerificaMembroEquipe(retorno.nr_matricula).then(function(e) {

                if(e.length != 0){
        
                    Swal.fire({
                        title: 'Deseja mover o Empregado '+retorno.no_usuario+' para essa equipe ?',
                        text: '',
                        icon: 'warning',
                        showCancelButton: true,
                        cancelButtonText: 'Nao',
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Sim',
                    }).then((result) => {
                        if(result.isConfirmed){

                            ajaxInativaMembroEquipe(retorno.nr_matricula).then(function (e) {
                                adicionaLinha(retorno);
                            });

                            
                        }
                    });
    
                }else{
                    adicionaLinha(retorno)
                }


            });

            }
        });

    }

    function saveMembroEquipeAvaliacao(){
        let url = $(this).attr('data-href');

        var totalAlteracao = $('#total_alteracao_equipe_avaliacao').val();

        /*if(totalAlteracao == 0){
            swal.fire({
                icon: "warning",
                text: "Não houve alteração na equipe"
            });
            return;
        }
        */
        
        if(matriculaMembro.length == 0 || matriculaMembro == null){
            swal.fire({
                icon: "warning",
                text: "Não existe nenhum empregado vinculado, verifique!"
            });
            return;
        }
        matriculaMembro.length = 0;
        loadValuesArrayMatricula();

        var matricula1 = $("#matricula1").find("input").val();
        var matricula2 = $("#matricula2").find("input").val();
        var matricula3 = $("#matricula3").find("input").val();

        var matricula4 = $("#matricula4").find("input").val();
        var matricula5 = $("#matricula5").find("input").val();
        var matricula6 = $("#matricula6").find("input").val();

        var matricula7 = $("#matricula7").find("input").val();
        var matricula8 = $("#matricula8").find("input").val();
        var matricula9 = $("#matricula9").find("input").val();

        var primeiraSubequipe = [matricula1,matricula2,matricula3];
        var segundaSubequipe =  [matricula4,matricula5,matricula6];
        var terceiraSubequipe = [matricula7,matricula8,matricula9];
        
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type	: "POST",
            url		: url,
            data    : {
                _token: $('meta[name="csrf-token"]').attr('content'),
                nr_matricula        : matriculaMembro,
                primeiraSubequipe   : primeiraSubequipe,
                segundaSubequipe    : segundaSubequipe,
                terceiraSubequipe   : terceiraSubequipe,
                nr_matricula_gestor : $('#nr_matricula_gestor').val(),
                sq_ciclo_avaliativo : $("#sq_ciclo_avaliativo").val(),
                sq_equipe_avaliacao : $('#sq_equipe_avaliacao').val()
            },
            success	: function(retorno) {
                console.info(retorno);
                if(retorno.status == true){
                    swal.fire({
                        icon: "success",
                        text: retorno.msg
                    });
                    $('#total_alteracao_equipe_avaliacao').val(0);
                    $('#sq_equipe_avaliacao').val(retorno.sqEquipeAvaliacao);
                    reloadPage();

                }else{
                    swal.fire({
                        icon: "error",
                        text: retorno.msg
                    });
                }
            }
        });
    }

    function confirmMembroEquipeAvaliacao(){
        let url = $(this).attr('data-href');

        var totalAlteracao = $('#total_alteracao_equipe_avaliacao').val();

        if(matriculaMembro.length == 0 || matriculaMembro == null){
            swal.fire({
                icon: "warning",
                text: "Não existe nenhum empregado vinculado, verifique!"
            });
            return;
        }

        if(totalAlteracao != 0){
            swal.fire({
                icon: "warning",
                text: "Salve as alterações antes de confirmar a equipe. Após a confirmação não será possível realizar nenhuma outra alteração."
            });
            return;
        }

        
        Swal.fire({
            title: 'Tem certeza que deseja confirmar a equipe?',
            text: '',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sim',
        }).then((result) => {
            if(result.isConfirmed){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type	: "POST",
                url		: url,
                data    : {
                    nr_matricula : matriculaMembro,
                    sq_equipe_avaliacao : $('#sq_equipe_avaliacao').val()
                },
                success	: function(retorno) {
                    if(retorno.status == true){
                        swal.fire({
                            icon: "success",
                            text: retorno.msg
                        }).then((result) => {
                            if(result.isConfirmed){
                            reloadPage();
                            // window.location.reload();
                        }
                    });
                    }else{
                        swal.fire({
                            icon: "error",
                            text: retorno.msg
                        });
                    }
                }
            });
        }else{
            return;
        }
    });
    }

    //Verifica os Membros que estão em outras equipes
    function ajaxVerificaMembroEquipe(nr_matricula){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       return $.ajax({
            type    : "GET",
            url     : '/modulo-avaliacao/validar-equipe/verifica-membro/'+nr_matricula
        });
        
    }

    //Inativa o Membro que esta em outras equipes
    function ajaxInativaMembroEquipe(nr_matricula){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
       return $.ajax({
            type    : "POST",
            url     : '/modulo-avaliacao/validar-equipe/inativa-membro',
            data    :{
                nr_matricula : nr_matricula
            }
        });
        
    }

    //Adiciona Linha
    function adicionaLinha(retorno){

        var totalAlteracao = parseInt($('#total_alteracao_equipe_avaliacao').val()) + 1;
        $('#total_alteracao_equipe_avaliacao').val(totalAlteracao);

        var row = ['<div class = "col-xs-4 col-sm-3 col-md-3 div-'+retorno.nr_matricula+'">'];
            row.push('<div id='+retorno.nr_matricula+' draggable="true" ondragstart="dragStart(event)" class = "card div-'+retorno.nr_matricula+'">');
            row.push('<div class = "card-body text-xs-center div-'+retorno.nr_matricula+'">');
            row.push('<input type="hidden" name="matricula_subequipe[]" value='+retorno.nr_matricula+'>');
            row.push('<div align = "center" style="padding: 1rem"><img  src="/img/'+retorno.no_arquivo_foto+'" style="width: 70px; height: 70px; border-radius: 50%; pointer-events: none;"></div>');
            row.push('<div align = "center"  style="padding-bottom: 0.5rem; min-height: 4rem; font-size: 0.9em">'+'u'+retorno.nr_matricula+' - '+retorno.area_benner.ds_area_primaria_benner+'<br>'+retorno.no_usuario+'</div>');
            row.push('<div align = "center"><button type="button" data-matricula="'+retorno.nr_matricula+'" id="btnExcluirCard" class = "btn btn-danger btn-sm">Excluir</button></div>');
            row.push('</div></div></div>');
            $(['#equipe-avaliacao'].join('')).append(row.join(''));
            $('#lista-membros').append('<input type="hidden" id="nr_matricula_membro" name="nr_matricula_membro[]" value="' + retorno.nr_matricula + '">');
            loadValuesArrayMatricula();
            $('#nr_matricula').val(null).trigger('change');
    }



    init();
})()

//Escondendo Itens Abaixo da Pesquisa ao Mudar Combo
$('#sq_ciclo_avaliativo').change(function(){
    $('#loadEquipeAvaliacao').hide()
    $('#divSubequipe').hide()
});

