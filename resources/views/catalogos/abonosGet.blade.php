@extends("components.layout")
@section("content")
@component("components.breadcrumbs",["breadcrumbs"=>$breadcrumbs])
@endcomponent

<div class="row my-4">
    <div class="col">
        <h1>Abonos del Préstamo {{$prestamo->id_prestamo}}</h1>
    </div>

    <!-- Tarjeta con información del préstamo -->
    <div class="card p-4 shadow-sm rounded-lg mb-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <strong class="text-muted">EMPLEADO</strong>
                <p class="mb-0">{{ $prestamo->Empleado->nombre }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <strong class="text-muted">ID PRÉSTAMO</strong>
                <p class="mb-0">{{ $prestamo->id_prestamo }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <strong class="text-muted">FECHA APROBACIÓN</strong>
                <p class="mb-0">{{ $prestamo->fecha_aprob }}</p>
            </div>
            <div class="col-md-3 mb-3">
                <strong class="text-muted">MONTO PRESTADO</strong>
                <p class="mb-0">{{ number_format($prestamo->monto, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center my-3">
    <h1>Abonos</h1>
    <a class="btn btn-primary" href="{{ url("/movimientos/prestamos/abonos/agregar/{$prestamo->id_prestamo}") }}">Agregar</a>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table" id="maintable">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">NUM DE ABONO</th>
                    <th scope="col">FECHA</th>
                    <th scope="col">MONTO CAPITAL</th>
                    <th scope="col">MONTO INTERES</th>
                    <th scope="col">MONTO COBRADO</th>
                    <th scope="col">SALDO PENDIENTE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($abonos as $index => $abono)
                    <tr>
                        <td class="text-center">{{ $abono->id_abono }}</td>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $abono->fecha }}</td>
                        <td class="text-center">${{ number_format($abono->monto_capital, 2) }}</td>
                        <td class="text-center">${{ number_format($abono->monto_interes, 2) }}</td>
                        <td class="text-center">${{ number_format($abono->monto_cobrado, 2) }}</td>
                        <td class="text-center">${{ number_format($abono->saldo_pendiente, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-right">TOTAL</th>
                    <th class="text-center" id="totalCapital"></th>
                    <th class="text-center" id="totalInteres"></th>
                    <th class="text-center" id="totalCobrado"></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let totalCapital = 0, totalInteres = 0, totalCobrado = 0;
        
        document.querySelectorAll("#maintable tbody tr").forEach(row => {
            totalCapital += parseFloat(row.cells[3].textContent.replace(/,/g, '').replace('$', '')) || 0;
            totalInteres += parseFloat(row.cells[4].textContent.replace(/,/g, '').replace('$', '')) || 0;
            totalCobrado += parseFloat(row.cells[5].textContent.replace(/,/g, '').replace('$', '')) || 0;
        });

        document.getElementById("totalCapital").textContent = '$' + totalCapital.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById("totalInteres").textContent = '$' + totalInteres.toLocaleString(undefined, {minimumFractionDigits: 2});
        document.getElementById("totalCobrado").textContent = '$' + totalCobrado.toLocaleString(undefined, {minimumFractionDigits: 2});
    });
</script>

@endsection
