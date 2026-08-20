<?php

namespace App\Http\Controllers;

use App\Models\Liquidacion;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PagoController extends Controller
{
    /**
     * Display the payments management module.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $metodo = $request->input('metodo', 'todos');

        $query = Pago::with(['liquidacion.trabajador.usuario', 'liquidacion.tarifa', 'registrador']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id_pago', 'like', "%{$search}%")
                  ->orWhere('referencia_pago', 'like', "%{$search}%")
                  ->orWhereHas('liquidacion.trabajador.usuario', function ($uq) use ($search) {
                      $uq->where('nombres', 'like', "%{$search}%")
                         ->orWhere('apellidos', 'like', "%{$search}%")
                         ->orWhere('documento', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($metodo) && strtolower($metodo) !== 'todos') {
            $query->where('metodo_pago', 'like', "%{$metodo}%");
        }

        $pagos = $query->orderBy('id_pago', 'desc')->get();

        // Tarjetas KPI
        $totalPagos = Pago::count();
        $montoTotalRaw = Pago::sum('monto');
        $montoTotalFormatted = '$' . number_format((float)$montoTotalRaw, 0, '.', ',');
        $porTransferencia = Pago::where('metodo_pago', 'like', '%Transferencia%')->count();
        $porEfectivo = Pago::where('metodo_pago', 'like', '%Efectivo%')->count();

        // Liquidaciones disponibles para registrar nuevos pagos
        $liquidacionesDisponibles = Liquidacion::with(['trabajador.usuario', 'tarifa'])
            ->orderBy('id_liquidacion', 'desc')
            ->get();

        $metodosDisponibles = ['Efectivo', 'Transferencia', 'Nequi / Daviplata', 'Cheque'];

        return view('admin.pagos.index', compact(
            'pagos',
            'search',
            'metodo',
            'totalPagos',
            'montoTotalFormatted',
            'porTransferencia',
            'porEfectivo',
            'liquidacionesDisponibles',
            'metodosDisponibles'
        ));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_liquidacion' => ['required', 'exists:liquidaciones,id_liquidacion'],
            'fecha_pago' => ['required', 'date'],
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'referencia_pago' => ['nullable', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ], [
            'id_liquidacion.required' => 'Debes seleccionar una liquidación a pagar.',
            'fecha_pago.required' => 'La fecha de pago es obligatoria.',
            'monto.required' => 'El monto a pagar es obligatorio.',
            'metodo_pago.required' => 'Debes seleccionar el método de pago.',
        ]);

        $liquidacion = Liquidacion::findOrFail($validated['id_liquidacion']);

        Pago::create([
            'id_liquidacion' => $liquidacion->id_liquidacion,
            'id_autorizacion' => $liquidacion->id_autorizacion ?? null,
            'id_usuario_registrador' => Auth::id() ?? 1,
            'fecha_pago' => $validated['fecha_pago'],
            'monto' => $validated['monto'],
            'metodo_pago' => $validated['metodo_pago'],
            'referencia_pago' => $validated['referencia_pago'] ?? null,
            'observacion' => $validated['observacion'] ?? null,
        ]);

        // Actualizar liquidación a estado LIQUIDADA
        $liquidacion->update([
            'estado' => 'LIQUIDADA',
            'fecha_liquidacion' => $validated['fecha_pago'],
        ]);

        return redirect()->route('pagos.index')
            ->with('success', '¡Pago registrado y liquidación completada exitosamente!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')
            ->with('success', '¡Registro de pago eliminado exitosamente!');
    }
}
