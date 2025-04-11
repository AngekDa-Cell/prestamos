<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puesto;
use App\Models\Empleado;
use App\Models\Prestamo;
use App\Models\DetEmpPuesto;
use App\Models\Abono;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class MovimientosController extends Controller
{
    // Puestos
    public function puestosAgregarPost(Request $request)
    {
        $puesto = new Puesto([
            'nombre' => strtoupper($request->input('nombre')),
            'sueldo' => $request->input('sueldo')
        ]);
        $puesto->save();

        return redirect('/catalogos/puestos');
    }
    // Termina Puestos

    // Abonos
    public function abonosAgregarGet($id_prestamo): View
    {
        // Consulta con select para incluir campos del empleado
        $prestamo = Prestamo::join("empleados", "empleados.id_empleado", "=", "prestamo.fk_id_empleado")
            ->where("prestamo.id_prestamo", $id_prestamo)
            ->select("prestamo.*", "empleados.nombre", "empleados.apellidoP", "empleados.apellidoM")
            ->firstOrFail();

        // Obtener el número de abono basado en el último registro
        $ultimo_abono = Abono::where("fk_id_prestamo", $id_prestamo)
            ->orderBy("num_abono", "desc")
            ->first();

        $num_abono = $ultimo_abono ? $ultimo_abono->num_abono + 1 : 1;

        // Obtener el último abono para el saldo
        $ultimo_abono_saldo = Abono::where("fk_id_prestamo", $id_prestamo)
            ->orderBy("fecha", "desc")
            ->first();

        $saldo_actual = $ultimo_abono_saldo ? $ultimo_abono_saldo->saldo_pendiente : $prestamo->monto;

        // Cálculo de montos
        $monto_interes = $saldo_actual * ($prestamo->tasa_mensual / 100);
        $monto_cobrado = $prestamo->pago_fijo_cap + $monto_interes;
        $saldo_pendiente = $saldo_actual - $prestamo->pago_fijo_cap;

        if ($saldo_pendiente < 0) {
            $pago_fijo_cap = $prestamo->pago_fijo_cap + $saldo_pendiente;
            $saldo_pendiente = 0;
        } else {
            $pago_fijo_cap = $prestamo->pago_fijo_cap;
        }

        // Formatear fecha para la vista
        $prestamo->fecha_Aprobacion = Carbon::parse($prestamo->fecha_Aprobacion)->format('Y-m-d');

        return view('movimientos.abonosAgregarGet', [
            'prestamo' => $prestamo,
            'num_abono' => $num_abono,
            'pago_fijo_cap' => $pago_fijo_cap,
            'monto_interes' => $monto_interes,
            'monto_cobrado' => $monto_cobrado,
            'saldo_pendiente' => $saldo_pendiente,
            'breadcrumbs' => [
                "Inicio" => url("/"),
                "Préstamos" => url("/movimientos/prestamos"),
                "Abonos" => url("/movimientos/prestamos/{$prestamo->id_prestamo}/abonos"),
                "Agregar" => "",
            ]
        ]);
    }

    public function abonosAgregarPost(Request $request)
    {
        $validated = $request->validate([
            'id_prestamo' => 'required|integer',
            'num_abono' => 'required|integer',
            'fecha' => 'required|date',
            'monto_capital' => 'required|numeric',
            'monto_interes' => 'required|numeric',
            'monto_cobrado' => 'required|numeric',
            'saldo_pendiente' => 'required|numeric'
        ]);

        $abono = new Abono([
            'fk_id_prestamo' => $validated['id_prestamo'],
            'num_abono' => $validated['num_abono'],
            'fecha' => $validated['fecha'],
            'monto_capital' => $validated['monto_capital'],
            'monto_interes' => $validated['monto_interes'],
            'monto_cobrado' => $validated['monto_cobrado'],
            'saldo_pendiente' => $validated['saldo_pendiente']
        ]);
        $abono->save();

        return redirect()->route('catalogos.abonos', ['id_prestamo' => $validated['id_prestamo']])
            ->with('success', 'Abono agregado exitosamente');
    }
    // Termina Abonos

    // Prestamos
    public function prestamosAgregarPost(Request $request)
    {
        $id_empleado=$request->input("id_empleado");
        $monto=$request->input("monto");
        $puesto=Puesto::join("detalle_empleado_puesto", "puesto.id_puesto", "=", "detalle_empleado_puesto.id_puesto")
            ->where("detalle_empleado_puesto.id_empleado","=",$id_empleado)
            ->whereNull("detalle_empleado_puesto.fecha_fin")->first();
        $sueldox6=$puesto->sueldo*6;
        if ($monto>$sueldox6){
            return view("/error",["error"=>"La solicitud excede el monto permitido"]);
        }
        $fecha_solicitud=$request->input("fecha_solicitud");
        $plazo=$request->input("plazo");
        $fecha_aprobacion=$request->input("fecha_aprobacion");
        $tasa_mensual=$request->input("tasa_mensual");
        $pago_fijo=$request->input("pago_fijo");
        $fecha_inicio_descuento=$request->input("fecha_inicio_descuento");
        $fecha_fin_descuento=$request->input("fecha_fin_descuento");
        $saldo=$request->input("saldo");
        $estado=$request->input("estado");
        $prestamo=new Prestamo([
            "id_empleado"=>$id_empleado,
            "fecha_solicitud"=>$fecha_solicitud,
            "monto"=>$monto,
            "plazo"=>$plazo,
            "fecha_aprobacion"=>$fecha_aprobacion,
            "tasa_mensual"=>$tasa_mensual,
            "pago_fijo"=>$pago_fijo,
            "fecha_inicio_descuento"=>$fecha_inicio_descuento,
            "fecha_fin_descuento"=>$fecha_fin_descuento,
            "saldo"=>$saldo,
            "estado"=>$estado,
        ]);
        $prestamo->save();
        return redirect("/movimientos/prestamos"); // redirige al listado de prestamos
    }
    // Termina Prestamos

    // Empleados
    public function empleadosAgregarPost(Request $request)
    {
        $puesto = Puesto::find($request->input('puesto_id'));

        $empleado = new Empleado([
            'nombre'       => strtoupper($request->input('nombre')),
            'apellidoP'    => strtoupper($request->input('apellidoP')),
            'apellidoM'    => strtoupper($request->input('apellidoM')),
            'fecha_inicio' => $request->input('fecha_inicio'),
            'fk_id_puesto' => $puesto->id_puesto,
            'sueldo'       => $puesto->sueldo
        ]);
        $empleado->save();

        $detallePuesto = new DetEmpPuesto([
            'fk_id_puesto'   => $puesto->id_puesto,
            'fk_id_empleado' => $empleado->id_empleado,
            'fecha_inicio'   => $request->input('fecha_inicio')
        ]);
        $detallePuesto->fecha_inicio = $request->input('fecha_inicio'); // Asegurar que la fecha se guarde correctamente
        $detallePuesto->save();

        return redirect('/catalogos/empleados');
    }
    // Termina Empleados
}
