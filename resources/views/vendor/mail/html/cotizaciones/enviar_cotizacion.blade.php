@component('mail::message')
#{{$cotizacion->saludo}}   {{$cotizacion->cliente->nombre}} <br>

{!! $cotizacion->descripcion  !!}

{!! $cotizacion->pie !!}

<p class="text-right">
Atentamente: <br>
<span style=""><b>{{ $cotizacion->vendedor->name }}</b></span><br>
@if($cotizacion->vendedor->phone)
<span style="">C:</span> {{$cotizacion->vendedor->phone}}<br>
@endif
<span style="">M:</span> {{$cotizacion->vendedor->email}}<br>
</p>

@endcomponent
