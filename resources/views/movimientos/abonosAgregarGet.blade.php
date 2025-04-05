@extends("components.layout")

@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<h1>Agregar abono del préstamo</h1>

<!-- Card con información del préstamo -->
<div class="card mb-4">
    <div class="row card-body">
        <div class="col-2"><strong>EMPLEADO</strong></div>
        <div class="col">{{$prestamo->nombre . " " . $prestamo->apellidoP . " " . $prestamo->apellidoM}}</div>
    </div>

    <div class="row card-body">
        <div class="col-2"><strong>ID PRÉSTAMO</strong></div>
        <div class="col-2">{{$prestamo->id_prestamo}}</div>
        <div class="col-2"><strong>FECHA APROBACIÓN</strong></div>
        <div class="col-2">{{$prestamo->fecha_aprob}}</div>
        <div class="col-2"><strong>MONTO PRESTADO</strong></div>
        <div class="col-2">${{ number_format($prestamo->monto, 2) }}</div>
    </div>
</div>

<!-- Formulario para agregar abono -->
<form method="POST" action="{{ route('movimientos.abonos.agregar', ['id_prestamo' => $prestamo->id_prestamo]) }}">
    @csrf
    <input type="hidden" name="id_prestamo" value="{{$prestamo->id_prestamo}}">

    <div class="row my-4">
        <div class="form-group mb-3 col-6">
            <label class="form-label">Número de abono:</label>
            <div class="form-control-plaintext">{{$num_abono}}</div>
            <input type="hidden" name="num_abono" value="{{$num_abono}}">
        </div>
        <div class="form-group mb-3 col-6">
            <label class="form-label">Fecha del abono:</label>
            <input type="date" value="{{ now()->format('Y-m-d') }}" name="fecha" id="fecha" class="form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group mb-3 col-6">
            <label class="form-label">Monto a capital ($):</label>
            <input type="number" value="{{ number_format($pago_fijo_cap, 2, '.', '') }}" step="0.01" name="monto_capital" id="monto_capital" class="form-control" required>
        </div>

        <div class="form-group mb-3 col-6">
            <label class="form-label">Monto de interés ($):</label>
            <input type="number" value="{{ number_format($monto_interes, 2, '.', '') }}" step="0.01" name="monto_interes" id="monto_interes" class="form-control" required>
        </div>
    </div>

    <div class="row">
        <div class="form-group mb-3 col-6">
            <label class="form-label">Monto total cobrado ($):</label>
            <input type="number" value="{{ number_format($monto_cobrado, 2, '.', '') }}" step="0.01" name="monto_cobrado" id="monto_cobrado" class="form-control" required>
        </div>
        <div class="form-group mb-3 col-6">
            <label class="form-label">Saldo actual ($):</label>
            <div class="form-control-plaintext">${{ isset($saldo_pendiente) ? number_format($saldo_pendiente, 2, '.', '') : '0.00' }}</div>
            <input type="hidden" name="saldo_pendiente" value="{{ isset($saldo_pendiente) ? $saldo_pendiente : 0 }}">
        </div>
    </div>

    <div class="mb-3">
        <a class="btn btn-secondary" href="{{ url('/catalogos/prestamos') }}">Volver a la lista de préstamos</a>
    </div>

    <div class="row">
        <div class="col"></div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>
@endsection
