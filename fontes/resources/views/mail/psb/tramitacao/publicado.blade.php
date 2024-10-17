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
  </style>
</head>
<body>
  <img src="https://images.unlayer.com/projects/82918/1654860156136-fundo-topo-logo.png" alt="Logotipo BRB">
  <div class="corpo">
    <h1>Processo Seletivo Publicado</h1>
    <hr>
    <div class="mensagem">
        <div class="container">
            <p style="font-family: Arial, Helvetica, sans-serif;">Olá, gestores <strong>{{ $parameters['diretoria'] }}</strong> e <strong>{{ $parameters['solicitante'] }}</strong>!</p><br>
            <p style="font-family: Arial, Helvetica, sans-serif;">O processo seletivo abaixo, solicitado pela <strong>{{ $parameters['solicitante'] }}</strong>, acaba de ser publicado: </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Nº:</strong> {{ $parameters['dn_processo_seletivo'] }} </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Nome:</strong> {{ $parameters['no_processo_seletivo'] }} </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Início das inscrições:</strong> {{ $parameters['inicio'] }} </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Fim das inscrições:</strong> {{ $parameters['fim'] }} </p>
        </div>
    </div>
    </div>
  </div>
  <img src="https://images.unlayer.com/projects/82918/1654114758242-fundo-rodape-PSB.png" alt="">
</body>
</html>
