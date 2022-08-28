<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border-bottom: 1px solid #ddd;
            padding: 8px;
        }

        #customers tr:nth-child(even) {
            /* background-color: #f2f2f2; */
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 0px;
            padding-bottom: 0px;
            /* background-color: #04AA6D; */
            /* colosr: white; */
        }

        #customers thead tr th {
            padding-top: 5px;
            padding-bottom: 10px;
            /* background-color: #04AA6D; */
            /* colosr: white; */
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-light {
            color: #f8f9fa !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
    </style>

</head>

<body class="text-center" style="font-family: Arial, Helvetica, sans-serif;">

<h1>Tabela de Logs</h1>
<br>
    <table class="table table-bordered mb-5" id="customers">
        <thead>
            <tr class="table-danger">
                <th scope="col">#</th>
                <th scope="col">Ação</th>
                <th class="text-left" scope="col">Descrição</th>
                <th class="text-left" scope="col">Usuário</th>
                <th class="text-left" scope="col">Data</th>
                <th scope="col">Identificador</th>
                <th class="text-left" scope="col">Pagina</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs ?? '' as $data)
            <tr>
                <th class="text-center">{{ $data->id }}</th>
                @switch($data->action)
                @case('delete')
                <td class="text-center"><span class="badge bg-danger text-light">{{ $data->action }}</span></td>
                @break
                @case('create')
                <td class="text-center"><span class="badge bg-success text-light">{{ $data->action }}</td>
                @break
                @case('update')
                <td class="text-center"><span class="badge bg-warning text-light">{{ $data->action }}</td>
                @break


                @endswitch
                <td class="text-left">{{ $data->extra_info }}</td>
                <td class="text-left">{{ $data->user->first_name }} {{ $data->user->last_name }}</td>
                <td class="text-left">{{ $data->created_at }}</td>
                <td style="text-align: center">{{ $data->send_id }}</td>
                <td class="text-left"><a href="{{ url('https://db-assuntosregulatorios.com'.$data->route ) }}">{{ $data->route }}</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>