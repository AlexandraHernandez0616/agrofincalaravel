<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TarifaController extends Controller
{
    /**
     * Display the payment rates management module.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $estado = $request->input('estado', 'todos');
        $tipo = $request->input('tipo', 'todos');

        $query = Tarifa::query();

        if (!empty($search)) {
            $query->where('tipo_pago', 'like', "%{$search}%");
        }

        if ($estado === 'ACTIVA' || $estado === 'activa') {
            $query->where('activa', 1);
        } elseif ($estado === 'INACTIVA' || $estado === 'inactiva') {
            $query->where('activa', 0);
        }

        if (!empty($tipo) && $tipo !== 'todos') {
            $query->where('tipo_pago', $tipo);
        }

        $tarifas = $query->orderBy('id_tarifa', 'desc')->get();

        // KPIs de Tarifas
        $totalTarifas = Tarifa::count();
        $tarifasActivas = Tarifa::where('activa', 1)->count();
        $tarifasInactivas = Tarifa::where('activa', 0)->count();

        // Tipos disponibles para el dropdown
        $tiposDisponibles = Tarifa::select('tipo_pago')
            ->distinct()
            ->pluck('tipo_pago');

        return view('admin.tarifas.index', compact(
            'tarifas',
            'search',
            'estado',
            'tipo',
            'totalTarifas',
            'tarifasActivas',
            'tarifasInactivas',
            'tiposDisponibles'
        ));
    }

    /**
     * Store a newly created tariff in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tipo_pago' => ['required', 'string', 'max:50'],
            'valor' => ['required', 'numeric', 'min:0'],
            'fecha_inicio_vigencia' => ['required', 'date'],
            'fecha_fin_vigencia' => ['nullable', 'date', 'after_or_equal:fecha_inicio_vigencia'],
            'activa' => ['nullable', 'boolean'],
        ], [
            'tipo_pago.required' => 'El tipo de tarifa es obligatorio.',
            'valor.required' => 'El valor monetario es obligatorio.',
            'fecha_inicio_vigencia.required' => 'La fecha de inicio de vigencia es obligatoria.',
            'fecha_fin_vigencia.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        Tarifa::create([
            'tipo_pago' => ucfirst(trim($validated['tipo_pago'])),
            'valor' => $validated['valor'],
            'fecha_inicio_vigencia' => $validated['fecha_inicio_vigencia'],
            'fecha_fin_vigencia' => $validated['fecha_fin_vigencia'] ?? null,
            'activa' => $request->has('activa') ? 1 : 0,
        ]);

        return redirect()->route('tarifas.index')
            ->with('success', '¡Tarifa de pago registrada exitosamente!');
    }

    /**
     * Update the specified tariff in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $tarifa = Tarifa::findOrFail($id);

        $validated = $request->validate([
            'tipo_pago' => ['required', 'string', 'max:50'],
            'valor' => ['required', 'numeric', 'min:0'],
            'fecha_inicio_vigencia' => ['required', 'date'],
            'fecha_fin_vigencia' => ['nullable', 'date', 'after_or_equal:fecha_inicio_vigencia'],
            'activa' => ['nullable', 'boolean'],
        ], [
            'tipo_pago.required' => 'El tipo de tarifa es obligatorio.',
            'valor.required' => 'El valor monetario es obligatorio.',
            'fecha_inicio_vigencia.required' => 'La fecha de inicio de vigencia es obligatoria.',
            'fecha_fin_vigencia.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        $tarifa->update([
            'tipo_pago' => ucfirst(trim($validated['tipo_pago'])),
            'valor' => $validated['valor'],
            'fecha_inicio_vigencia' => $validated['fecha_inicio_vigencia'],
            'fecha_fin_vigencia' => $validated['fecha_fin_vigencia'] ?? null,
            'activa' => $request->has('activa') ? 1 : 0,
        ]);

        return redirect()->route('tarifas.index')
            ->with('success', '¡Tarifa actualizada correctamente!');
    }

    /**
     * Toggle active / inactive status of a tariff.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $tarifa = Tarifa::findOrFail($id);
        $tarifa->activa = $tarifa->activa ? 0 : 1;
        $tarifa->save();

        $estadoTxt = $tarifa->activa ? 'habilitada' : 'deshabilitada';

        return redirect()->route('tarifas.index')
            ->with('success', "La tarifa '{$tarifa->tipo_pago}' ha sido {$estadoTxt} correctamente.");
    }

    /**
     * Remove the specified tariff from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $tarifa = Tarifa::findOrFail($id);
        $tarifa->delete();

        return redirect()->route('tarifas.index')
            ->with('success', '¡Tarifa eliminada exitosamente!');
    }
}
