<html>
<body>

@if(env('APP_ENV') == 'local' || env('APP_ENV') == 'dsv' || env('APP_ENV') == 'hmo')
    <div style = "background-color: red; height: 50px;"><strong><p style = "text-align: center; font-size: 25px; color: white; font-family:
  Times New Roman, Times, serif">Ambiente {{env('APP_ENV')}} - E-mail de teste</p></strong></div>
@endif
<div style = "background-color: #6495ED; height: 50px;"><strong><p style = "text-align: center; font-size: 25px; color: white; font-family:
  Times New Roman, Times, serif">Plataforma PRH</p></strong></div>
<div style = "background-color: #CAE1FF">
    <strong>
        <p style = "text-align: center; color: black; font-family:
        Times New Roman, Times, serif">Nova Solicitação de Acesso cadastrada</p>
    </strong>
</div>
<div style = "background-color: #CAE1FF"><strong><p
                style = "text-align: left; color: black; font-family:Times New Roman, Times, serif">Dados da solicitação</p>
    </strong></div>
<table style = "width:100%">
    <tr>
        <th class = "text-left">Nome</th>
        <th class = "text-left">{{$parameters['usuario']->no_usuario}}</th>
    </tr>
    <tr>
        <th class = "text-left">Matrícula</th>
        <th class = "text-left">{{$parameters['usuario']->nr_matricula}}</th>

    </tr>
    <tr>
        <th class = "text-left">Lotação</th>
        <th class = "text-left">{{$parameters['usuario']->areaBenner->ds_area_benner}}</th>
    </tr>
    <tr>
        <th class = "text-left">Email</th>
        <th class = "text-left">{{$parameters['usuario']->ds_email}}</th>
    </tr>
    <tr>
        <th class = "text-left">Mensagem</th>
        <th class = "text-left">{{$parameters['mensagem']}}</th>
    </tr>
</table>
<div style = "background-color: #CAE1FF"><strong><p style = "text-align: center; color: black; font-family:
  Times New Roman, Times, serif"><a href = "https://prh.brb.com.br">Clique aqui para validar a solicitação</a></p>
    </strong></div>
<!-- <div style = "background-color: #6495ED; height: 50px;"><strong><p style = "text-align: center; font-size: 25px; color: white; font-family:
  Times New Roman, Times, serif">Plataforma PRH</p></strong></div> -->
</body>
</html>