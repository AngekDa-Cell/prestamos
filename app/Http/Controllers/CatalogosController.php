<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\DetEmpPuesto;
use App\Models\Abono;
use Carbon\Carbon AS SupportCarbon;
use DateTime;


class CatalogosController extends Controller
{
    public function home(): View
    {
        return view('home', ["breadcrumbs" => []]);
    }

    // Puestos
    public function puestosGet(): View
    {
        $puestos = Puesto::all();
        return view('catalogos.puestosGet', [
            'puestos' => $puestos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Puestos" => url("/catalogos/puestos")
            ]
        ]);
    }

    public function puestosAgregarGet(): View
    {
        return view('movimientos.puestosAgregarGet', [
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Puestos" => url("/catalogos/puestos"),
                "Agregar" => url("/movimientos/puestos/agregar")
            ]
        ]);
    }
    // Termina Puestos

    // Empleados
    public function empleadosGet(): View
    {
        $empleados = Empleado::all();
        return view('catalogos.empleadosGet', [
            'empleados' => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/catalogos/empleados")
            ]
        ]);
    }

    public function empleadosAgregarGet(): View
    {
        $puestos = Puesto::all();
        return view('movimientos.empleadosAgregarGet', [
            "puestos" => $puestos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/catalogos/empleados"),
                "Agregar" => url("/movimientos/empleados/agregar")
            ]
        ]);
    }
    // Termina Empleados

    // Prestamos
    public function prestamosGet(): View
    {
        $prestamos = Prestamo::join("empleados","prestamo.fk_id_empleado","=","empleados.id_empleado")
            ->select(
                'prestamo.*',
                'empleados.nombre',
                'empleados.apellidoP',
                'empleados.apellidoM'
            )
            ->get();
        return view("catalogos.prestamosGet", [
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url('/movimientos/prestamos')
            ]
        ]);
    }
    public function prestamosAgregarGet(): View
    {
        $haceunanno = (new DateTime("-1 year"))->format("Y-m-d");
        $empleados = Empleado::where("fecha_ingreso","<",$haceunanno)->get()->all();
        $fecha_actual = SupportCarbon::now();
        $prestamosvigentes = Prestamo::where("fecha_ini_desc","<=",$fecha_actual)->where("fecha_fin_desc",">=",$fecha_actual)->get()->all();
        $empleados = array_column($empleados, null,"id_empleado");
        $prestamosvigentes = array_column($prestamosvigentes, null,"id_empleado");
        $empleados=array_diff_key($empleados,$prestamosvigentes);
        return view("movimientos/prestamosAgregarGet", [
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Prestamos" => url('/movimientos/prestamos'),
                "Agregar" => url('/movimientos/prestamos/agregar')
            ]
        ]);
    }
    public function prestamosEditarGet($id_prestamo): View
    {
        $prestamo = Prestamo::findOrFail($id_prestamo);
        $empleados = Empleado::all();
        return view('movimientos.prestamosEditarGet', [
            "prestamo" => $prestamo,
            "empleados" => $empleados,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/catalogos/prestamos"),
                "Editar" => url("/movimientos/prestamos/editar/{$id_prestamo}")
            ]
        ]);
    }
    // Termina Prestamos

    // Abonos
    public function abonosGet($id_prestamo): View
    {
        $prestamo = Prestamo::findOrFail($id_prestamo);
        $abonos = Abono::where('fk_id_prestamo', $id_prestamo)->with('prestamo')->get();
        return view('catalogos.abonosGet', [
            'prestamo' => $prestamo,
            'abonos' => $abonos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/catalogos/prestamos"),
                "Abonos" => url("/catalogos/abonos/{$id_prestamo}")
            ]
        ]);
    }

    public function abonosAgregarGet($id_prestamo): View
    {
        $prestamo = Prestamo::findOrFail($id_prestamo);
        $prestamos = Prestamo::with('empleado')->get();
        return view('movimientos.abonosAgregarGet', [
            "prestamo" => $prestamo,
            "prestamos" => $prestamos,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Préstamos" => url("/catalogos/prestamos"),
                "Agregar Abono" => url("/movimientos/prestamos/abonos/{$id_prestamo}")
            ]
        ]);
    }

    public function abonosEditarGet($id_abono): View
    {
        $abono = Abono::findOrFail($id_abono);
        return view('movimientos.abonosEditarGet', [
            "abono" => $abono,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Abonos" => url("/catalogos/abonos"),
                "Editar" => url("/movimientos/abonos/editar/{$id_abono}")
            ]
        ]);
    }
    // Termina Abonos

    // Empleados - Puestos
    public function empleadosPuestosGet($id_empleado): View
    {
        $empleado = Empleado::findOrFail($id_empleado);
        $puestos = DetEmpPuesto::where('fk_id_empleado', $id_empleado)
            ->join('puestos', 'puestos.id_puesto', '=', 'det_emp_puesto.fk_id_puesto')
            ->get(['puestos.nombre', 'puestos.sueldo', 'det_emp_puesto.fecha_inicio', 'det_emp_puesto.fecha_fin']);

        return view('movimientos.empleadosPuestosGet', [
            "puestos" => $puestos,
            "empleado" => $empleado,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/catalogos/empleados"),
                "Puestos" => url("/movimientos/empleados/puestos/{$id_empleado}")
            ]
        ]);
    }
    // Termina Empleados - Puestos

    // Empleados - Prestamos
    public function empleadosPrestamosGet($id_empleado): View
    {
        $empleado = Empleado::findOrFail($id_empleado);
        $prestamos = Prestamo::where('fk_id_empleado', $id_empleado)->get();

        return view('movimientos.empleadosPrestamosGet', [
            "prestamos" => $prestamos,
            "empleado" => $empleado,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/catalogos/empleados"),
                "Préstamos" => url("/movimientos/empleados/prestamos/{$id_empleado}")
            ]
        ]);
    }
    // Termina Empleados - Prestamos

    // Empleados - Editar
    public function empleadosEditarGet($id_empleado): View
    {
        $puestos = Puesto::all();
        $empleado = Empleado::findOrFail($id_empleado);
        $det_emp_puesto = DetEmpPuesto::where('fk_id_empleado', $id_empleado)->get();

        return view('movimientos.empleadosEditarGet', [
            "puestos" => $puestos,
            "empleado" => $empleado,
            "det_emp_puesto" => $det_emp_puesto,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Empleados" => url("/catalogos/empleados"),
                "Editar" => url("/movimientos/empleados/editar/{$id_empleado}")
            ]
        ]);
    }
    // Termina Empleados - Editar
}
