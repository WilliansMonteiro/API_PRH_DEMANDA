<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demonstrativo de pagamento BRB(Portal)</title>
    <style>
        /* Estilos do cartão */

      .table-border {
      border: 1px solid #ccc;
      border-radius: 1px;
      /* margin: 0; */
      /* box-shadow: 0 4px 8px rgb(0, 0, 0); */
    }

    td {
        font-family: 'Times New Roman', Times, serif;
        font-size: 13.5px;
    }


    .bordas-verticais table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .bordas-verticais th, .bordas-verticais td {
            border-right: 1px solid #ddd;
            /* padding: 8px; */
            text-align: left;
        }

        .bordas-verticais th:last-child, .bordas-verticais td:last-child {
            border-right: none;
        }

        .bordas-verticais th {
            background-color: #f2f2f2;
            border-bottom: 2px solid #ddd;
        }




    /* Estilos do conteúdo do cartão */
    .card-body {
      font-family: Arial, sans-serif;
      font-size: 14px;
      color: #333;
    }
      </style>
</head>

<body>

        <!-- Cartão HTML 1 -->
       <table class="table-border" style="width: 100%">
        <tr>
            <th class="table-border" rowspan="3"> <img src="{{ asset('img/banco-brb-bsli3bsli4.jpg') }}" style="max-height: 50px;" class="brand-image-xl logo-xl"></th>
            <th class="table-border" colspan="7"> <h3> DEMONSTRATIVO DE PAGAMENTO BRB (PORTAL) </h3></th>
        </tr>
        <tr>
            <td class="table-border" rowspan="2" style="text-align: center; font-family:'Times New Roman', Times, serif"><strong>Matrícula</strong> <br> {{$informacoes->matricula}} - {{$informacoes->matriculadigito}}</td>
            <td class="table-border" rowspan="2" style="text-align: center"><strong>Nome</strong> <br> {{$informacoes->nome}}</td>
            <td class="table-border" rowspan="2" style="text-align: center"><strong>Folha</strong> <br>{{$dados_pdf->tipofolha}}</td>
            <td class="table-border" rowspan="2" style="text-align: center"><strong>Padrão</strong> <br> {{$informacoes->padrao}}</td>
            <td class="table-border" rowspan="2" style="text-align: center"><strong>Agência</strong> <br>{{$informacoes->agencia}}</td>
            <td class="table-border" rowspan="2" style="text-align: center"><strong>Admissão</strong> <br> {{ Carbon\Carbon::createFromDate($informacoes->dataadmissao)->format('d/m/Y') }} </td>
            <td class="table-border" rowspan="2" style="text-align: center"><strong>Referência</strong> <br>{{$mes_referencia}}/{{$ano_referencia}}</td>
        </tr>


       </table>

       <table class="table-border" style="width: 100%">
        <tr>
            <td class="table-border" colspan="4" style="text-align: center"><strong>Lotação</strong> <br>{{$lotacao_funcao->lotacao}}</td>
            <td class="table-border" colspan="2" style="text-align: center"><strong>Classe</strong> <br>{{$informacoes->classe}}</td>
            <td class="table-border" colspan="2" style="text-align: center"><strong>Cargo</strong> <br>{{$informacoes->cargo}}</td>
            <td class="table-border" colspan="2" style="text-align: center"><strong>Função</strong> <br>{{$lotacao_funcao->ds_funcao}}</td>
            <td class="table-border" colspan="2" style="text-align: center"><strong>FL</strong><br>1 </td>

        </tr>
       </table>

       <br>

       <table class="bordas-verticais" style="width: 100%">
        <tr>
            <th class="table-border"  style="text-align: center">Código</th>
            <th class="table-border"  style="text-align: center">Descrição</th>
            <th class="table-border"  style="text-align: center">Referência</th>
            <th class="table-border"  style="text-align: center">Vencimentos</th>
            <th class="table-border"  style="text-align: center">Descontos</th>
        </tr>
        @foreach ($descricao as $item)
        <tr>
        <td style="text-align: center">{{$item->codigoverba}}</td>
        <td style="text-align: center">{{$item->nome}}</td>
        <td style="text-align: center">R$ {{ App\Helpers\Helper::converterFormatoMoeda($item->referencia) }}</td>
        <td style="text-align: center">
            @if($item->tipoverba == 1)
        R$ {{ App\Helpers\Helper::converterFormatoMoeda($item->valor)}}
            @endif</td>
        <td style="text-align: center">
            @if($item->tipoverba == 2)
            R$ {{ App\Helpers\Helper::converterFormatoMoeda($item->valor)}}
                @endif
            </td>
        </td>
        </tr>
        @endforeach
       </table>
       <table class="table-border" style="width: 100%">
        <tr>
          <td class="table-border"  rowspan="2" style="text-align: center; width: 57%;">Todos nascemos livres e iguais em dignidade e direitos.</td>
          <td class="table-border" style="text-align: center; width: 16%;"><strong>Total de Vencimentos:</strong> <br> R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->rendimentos) }} </td>
          <td class="table-border" style="text-align: center; width: 12%;"><strong>Total de Descontos:</strong> <br> R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->descontos) }}</td>
        </tr>
        <tr>
          <td class="table-border" style="text-align: center"><strong>Dep. IR: </strong> <br> {{$dados_pdf->numdependentes}}</td>
          <td class="table-border" style="text-align: center"> <strong>Valor Líquido:</strong> <br> R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->liquido) }}</td>
        </tr>


      </table>
      <table class="table-border" style="width: 100%">
      <tr>
        <td class="table-border" style="text-align: center; width: 11%;" rowspan="1"><strong>Sal. Cont. INSS: </strong> <br> R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->basecalcinss)}}</td>
        <td class="table-border" style="text-align: center; width: 12%;" rowspan="1"><strong>Base Cálc. FGTS: </strong>  <br> R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->basecalcfgts)}}</td>
        <td class="table-border" style="text-align: center; width: 12%;" rowspan="1"><strong>FGTS do mês: </strong>  <br> R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->depositofgts)}} </td>
        <td class="table-border" style="text-align: center; width: 17%;" rowspan="1"><strong>Base Cálc. IRRF: </strong>  <br>R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->basecalcirrf)}}</td>
        {{-- <td class="table-border" style="text-align: center; width: 12%;" rowspan="1"><strong>Faixa IRRF: <br> {{$dados_pdf->numdependentes}}</strong></td> --}}
        <td class="table-border" style="text-align: center; width: 15%;" rowspan="1"><strong>Carga Horária: </strong><br>{{$dados_pdf->cargahoraria}}H</td>
        <td class="table-border" style="text-align: center; width: 12%;" rowspan="1"><strong>Margem Consignável: </strong><br>R$ {{ App\Helpers\Helper::converterFormatoMoeda($dados_pdf->margemconsignavelbruta) }}</td>
      </tr>
      </table>





</body>

</html>
