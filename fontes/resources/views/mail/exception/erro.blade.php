<html>
<body>


<div style = "background-color: red; height: 50px;"><strong><p style = "text-align: center; font-size: 25px; color: white; font-family:
  Times New Roman, Times, serif">Ambiente {{env('APP_ENV')}}</p></strong></div>

<div style = "background-color: #6495ED; height: 50px;"><strong><p style = "text-align: center; font-size: 25px; color: white; font-family:
  Times New Roman, Times, serif">Plataforma PRH - Matrícula: {{Auth::check() ? Auth::user()->nr_matricula : null}}</p></strong></div>

{!! $content !!}

</body>
</html>