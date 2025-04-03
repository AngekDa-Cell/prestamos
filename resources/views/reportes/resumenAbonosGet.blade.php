@extends("components.layout")

@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<h1 class="text-center">Resumen de Abonos Cobrados</h1>

<!-- Botón de regresar -->
<div class="mb-4">
    <a href="{{ url('/reportes') }}" class="btn btn-secondary">
        ← Regresar
    </a>
</div>

<!-- Filtro por rango de fechas -->
<form method="GET" action="{{ url('/reportes/matriz-abonos') }}" class="mb-4">
    <div class="row justify-content-center">
        <div class="col-4">
            <label for="fecha_inicio">Fecha de inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ $fecha_inicio }}">
        </div>
        <div class="col-4">
            <label for="fecha_fin">Fecha fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ $fecha_fin }}">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </div>
</form>

<!-- Tabla de abonos cobrados -->
<div class="card">
    <div class="card-body">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th>ID Préstamo</th>
                    <th>Empleado</th>
                    <th>Fecha Abono</th>
                    <th>Monto Cobrado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($abonosAgrupados as $prestamo)
                @foreach($prestamo['abonos'] as $abono)
                <tr>
                    <td>{{ $prestamo['id_prestamo'] }}</td>
                    <td>{{ $prestamo['nombre'] }}</td>
                    <td>{{ $abono['fecha'] }}</td>
                    <td>{{ number_format($abono['monto_cobrado'], 2) }}</td>
                </tr>
                @endforeach
                @empty
                <tr>
                    <td colspan="4" class="text-center">No hay abonos registrados en este período</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection