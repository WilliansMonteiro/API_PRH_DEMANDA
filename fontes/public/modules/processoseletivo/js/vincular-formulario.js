$(document).ready(function() {
    // A cada mudança na seleção da função, os campos com cd_funcao, cd_empresa_dependencia_dir, sup e ger são alterados.
    // Estes campos serão enviados na requisição para fazer o vínculo entre formulário e função.
    // os campos cd_empresa_dependencia_dir, cd_empresa_dependencia_sup e cd_empresa_dependencia_ger serão usados
    // no momento do salvamento no banco para buscar o grupo ao qual a função pertence
    $(document).on('change', '#cd_funcao', function() {

        var opcao = $('#cd_funcao :selected').val();
        var textoOpcao = $('#cd_funcao :selected').text();
        var diretoria = opcao;
        var superintendencia = opcao;
        var gerencia = opcao;
        var ItemFuncao = opcao;
        var tamanhoOpcao = opcao.length;

        console.log('tamanho ', tamanhoOpcao, ' ', 'opcao ', opcao);

        if (tamanhoOpcao < 14) {
            diretoria = diretoria.slice(0, 5);
            superintendencia = null;
            gerencia = null;
            ItemFuncao = ItemFuncao.slice(8);
            console.log('diretoria :', diretoria, "/",  "superintendencia: ", superintendencia, "/", " gerência: ", gerencia, "/", "função: ", ItemFuncao);
        } else if (tamanhoOpcao >= 14 && tamanhoOpcao < 19) {
            diretoria = diretoria.slice(0, 5);
            superintendencia = superintendencia.slice(6, 11);
            gerencia = null;
            ItemFuncao = ItemFuncao.slice(13);
            console.log('diretoria :', diretoria, "/",  "superintendencia: ", superintendencia, "/", " gerência: ", gerencia, "/", "função: ", ItemFuncao);
        } else if (tamanhoOpcao >= 19) {
            diretoria = diretoria.slice(0, 5);
            superintendencia = superintendencia.slice(6, 11);
            gerencia = gerencia.slice(12, 17);
            ItemFuncao = ItemFuncao.slice(18);
            console.log('diretoria :', diretoria, "/",  "superintendencia: ", superintendencia, "/", " gerência: ", gerencia, "/", "função: ", ItemFuncao);
        }

        $('#cd_empresa_dependencia_dir').val(diretoria);
        $('#cd_empresa_dependencia_sup').val(superintendencia);
        $('#cd_empresa_dependencia_ger').val(gerencia);
        $('#ItemFuncao').val(ItemFuncao);
        $('#no_grupo_funcao').val(textoOpcao);
    });

    $(document).on('change', '#sq_formulario', function() {
        var noFormulario = $('#sq_formulario :selected').text();
        console.log('formulário: ', noFormulario);
        $('#no_formulario').val(noFormulario);
    });
});
