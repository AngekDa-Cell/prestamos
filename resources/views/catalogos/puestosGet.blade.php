@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row my-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Puestos</h1>
            <a class="btn btn-primary" href="{{ url('/movimientos/puestos/agregar') }}">Agregar</a>
        </div>
    </div>

    <table class="table table-hover" id="maintable">
        <thead>
            <tr>
                <th class="text-center">ID Puesto</th>
                <th class="text-center">Puesto</th>
                <th class="text-center">Sueldo</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($puestos as $puesto)
                <tr>
                    <td class="text-center">{{ $puesto->id_puesto }}</td>
                    <td class="text-center">{{ $puesto->nombre }}</td>
                    <td class="text-center">${{ number_format($puesto->sueldo, 2) }}</td>
                    <td class="text-center">
                        @if ($puesto->estado == 'activo')
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay puestos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
