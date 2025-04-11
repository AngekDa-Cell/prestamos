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
use Illuminate\Http\Request;

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

    public function puestosEliminarPost(Request $request)
    {
        $puesto = Puesto::findOrFail($request->input('id_puesto'));
        
        // Primero eliminamos los detalles de empleado-puesto relacionados
        DetEmpPuesto::where('fk_id_puesto', $puesto->id_puesto)->delete();
        
        // Luego eliminamos el puesto
        $puesto->delete();

        return redirect('/catalogos/puestos')->with('success', 'Puesto eliminado exitosamente');
    }
    // Termina Puestos

    // Empleados
    public function empleadosGet(): View
    {
        $empleados = Empleado::with('puestos')->get();
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

    public function empleadosEliminarPost(Request $request)
    {
        $empleado = Empleado::findOrFail($request->input('id_empleado'));
        
        // Primero eliminamos el detalle del empleado-puesto
        DetEmpPuesto::where('fk_id_empleado', $empleado->id_empleado)->delete();
        
        // Luego eliminamos el empleado
        $empleado->delete();

        return redirect('/catalogos/empleados')->with('success', 'Empleado eliminado exitosamente');
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
        // Obtener todos los empleados
        $empleados = Empleado::all();
        
        // Obtener empleados con préstamos vigentes
        $prestamosvigentes = Prestamo::whereNull('fecha_fin_desc')
            ->orWhere(function ($query) {
                $query->where('fecha_fin_desc', '>=', now())
                    ->where('fecha_ini_desc', '<=', now());
            })
            ->pluck('fk_id_empleado');

        // Filtrar empleados que no tienen préstamos vigentes
        $empleados = $empleados->whereNotIn('id_empleado', $prestamosvigentes);

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
