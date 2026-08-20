<?php

namespace App\Http\Controllers;

use App\Models\Liquidacion;
use App\Models\Tarifa;
use App\Models\Trabajador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LiquidacionController extends Controller
{
    /**
     * Display the liquidations management module.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $estado = $request->input('estado', 'todos');
        $tipo = $request->input('tipo', 'todos');

        $query = Liquidacion::with(['trabajador.usuario', 'tarifa']);

        if (!empty($search)) {
            $query->whereHas('trabajador.usuario', function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%");
            });
        }

        if (!empty($estado) && strtolower($estado) !== 'todos') {
            $query->where('estado', strtoupper($estado));
        }

        if (!empty($tipo) && strtolower($tipo) !== 'todos') {
            $query->whereHas('tarifa', function ($q) use ($tipo) {
                $q->where('tipo_pago', $tipo);
            });
        }

        $liquidaciones = $query->orderBy('id_liquidacion', 'desc')->get();

        // Métricas KPI
        $totalLiquidaciones = Liquidacion::count();
        $pendientes = Liquidacion::where('estado', 'PENDIENTE')->count();
        $generadas = Liquidacion::where('estado', 'GENERADA')->count();
        $liquidadas = Liquidacion::where('estado', 'LIQUIDADA')->count();

        // Datos para formularios y filtros
        $trabajadores = Trabajador::with('usuario')
            ->where('estado_trabajador', 'ACTIVO')
            ->get();

        $tarifas = Tarifa::where('activa', 1)->get();

        $tiposDisponibles = Tarifa::select('tipo_pago')
            ->distinct()
            ->pluck('tipo_pago');

        return view('admin.liquidaciones.index', compact(
            'liquidaciones',
            'search',
            'estado',
            'tipo',
            'totalLiquidaciones',
            'pendientes',
            'generadas',
            'liquidadas',
            'trabajadores',
            'tarifas',
            'tiposDisponibles'
        ));
    }

    /**
     * Store a newly created liquidation.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_trabajador' => ['required', 'exists:trabajadores,id_trabajador'],
            'id_tarifa' => ['required', 'exists:tarifas,id_tarifa'],
            'periodo_inicio' => ['required', 'date'],
            'periodo_fin' => ['required', 'date', 'after_or_equal:periodo_inicio'],
            'jornadas_consideradas' => ['nullable', 'numeric', 'min:0'],
            'produccion_considerada' => ['nullable', 'numeric', 'min:0'],
            'valor_calculado' => ['nullable', 'numeric', 'min:0'],
            'estado' => ['nullable', 'string', 'in:PENDIENTE,GENERADA,LIQUIDADA'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ], [
            'id_trabajador.required' => 'Debes seleccionar un trabajador.',
            'id_tarifa.required' => 'Debes seleccionar el tipo de tarifa aplicada.',
            'periodo_inicio.required' => 'La fecha de inicio de período es obligatoria.',
            'periodo_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
        ]);

        $tarifa = Tarifa::findOrFail($validated['id_tarifa']);
        $jornadas = (float)($validated['jornadas_consideradas'] ?? 1);
        $produccion = (float)($validated['produccion_considerada'] ?? 0);

        // Si no ingresó valor manual, calcular automáticamente
        if (empty($validated['valor_calculado']) || $validated['valor_calculado'] <= 0) {
            if ($produccion > 0) {
                $valorCalculado = $produccion * (float)$tarifa->valor;
            } else {
                $valorCalculado = $jornadas * (float)$tarifa->valor;
            }
        } else {
            $valorCalculado = (float)$validated['valor_calculado'];
        }

        $estado = $validated['estado'] ?? 'PENDIENTE';
        $fechaLiquidacion = ($estado === 'LIQUIDADA') ? now()->toDateString() : null;

        Liquidacion::create([
            'id_trabajador' => $validated['id_trabajador'],
            'id_tarifa' => $validated['id_tarifa'],
            'periodo_inicio' => $validated['periodo_inicio'],
            'periodo_fin' => $validated['periodo_fin'],
            'jornadas_consideradas' => $jornadas,
            'produccion_considerada' => $produccion,
            'valor_calculado' => $valorCalculado,
            'fecha_generacion' => now()->toDateString(),
            'fecha_liquidacion' => $fechaLiquidacion,
            'estado' => $estado,
            'observacion' => $validated['observacion'] ?? null,
        ]);

        return redirect()->route('liquidaciones.index')
            ->with('success', '¡Liquidación generada exitosamente!');
    }

    /**
     * Update the specified liquidation in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $liquidacion = Liquidacion::findOrFail($id);

        $validated = $request->validate([
            'id_trabajador' => ['required', 'exists:trabajadores,id_trabajador'],
            'id_tarifa' => ['required', 'exists:tarifas,id_tarifa'],
            'periodo_inicio' => ['required', 'date'],
            'periodo_fin' => ['required', 'date', 'after_or_equal:periodo_inicio'],
            'jornadas_consideradas' => ['nullable', 'numeric', 'min:0'],
            'produccion_considerada' => ['nullable', 'numeric', 'min:0'],
            'valor_calculado' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'string', 'in:PENDIENTE,GENERADA,LIQUIDADA'],
            'observacion' => ['nullable', 'string', 'max:500'],
        ], [
            'id_trabajador.required' => 'Debes seleccionar un trabajador.',
            'id_tarifa.required' => 'Debes seleccionar el tipo de tarifa aplicada.',
            'periodo_inicio.required' => 'La fecha de inicio de período es obligatoria.',
            'periodo_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
            'valor_calculado.required' => 'El valor a liquidar es obligatorio.',
        ]);

        $fechaLiquidacion = ($validated['estado'] === 'LIQUIDADA' && empty($liquidacion->fecha_liquidacion)) 
            ? now()->toDateString() 
            : $liquidacion->fecha_liquidacion;

        $liquidacion->update([
            'id_trabajador' => $validated['id_trabajador'],
            'id_tarifa' => $validated['id_tarifa'],
            'periodo_inicio' => $validated['periodo_inicio'],
            'periodo_fin' => $validated['periodo_fin'],
            'jornadas_consideradas' => $validated['jornadas_consideradas'] ?? 0,
            'produccion_considerada' => $validated['produccion_considerada'] ?? 0,
            'valor_calculado' => $validated['valor_calculado'],
            'fecha_liquidacion' => $fechaLiquidacion,
            'estado' => $validated['estado'],
            'observacion' => $validated['observacion'] ?? null,
        ]);

        return redirect()->route('liquidaciones.index')
            ->with('success', '¡Liquidación actualizada correctamente!');
    }

    /**
     * Change liquidation state (e.g. GENERADA or LIQUIDADA).
     */
    public function cambiarEstado(Request $request, int $id, string $nuevoEstado): RedirectResponse
    {
        $nuevoEstado = strtoupper($nuevoEstado);
        if (!in_array($nuevoEstado, ['PENDIENTE', 'GENERADA', 'LIQUIDADA'])) {
            return back()->with('error', 'Estado de liquidación no válido.');
        }

        $liquidacion = Liquidacion::with('trabajador.usuario')->findOrFail($id);
        $liquidacion->estado = $nuevoEstado;

        if ($nuevoEstado === 'LIQUIDADA') {
            $liquidacion->fecha_liquidacion = now()->toDateString();
        }

        $liquidacion->save();

        $nombre = $liquidacion->trabajador?->usuario?->name ?? 'Trabajador';
        $mensaje = match ($nuevoEstado) {
            'GENERADA' => "La liquidación de {$nombre} ha sido Generada con éxito.",
            'LIQUIDADA' => "La liquidación de {$nombre} ha sido Liquidada exitosamente.",
            default => "Estado de liquidación actualizado a {$nuevoEstado}."
        };

        return redirect()->route('liquidaciones.index')->with('success', $mensaje);
    }

    /**
     * Remove the specified liquidation from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $liquidacion = Liquidacion::findOrFail($id);
        $liquidacion->delete();

        return redirect()->route('liquidaciones.index')
            ->with('success', '¡Liquidación eliminada correctamente!');
    }
}
