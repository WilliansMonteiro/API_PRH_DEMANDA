(function(){

    function init(){
        events();
    }

    function events(){
        $(document).on('click', '#btnPerfilExcluir',deletePerfilAcesso)
        $(document).on('click', '#remove-perfil-controle-acesso', delete_perfil_controle_acesso)
        $(document).on('click', '#ativar-perfil-controle-acesso', ativa_perfil_acesso)

    }



    function ativa_perfil_acesso()
    {
        var url = $(this).data('href');

        Swal.fire({
            title: "Tem certeza que deseja ativar o perfil de acesso?",
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

    function delete_perfil_controle_acesso()
    {
        var url = $(this).data('href');

        Swal.fire({
            title: "Tem certeza que deseja remover o perfil de acesso?",
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

    function deletePerfilAcesso(e){
        e.preventDefault();
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
    };

    init();
})();


