@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row my-4">
        <h1>Editar Préstamo</h1>
    </div>

    <div class="mb-3">
        <a class="btn btn-secondary" href="{{ url('/catalogos/prestamos') }}">Volver a la lista de préstamos</a>
    </div>

    <form action="{{ url('/movimientos/prestamos/editar/'.$prestamo->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Para indicar que es una actualización --}}

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="empleado">Empleado</label>
                <select class="form-control" id="empleado" name="empleado" required>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}" 
                            {{ $empleado->id == $prestamo->empleado_id ? 'selected' : '' }}>
                            {{ $empleado->nombre }} {{ $empleado->apellidoP }} {{ $empleado->apellidoM }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="monto">Monto</label>
                <input type="number" class="form-control" id="monto" name="monto" value="{{ $prestamo->monto }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="{{ $prestamo->fecha }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="Aprobado" {{ $prestamo->estado == 'Aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="Pendiente" {{ $prestamo->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
@endsection
