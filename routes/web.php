<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\MovimientosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportesController;

Route::get('/', function () {
    return view('home',["breadcrumbs"=>[]]);
});



//Puestos
Route::get("/catalogos/puestos", [CatalogosController::class, 'puestosGet']);
Route::post("/catalogos/puestos/eliminar", [CatalogosController::class, 'puestosEliminarPost']);
//Puestos Acciones
Route::get("/movimientos/puestos/agregar", [CatalogosController::class, 'puestosAgregarGet']);
Route::post("/movimientos/puestos/agregado", [MovimientosController::class, 'puestosAgregarPost']);



//Empleados
Route::get("/catalogos/empleados", [CatalogosController::class, 'empleadosGet']);
Route::post("/catalogos/empleados/eliminar", [CatalogosController::class, 'empleadosEliminarPost']);
// Empleados Puestos
Route::get("/movimientos/empleados/puestos/{id_empleado}", [CatalogosController::class, 'empleadosPuestosGet'])->where('id_empleado', '[0-9]+');
// Empleados Prestamos
Route::get("/movimientos/empleados/prestamos/{id_empleado}", [CatalogosController::class, 'empleadosPrestamosGet'])->where('id_empleado', '[0-9]+');
//Empleados Editar
Route::get("/movimientos/empleados/editar/{id_empleado}", [CatalogosController::class, 'empleadosEditarGet'])->where('id_empleado', '[0-9]+');
//Empleados Acciones
Route::get("/movimientos/empleados/agregar", [CatalogosController::class, 'empleadosAgregarGet']);
Route::post("/movimientos/empleados/agregado", [MovimientosController::class, 'empleadosAgregarPost']);



//Prestamos
Route::get("/catalogos/prestamos", [CatalogosController::class, 'prestamosGet']);
Route::get("/movimientos/prestamos", [CatalogosController::class, 'prestamosGet']);
Route::get("/movimientos/prestamos/agregar", [CatalogosController::class, 'prestamosAgregarGet']);
Route::post("/movimientos/prestamos/agregado", [MovimientosController::class, 'prestamosAgregarPost']);
Route::get("/movimientos/prestamos/editar/{id_prestamo}", [CatalogosController::class, 'prestamosEditarGet'])->where('id_prestamo', '[0-9]+');

//Abonos
Route::get("/movimientos/prestamos/abonos/{id_prestamo}", [CatalogosController::class, 'abonosGet'])->where('id_prestamo', '[0-9]+')->name('catalogos.abonos');
Route::get("/movimientos/prestamos/abonos/editar/{id_abono}", [CatalogosController::class, 'abonosEditarGet'])->where('id_abono', '[0-9]+');
Route::get("/movimientos/prestamos/abonos/agregar/{id_prestamo}", [MovimientosController::class, 'abonosAgregarGet'])->where('id_prestamo', '[0-9]+');
Route::post("/movimientos/prestamos/abonos/agregar/{id_prestamo}", [MovimientosController::class, 'abonosAgregarPost'])->name('movimientos.abonos.agregar');



// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

//Reportes
Route::get("/reportes",[ReportesController::class,"indexGet"]);
Route::get("/reportes/prestamos-activos", [ReportesController::class, "prestamosActivosGet"]);
Route::get("/reportes/matriz-abonos", [ReportesController::class, "matrizAbonosGet"]);
