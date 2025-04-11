@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row my-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Empleados</h1>
            <a class="btn btn-primary" href="{{ url('/movimientos/empleados/agregar') }}">Agregar</a>
        </div>
    </div>

    <table class="table table-hover" id="maintable">
        <thead>
            <tr>
                <th class="text-center">ID Empleado</th>
                <th class="text-center">Nombre</th>
                <th class="text-center">Apellido Paterno</th>
                <th class="text-center">Apellido Materno</th>
                <th class="text-center">Fecha de Ingreso</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($empleados as $empleado)
                <tr>
                    <td class="text-center">{{ $empleado->id_empleado }}</td>
                    <td class="text-center">{{ $empleado->nombre }}</td>
                    <td class="text-center">{{ $empleado->apellidoP }}</td>
                    <td class="text-center">{{ $empleado->apellidoM }}</td>
                    <td class="text-center">{{ $empleado->puestos->first() ? \Carbon\Carbon::parse($empleado->puestos->first()->pivot->fecha_inicio)->format('d/m/Y') : 'Sin fecha' }}</td>
                    <td class="text-center d-flex justify-content-center gap-2">
                        <a href="{{ url('/movimientos/empleados/puestos/' . $empleado->id_empleado) }}" class="btn btn-sm btn-outline-secondary">Puesto</a>
                        <a href="{{ url('/movimientos/empleados/prestamos/' . $empleado->id_empleado) }}" class="btn btn-sm btn-outline-info">Préstamos</a>
                        <form action="/catalogos/empleados/eliminar" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="id_empleado" value="{{ $empleado->id_empleado }}">
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay empleados registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
