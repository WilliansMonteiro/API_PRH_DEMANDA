<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="x-apple-disable-message-reformatting">
  <title></title>

  <style>
    * {
      font-family:arial,helvetica,sans-serif;
    }

    body {
      width: 1200px;
    }

    .topo {
      /* background-image: linear-gradient(to right, #00AFEF, #014282); */
      background-color: #00AFEF;
      width: 70%;
      height: 80px;
      display: flex;
    }

    .div-logo {
      width: 60%;
      padding: 18px;
    }

    .div-sigla {
      width: 40%;
      color: white;
      font-weight: 700;
      font-style: italic;
      padding: 64px 20px 0 0;
    }

    .logo {
      width:  50px;
      margin: 5px 0 0 10px;
    }

    h1 {
      text-align: center;
    }

    .corpo {
      /* width: 70%; */
      width: 840px;
    }

    .mensagem {
      margin: 10%;
    }

    .feedback {
      text-align: center;
    }

    .icone-feedback {
      width:  50px;
    }

    .div-botao {
      text-align: center;
      width: 100%;
    }

    .botao {
      background-color: #00AFEF;
      border-radius: 6px;
      border: none;
      padding: 15px;
      color: #FFF;
      cursor: pointer;
      font-weight: 700;
      width: 40%;
      font-size: 12pt;
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .botao:hover {
      background-color: #014282;
      transition: 0.4s;
    }

    .rodape {
      /* background-image: linear-gradient(to right, #00AFEF, #014282); */
      background-color: #00AFEF;
      width: 70%;
      height: 80px;
      font-weight: 600;
      color: #E7E7E7;
    }

    .logo-rodape {
      border-right: 1px solid lightgray;
      padding-right: 10px;
    }

    .info-rodape {
      padding-left: 10px;
    }

    .porcentagem{
        display: flex;
        flex-direction: column;
        align-items: center;
    }
  </style>
</head>
<body>
  <img src="https://images.unlayer.com/projects/82918/1654860156136-fundo-topo-logo.png" alt="Logotipo BRB" width="100%">
  <div class="">
    <h1>Plataforma de recursos humanos</h1>
    <hr>
    <div class="mensagem">
        <div class="container">
            <p style="font-family: Arial, Helvetica, sans-serif;">Prezados usuário e gestor,<br>
            <p style="font-family: Arial, Helvetica, sans-serif;">Informamos que o cadastro do(a) <strong style="color: red;">{{ $parameters['no_usuario'] }}</strong> - <strong style="color: red;">{{$parameters['matricula']}}</strong> foi alterado pelo RH. Em atenção às regras de controle de acesso, solicitamos que os acessos aos sistemas, drives, correio e outros recursos tecnológicos sejam analisados e adequados no prazo máximo de 03 dias, conforme estabelecido no Manual de Controle de Acesso Lógico.</p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><ul><li>Lotação anterior: <strong style="color: red;">{{ $parameters['lotacao_antiga'] }}</strong>  – Lotação atual: <strong style="color: red;">{{ $parameters['nova_lotacao'] }} </strong></li></ul> </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><ul><li>Cargo/função anterior: <strong style="color: red;">{{ $parameters['cargo_antigo'] }}</strong>  – Cargo/função atual: <strong style="color: red;">{{ $parameters['novo_cargo'] }}</strong></li></ul> </p>
            <p style="font-family: Arial, Helvetica, sans-serif;">Para isso, proceder com abertura de interação na Ferramenta GIS (gis.brb.com.br/ess.do) solicitando que os perfis sejam readequados à nova lotação/cargo. </p>
            <p style="font-family: Arial, Helvetica, sans-serif;">Importante: </p>
            <p style="font-family: Arial, Helvetica, sans-serif;">De acordo com o Manual de Acesso Lógico: </p>

            <p style="font-family: Arial, Helvetica, sans-serif;"><ul><li>Garantir que todos seus subordinados com situação de ativo no sistema de Recursos Humanos e/ou PRH estejam efetivamente habilitados nos perfis adequados para exercer as suas atribuições profissionais para o Banco;</li></ul> </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><ul><li>Abrir chamado na Ferramenta GIS para solicitar o cancelamento das senhas e dos direitos de acesso a sistemas, a alteração ou a revogação dos perfis atribuídos ao usuário cuja lotação ou cargo/função tenha sido alterada e, também, a desvinculação das caixas postais administrativas e de serviço do correio eletrônico, quando não mais necessárias para o exercício das atribuições individuais de seus subordinados e prestadores de serviço em um prazo máximo de três dias úteis;</li></ul> </p>
            <p style="font-family: Arial, Helvetica, sans-serif; color:red;"><strong>RESPONDER ESTA NOTIFICAÇÃO COM O NÚMERO DA INTERAÇÃO (IT) ABERTA PARA AJUSTES DO ACESSO. EXEMPLO: IT01234567</strong> </p>


            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Att,</strong> </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong style="color: #00AFEF">Equipe Governança de Identidades e Acessos</strong> </p>
            <p style="font-family: Arial, Helvetica, sans-serif;color: #014282;"> Banco BRB</p>
            <p style="font-family: Arial, Helvetica, sans-serif;color: #014282;"> DICOR/SUROC/GERIT</p>
            <p style="font-family: Arial, Helvetica, sans-serif;color: #014282;"> Gerência de Segurança da Informação e Risco Cibernético</p>
            <p style="font-family: Arial, Helvetica, sans-serif;color: #014282;"> E-mail: conformidadeacessologico@brb.com.br</p>
            <p style="font-family: Arial, Helvetica, sans-serif;color: #014282;"> Telefone: 3409-3126</p>
            <p style="font-family: Arial, Helvetica, sans-serif;"> - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>
            <p style="font-family: Arial, Helvetica, sans-serif;"> O conteúdo dessa mensagem é confidencial, destina-se estritamente à(s) pessoa(s) acima referida(s) e é legalmente protegido. A retransmissão, divulgação, cópia ou outro uso desta comunicação por pessoas ou entidades, que não sejam o(s) destinatário(s), constitui obtenção de dados por meio ilícito e configura ofensa ao Art. 5°, inciso XII, da Constituição Federal. Caso esta mensagem tenha sido recebida por engano, por favor, inutilize-a e, se possível, avise ao remetente por e-mail.</p>


        </div>
    </div>
    </div>
  </div>
  <img src="https://images.unlayer.com/projects/82918/1654114758242-fundo-rodape-PSB.png" alt="" width="100%">
</body>
</html>

