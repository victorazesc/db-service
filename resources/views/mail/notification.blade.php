<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">


        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body style="background-color:#dee2e6; color: #212529">
    <div class="text-center">
    <br>
            <img width="80px" src="https://azevedoseg.com/db/public/img/logo.svg" alt="DB">
            </div>
            <br>
        <div class="card" style="border-radius: 5px;">
            <div class="card-header">
                <h4>Tabela de Certidões</h4>
            </div>
            <div class="card-body table-responsive-md">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nome da Certidão</th>
                            <th>Nome do Cliente</th>
                            <th>Data de Emissão</th>
                            <th>Data de Vencimento</th>
                            <th>Grau de Complexidade</th>
                            <th>status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($certificates as $certificate)
                            <tr>
                                <th scope="row">{{ $certificate->id }}</th>
                                <td>{{ $certificate->certificate_name }}</td>
                                <td>{{ $certificate->client->name_client }}</td>
                                <td>{{ date('d/m/Y', strtotime($certificate->emission_date)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($certificate->due_date)) }}</td>
                                <td>{{ $certificate->alert_days }}</td>
                                <td>{{ $certificate->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
