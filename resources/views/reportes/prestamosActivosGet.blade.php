@extends("components.layout")

@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="container mt-4">
    <h1 class="text-center mb-4">Préstamos Activos</h1>

    <!-- Botón de regresar y filtro -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ url('/reportes') }}" class="btn btn-secondary">
            ← Regresar
        </a>

        <!-- Filtro por fecha -->
        <form method="GET" action="{{ url('/reportes/prestamos-activos') }}" class="d-flex align-items-end gap-2">
            <div>
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $fecha }}">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </form>
    </div>

    <!-- Tabla de préstamos activos -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID Préstamo</th>
                        <th>Empleado</th>
                        <th>Monto Prestado</th>
                        <th>Total Capital</th>
                        <th>Total Interés</th>
                        <th>Total Cobrado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prestamos as $prestamo)
                    <tr>
                        <td>{{ $prestamo->id_prestamo }}</td>
                        <td>{{ $prestamo->nombre }}</td>
                        <td>{{ number_format($prestamo->monto, 2) }}</td>
                        <td>{{ number_format($prestamo->total_capital, 2) }}</td>
                        <td>{{ number_format($prestamo->total_interes, 2) }}</td>
                        <td>{{ number_format($prestamo->total_cobrado, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay préstamos activos para la fecha seleccionada.</td>
                    </tr>
                    @endforelse

                    <!-- Fila de totales -->
                    @if($prestamos->isNotEmpty())
                    <tr class="table-success fw-bold">
                        <td colspan="2">Totales Generales</td>
                        <td>{{ number_format($totalPrestado, 2) }}</td>
                        <td>{{ number_format($totalCapital, 2) }}</td>
                        <td>{{ number_format($totalInteres, 2) }}</td>
                        <td>{{ number_format($totalCobrado, 2) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
