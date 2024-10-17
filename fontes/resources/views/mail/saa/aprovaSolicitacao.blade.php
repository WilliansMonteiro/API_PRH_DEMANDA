
<html>
    <head>
    
        <style>
         .card {
      /* Add shadows to create the "card" effect */
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
      transition: 0.3s;
      background-color: #3d85c6;
      border-radius: 5px; /* 5px rounded corners */
      height: 80px;
    }
    
    .card-2 {
      /* Add shadows to create the "card" effect */
      box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
      transition: 0.3s;
      background-color: #ffffff;
      border-radius: 5px; /* 5px rounded corners */
      width: 50%;
      margin-left: auto;
      margin-right: auto; 
    }
    
    /* On mouse-over, add a deeper shadow */
    .card:hover {
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }
    
    /* Add some padding inside the card container */
    .container {
      padding: 2px 16px;
    }
    .titulo{
        text-align: center;
    }
    .botao{
    
      margin-left: auto;
      margin-right: auto; 
      background-color: #3d85c6; /* blue */
      border: none;
      color: white;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      font-size: 16px;
    }
        </style>
    </head>
    <body style="background-color: rgba(249, 254, 255, 0.685) ">
        <div class="card">
            <div class="container">
                
            </div>
          
          </div>
    
          <div class="card-2">
              
            <div class="container">
            <div class="titulo">
              <h2 style="font-family: Arial, Helvetica, sans-serif;"><b>{{$parameters['mensagem']}}</b></h2>
              <hr>
            </div>
             <p style="font-family: Arial, Helvetica, sans-serif;"> <strong>PRESTADOR</strong>  - <span>{{$parameters['prestador']}}</span> </p> 
             <p style="font-family: Arial, Helvetica, sans-serif;"> <strong>MATRÍCULA</strong>  - <span>{{$parameters['matricula']}}</span> </p> 
             <p style="font-family: Arial, Helvetica, sans-serif;"> <strong>APROVADOR</strong>  - <span> {{$parameters['usuario']}} </span> </p>
             <p style="font-family: Arial, Helvetica, sans-serif;"> <strong>EMPRESA</strong>  - <span>{{$parameters['empresa']}} </span> </p>    
            </div>
            <div class="botao">
            <a href="https://prhdsv.brb.com.br" target="_blank" class="botao">ACESSAR PRH</a>
            </div>
          </div>
    
    </body>
    </html>
    
