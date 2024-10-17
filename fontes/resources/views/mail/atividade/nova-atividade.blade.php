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
  <img src="https://images.unlayer.com/projects/82918/1654860156136-fundo-topo-logo.png" alt="Logotipo BRB">
  <div class="corpo">
    <h1>PRH - Plataforma de Recursos Humanos / BRB In Home</h1>
    <hr>
    <div class="mensagem">
        <div class="container">
            <p style="font-family: Arial, Helvetica, sans-serif;">Olá, <strong>{{ $parameters['no_usuario'] }}</strong>!</p><br>
            <p style="font-family: Arial, Helvetica, sans-serif;">Você foi inserido em uma nova atividade: </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Atividade:</strong> {{ $parameters['atividade'] }} </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Prazo:</strong> {{ $parameters['prazo'] }} </p>
            <p style="font-family: Arial, Helvetica, sans-serif;"><strong>Ciclo:</strong> {{ $parameters['ciclo'] }} </p>
        </div>
    </div>
    </div>
  </div>
  <img src="https://images.unlayer.com/projects/82918/1654114758242-fundo-rodape-PSB.png" alt="">
</body>
</html>
