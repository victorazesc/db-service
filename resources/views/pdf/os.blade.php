<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
<style>
    @page { margin:0cm 0cm}
    body {
                margin-top:    3.5cm;
                margin-bottom: 2cm;
                margin-left:   3cm;
                margin-right:  3cm;
            }
            #watermark {
                position: fixed;
                bottom:   -10px;
                left:     0px;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    21.0cm;
                height:   30cm;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
            .page_break { page-break-before: always; }

</style>
</head>

<body class="text-center" style="font-family: Arial, Helvetica, sans-serif">
<div id="watermark">
            <img src="https://azevedoseg.com/db/public/img/mark.png" height="100%" width="100%" />
        </div>

    <!-- <img src="/db/public/img/mark.png" style="width: 200px; height: 200px"> -->

    <p style="text-align:right">Itajaí, {{$os->today}}.</p>

    <p>À</p>
    <p>{{$os->client->name_client}}</p>
    <p>Att.: {{$os->user->first_name . ' ' . $os->user->last_name}}</p>

    <p style="text-align:right">Prop. {{$os->id}}/{{$os->year}}</p>

    <p>

    <p style="text-align: justify;">Somos especializados em Legalização de Empresa, Consultoria Técnica e Jurídica na Área de Assuntos Regulatórios e Sistema de Qualidade junto a ANVISA e Certificações ISO.</p>

    <p style="text-align: justify;">Estamos localizados no novo endereço Itajaí à Av. Cel. Marcos Konder 1177, sala 1003 – Edifício Pasteur. (com auditório para treinamento de 30 pessoas)</p>

    <p style="text-align: justify;">Nossa equipe técnica é multidisciplinar e especializada, o que permite conquistar ganho de eficiência e importantes vantagens, tais como, a redução do tempo de elaboração e aprovação dos processos, a redução de custos, a diminuição de pessoal alocado para tarefas regulatórias, dentre outros benefícios.</p>
   
    <p style="text-align: justify;">Preparamos todo o processo para obtenção de Licença Sanitária e Autorização de Funcionamento (AFE e AE), acompanhando a Vistoria Sanitária até a obtenção do Deferimento. Assessoramos os processos Sanitários de Importação.</p>
 
    <p style="text-align: justify;">Preparamos todos os processos para a obtenção dos Registros, Cadastros e Notificação de produtos junto à ANVISA, bem como elaboração do dossiê técnico.</p>
   
    <p style="text-align: justify;">Realizamos Treinamentos Específicos com a interpretação de normas e formação de auditores internos da qualidade e Vigilância Sanitária, visando à preparação para a Certificação de Boas Práticas e ISOs.</p>


    </p>
    <div class="page_break"></div>

    <h4>OBJETO DA PROPOSTA</h4>

    @foreach($os->selected_services as $key => $data)

    <p>{{$key +1}} - {{$data->service_name}}</p>


    @endforeach

    <h4>INVESTIMENTO</h4>

    <p>R$ {{$os->amount}} ({{$os->valueInText}}) @if($os->monthly || $os->monthly === null) mensal. @endif</p>

    <p><small>Despesas extras e taxas não inclusos para os serviços prestados</small></p>

    @if($os->monthly || $os->monthly === null)
    <h4>FORMA DE PAGAMENTO</h4>

    <p>Todo dia 05 conforme contrato e mediante nota fiscal apresentada.</p>
    @endif
    <img src="https://azevedoseg.com/db/public/img/ass.png" height="60px" style="margin-top: 30px; margin-bottom: -30px"/>
    <p>Dávida Barboza</p>
    <br>
    <p>Aceite __________________ ____/______/_____</p>


</body>

</html>