<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>COTIZACIÓN</title>
    <link rel="stylesheet" href="{{ asset('css/cotizacion.css') }}" type="text/css">
</head>
<body>
    <table class="w-full">
        <tr>
            <td class="w-half">
                <img src="{{ asset('img/fuego-bites.png') }}" alt="Fuego Bites" width="75" />
            </td>
            <td class="w-half text-end">
                <h2>{{ config('company.name') }}</h2>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <p>
            <b>
                Cotización: {{ $cotizacion->folio }}
            </b>
        </p>

        <p>
            <span class="text-capitalize">{{ $cotizacion->saludo }}</span> <span class="text-capitalize fw-bolder">{{ $cotizacion->cliente->nombre }}</span>
        </p>
        <p>
            {{ $cotizacion->descripcion }}
        </p>
    </div>

    <div class="margin-top">
        <table class="partidas">
            <tr>
                <th>PARTIDA</th>
                <th>CONCEPTO</th>
                <th>CANTIDAD</th>
                <th>PRECIO </th>
                <th>TOTAL</th>
            </tr>
            @foreach($cotizacion->partidas as $partida)
                <tr class="items">
                    <td>
                        {{ $partida->posicion }}
                    </td>
                    <td>
                        <div>
                            {{ $partida->concepto }}
                        </div>
                        <div class="text-muted">
                            {{ $partida->descripcion }}
                        </div>
                    </td>
                    <td>
                        {{ $partida->cantidad}}
                    </td>
                    <td>
                        {{ $partida->precio }}
                    </td>
                    <td>
                        {{ $partida->total }}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="total">
        Total: $ {{ number_format($cotizacion->partidas->sum('total'),2) }} MXN
    </div>

    <div class="margin-top">
        <table class="observaciones">
            <tr>
                <th>
                    <b>OBSERVACIONES</b>
                </th>
            </tr>
            <tr>
                <td>
                    <p>
                        {{ $cotizacion->observaciones}}
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <div class="margin-top">
        <p>
            {{ $cotizacion->pie }}
        </p>
    </div>

    <div class="footer margin-top text-end">
        <b>Atentamente: </b><br>
        <span class="text-primary"><b>{{ $cotizacion->vendedor->name }}</b></span><br>
        <span class="text-primary">C:</span>{{ $cotizacion->vendedor->email }}<br>
        <span class="text-primary">W:</span> {{ config('company.web') }}<br>
    </div>
</body>
</html>
