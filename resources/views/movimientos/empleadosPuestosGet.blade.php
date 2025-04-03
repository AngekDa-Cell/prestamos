@extends("components.layout")

@section("content")
    @component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
    @endcomponent

    <div class="row my-4">
        <h1>Puesto del Empleado</h1>
    </div>
    
    <div>Empleado: {{$empleado->nombre}} {{$empleado->apellidoP}} {{$empleado->apellidoM}}</div>

    <br>

    <div class="col-auto titlebar-commands mb-3">
        <a class="btn btn-secondary" href="{{ url('/catalogos/empleados') }}">Volver a la lista de empleados</a>
    </div>
    
    <br>

    <table class="table" id="maintable">
    <thead>
        <tr>
            <th scope="col">Puesto</th>
            <th scope="col">Sueldo</th>
            <th scope="col">Fecha de Inicio</th>
            <th scope="col">Fecha de Fin</th>
        </tr>
    </thead>
    <tbody>
        @foreach($puestos as $puesto)
            <tr>
                <td class="text-center">{{ $puesto->nombre }}</td>
                <td class="text-center">{{ $puesto->sueldo }}</td>
                <td class="text-center">{{ $puesto->fecha_inicio }}</td>
                <td class="text-center">{{ $puesto->fecha_fin }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
    <a class="btn btn-primary" href="{{url('/movimientos/empleados/puestos/editar/'.$empleado->id_empleado)}}">Cambiar</a>
@endsection
