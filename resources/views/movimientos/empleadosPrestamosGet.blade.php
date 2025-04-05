@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row my-4">
        <h1>Préstamos del Empleado</h1>
    </div>
    
    <div class="mb-3">
        <strong>Empleado:</strong> {{$empleado->nombre}} {{$empleado->apellidoP}} {{$empleado->apellidoM}}
    </div>

    <div class="col-auto titlebar-commands mb-3">
        <a class="btn btn-secondary" href="{{ url('/catalogos/empleados') }}">Volver a la lista de empleados</a>
    </div>

    <table class="table" id="maintable">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Fecha de Aprobación</th>
                <th scope="col">Fecha de Solicitud</th>
                <th scope="col">Monto</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestamos as $prestamo)
                <tr>
                    <td class="text-center">{{ $prestamo->id_prestamo }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($prestamo->fecha_aprob)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($prestamo->fecha_solicitud)->format('d/m/Y') }}</td>
                    <td class="text-center">${{ number_format($prestamo->monto, 2) }}</td>
                    <td class="text-center"><a href="{{ url('/movimientos/prestamos/abonos/' . $prestamo->id_prestamo) }}">Abonos</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
