<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Prestamo;
use App\Models\Empleado;
use App\Models\Abono;
use DateTime;
use Francerz\PowerData\index;

class ReportesController extends Controller
{
    public function indexGet()
    {
        return view('reportes.indexGet', [
            'breadcrumbs' => [
                "Inicio" => url("/"),
                "Reportes" => url("/reportes"),
            ]
        ]);
    }

    public function prestamosActivosGet(Request $request)
    {
        $fecha = Carbon::now()->format("Y-m-d");
        $fecha = $request->query("fecha", $fecha);

        $prestamos = Prestamo::join("empleados", "empleados.id_empleado", "=", "prestamo.fk_id_empleado")
            ->leftJoin("abonos", "abonos.fk_id_prestamo", "=", "prestamo.id_prestamo")
            ->select("prestamo.id_prestamo", "empleados.nombre", "prestamo.monto")
            ->selectRaw("COALESCE(SUM(abonos.monto_capital), 0) AS total_capital")
            ->selectRaw("COALESCE(SUM(abonos.monto_interes), 0) AS total_interes")
            ->selectRaw("COALESCE(SUM(abonos.monto_cobrado), 0) AS total_cobrado")
            ->groupBy("prestamo.id_prestamo", "empleados.nombre", "prestamo.monto")
            ->where("prestamo.fecha_ini_desc", "<=", $fecha)
            ->where("prestamo.fecha_fin_desc", ">=", $fecha)
            ->get();

        // Calcular los totales generales
        $totalPrestado = $prestamos->sum('monto');
        $totalCapital = $prestamos->sum('total_capital');
        $totalInteres = $prestamos->sum('total_interes');
        $totalCobrado = $prestamos->sum('total_cobrado');

        return view("/reportes/prestamosActivosGet", [
            "fecha" => $fecha,
            "prestamos" => $prestamos,
            "totalPrestado" => $totalPrestado,
            "totalCapital" => $totalCapital,
            "totalInteres" => $totalInteres,
            "totalCobrado" => $totalCobrado,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Reportes" => url("/reportes/prestamos-activos")
            ]
        ]);
    }


    public function matrizAbonosGet(Request $request)
    {
        $fecha_inicio = Carbon::now()->format("Y-01-01");
        $fecha_inicio = $request->query("fecha_inicio", $fecha_inicio);
        $fecha_fin = Carbon::now()->format("Y-12-31");
        $fecha_fin = $request->query("fecha_fin", $fecha_fin);

        $query = Abono::join("prestamo", "prestamo.id_prestamo", "=", "abonos.fk_id_prestamo")
            ->join("empleados", "empleados.id_empleado", "=", "prestamo.fk_id_empleado")
            ->select("prestamo.id_prestamo", "empleados.nombre", "abonos.monto_cobrado", "abonos.fecha")
            ->orderBy("abonos.fecha");

        $query->where("abonos.fecha", ">=", $fecha_inicio);
        $query->where("abonos.fecha", "<=", $fecha_fin);
        $abonos = $query->get()->toArray();

        // Crear un array para agrupar los datos
        $abonosAgrupados = [];
        foreach ($abonos as $abono) {
            $fecha = (new DateTime($abono["fecha"]))->format("Y-m");
            $id_prestamo = $abono["id_prestamo"];
            
            if (!isset($abonosAgrupados[$id_prestamo])) {
                $abonosAgrupados[$id_prestamo] = [
                    'id_prestamo' => $id_prestamo,
                    'nombre' => $abono['nombre'],
                    'abonos' => []
                ];
            }
            
            $abonosAgrupados[$id_prestamo]['abonos'][] = [
                'fecha' => $fecha,
                'monto_cobrado' => $abono['monto_cobrado']
            ];
        }

        return view("reportes.resumenAbonosGet", [
            "abonosAgrupados" => $abonosAgrupados,
            "fecha_inicio" => $fecha_inicio,
            "fecha_fin" => $fecha_fin,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Reportes" => url("/reportes"),
                "Resumen de Abonos" => url("/reportes/matriz-abonos")
            ]
        ]);
    }
}
