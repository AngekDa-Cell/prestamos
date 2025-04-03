@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row my-4">
        <h1>Agregar Empleado</h1>
    </div>

    <div class="mb-3">
        <a class="btn btn-secondary" href="{{ url('/catalogos/empleados') }}">Volver a la lista de empleados</a>
    </div>

    <form action="{{ url('/movimientos/empleados/agregado') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="apellidoP">Apellido Paterno</label>
                <input type="text" class="form-control" id="apellidoP" name="apellidoP" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="apellidoM">Apellido Materno</label>
                <input type="text" class="form-control" id="apellidoM" name="apellidoM" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha_inicio">Fecha de Ingreso</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="puesto_id">Puesto</label>
                <select class="form-control" id="puesto_id" name="puesto_id" required>
                    @foreach ($puestos as $puesto)
                        <option value="{{ $puesto->id_puesto }}">{{ $puesto->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Agregar Empleado</button>
    </form>
@endsection
