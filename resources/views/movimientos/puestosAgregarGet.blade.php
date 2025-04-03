@extends("components.layout")

@section("content")
@component("components.breadcrumbs", ["breadcrumbs" => $breadcrumbs])
@endcomponent

<div class="row my-4">
    <h1>Agregar Puesto</h1>
</div>

<div class="col-auto titlebar-commands">
    <a class="btn btn-secondary" href="{{ url('/catalogos/puestos') }}">Volver a la lista de puestos</a>
</div>
<br>

<form action="{{ url('/movimientos/puestos/agregado') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="nombre">Nombre del Puesto</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
    </div>
    <div class="form-group">
        <label for="sueldo">Sueldo</label>
        <input type="number" class="form-control" id="sueldo" name="sueldo" required>
    </div>
    <div class="form-group">
        <label for="estado">Estado</label>
        <select class="form-control" id="estado" name="estado" required>
            <option value="1">Activo</option>
            <option value="0">Inactivo</option>
        </select>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Agregar Puesto</button>
</form>

@endsection
