<?php

namespace App\Http\Controllers;

use App\Models\Cultivo;
use App\Models\Lote;
use App\Models\Produccion;
use App\Models\Trabajador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoteController extends Controller
{
    /**
     * Display the main Lotes, Cultivos y Producción dashboard.
     */
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'lotes');
        if (!in_array($tab, ['lotes', 'cultivos', 'produccion'])) {
            $tab = 'lotes';
        }

        // Lotes con sus relaciones cargadas
        $lotes = Lote::with(['cultivo', 'producciones.trabajador.usuario'])
            ->orderBy('id_lote', 'asc')
            ->get();

        // Cultivos disponibles
        $cultivos = Cultivo::withCount('lotes')
            ->orderBy('id_cultivo', 'asc')
            ->get();

        // Trabajadores disponibles para registro de cosechas/producción
        $trabajadores = Trabajador::with('usuario')
            ->orderBy('id_trabajador', 'desc')
            ->get();

        // Cálculo de KPIs
        $totalLotes = $lotes->count();
        
        $extensionSum = (float) $lotes->sum('extension');
        $extensionTotal = ((int)$extensionSum == $extensionSum ? (int)$extensionSum : number_format($extensionSum, 2)) . ' ha';

        // Conteo por tipo de cultivo para las tarjetas KPI
        $lotesCafe = $lotes->filter(function ($lote) {
            return str_contains(strtolower($lote->cultivo?->nombre ?? ''), 'caf');
        })->count();

        $lotesCacao = $lotes->filter(function ($lote) {
            return str_contains(strtolower($lote->cultivo?->nombre ?? ''), 'cacao');
        })->count();

        // Producción global
        $produccionGlobal = Produccion::sum('cantidad');

        return view('admin.lotes.index', compact(
            'lotes',
            'cultivos',
            'trabajadores',
            'tab',
            'totalLotes',
            'extensionTotal',
            'lotesCafe',
            'lotesCacao',
            'produccionGlobal'
        ));
    }

    /**
     * Store a newly created lot in storage.
     */
    public function storeLote(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'ubicacion_descripcion' => ['required', 'string', 'max:150'],
            'extension' => ['required', 'numeric', 'min:0.01'],
            'id_cultivo' => ['required', 'exists:cultivos,id_cultivo'],
            'fecha_registro' => ['nullable', 'date'],
        ], [
            'nombre.required' => 'El nombre del lote es obligatorio.',
            'ubicacion_descripcion.required' => 'La ubicación del lote es obligatoria.',
            'extension.required' => 'La extensión en hectáreas es obligatoria.',
            'id_cultivo.required' => 'Debes asociar un cultivo al lote.',
        ]);

        Lote::create([
            'nombre' => $validated['nombre'],
            'ubicacion_descripcion' => $validated['ubicacion_descripcion'],
            'extension' => $validated['extension'],
            'id_cultivo' => $validated['id_cultivo'],
            'fecha_registro' => $validated['fecha_registro'] ?? now()->toDateString(),
        ]);

        return redirect()->route('cultivos.index', ['tab' => 'lotes'])
            ->with('success', '¡Lote registrado exitosamente!');
    }

    /**
     * Update the specified lot in storage.
     */
    public function updateLote(Request $request, int $id): RedirectResponse
    {
        $lote = Lote::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'ubicacion_descripcion' => ['required', 'string', 'max:150'],
            'extension' => ['required', 'numeric', 'min:0.01'],
            'id_cultivo' => ['required', 'exists:cultivos,id_cultivo'],
            'fecha_registro' => ['nullable', 'date'],
        ]);

        $lote->update([
            'nombre' => $validated['nombre'],
            'ubicacion_descripcion' => $validated['ubicacion_descripcion'],
            'extension' => $validated['extension'],
            'id_cultivo' => $validated['id_cultivo'],
            'fecha_registro' => $validated['fecha_registro'] ?? $lote->fecha_registro,
        ]);

        return redirect()->route('cultivos.index', ['tab' => 'lotes'])
            ->with('success', '¡Lote actualizado correctamente!');
    }

    /**
     * Remove the specified lot from storage.
     */
    public function destroyLote(int $id): RedirectResponse
    {
        $lote = Lote::findOrFail($id);

        // Eliminar producciones asociadas al lote
        $lote->producciones()->delete();
        $lote->delete();

        return redirect()->route('cultivos.index', ['tab' => 'lotes'])
            ->with('success', '¡Lote eliminado correctamente!');
    }

    /**
     * Store a newly created crop type in storage.
     */
    public function storeCultivo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'variedad' => ['required', 'string', 'max:100'],
            'cantidad_cultivo' => ['required', 'numeric', 'min:0'],
            'fecha_registro' => ['nullable', 'date'],
            'estado' => ['required', 'string', 'max:20'],
        ], [
            'nombre.required' => 'El nombre del cultivo es obligatorio.',
            'variedad.required' => 'La variedad del cultivo es obligatoria.',
        ]);

        Cultivo::create([
            'nombre' => strtolower(trim($validated['nombre'])),
            'variedad' => $validated['variedad'],
            'cantidad_cultivo' => $validated['cantidad_cultivo'],
            'fecha_registro' => $validated['fecha_registro'] ?? now()->toDateString(),
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('cultivos.index', ['tab' => 'cultivos'])
            ->with('success', '¡Cultivo registrado exitosamente!');
    }

    /**
     * Update the specified crop type.
     */
    public function updateCultivo(Request $request, int $id): RedirectResponse
    {
        $cultivo = Cultivo::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'variedad' => ['required', 'string', 'max:100'],
            'cantidad_cultivo' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'string', 'max:20'],
        ]);

        $cultivo->update([
            'nombre' => strtolower(trim($validated['nombre'])),
            'variedad' => $validated['variedad'],
            'cantidad_cultivo' => $validated['cantidad_cultivo'],
            'estado' => $validated['estado'],
        ]);

        return redirect()->route('cultivos.index', ['tab' => 'cultivos'])
            ->with('success', '¡Cultivo actualizado correctamente!');
    }

    /**
     * Remove the specified crop type.
     */
    public function destroyCultivo(int $id): RedirectResponse
    {
        $cultivo = Cultivo::findOrFail($id);

        if ($cultivo->lotes()->count() > 0) {
            return redirect()->route('cultivos.index', ['tab' => 'cultivos'])
                ->withErrors(['error' => 'No puedes eliminar este cultivo porque tiene lotes asignados.']);
        }

        $cultivo->delete();

        return redirect()->route('cultivos.index', ['tab' => 'cultivos'])
            ->with('success', '¡Cultivo eliminado correctamente!');
    }

    /**
     * Store a newly created harvest / production record.
     */
    public function storeProduccion(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_lote' => ['required', 'exists:lotes,id_lote'],
            'id_trabajador' => ['required', 'exists:trabajadores,id_trabajador'],
            'fecha' => ['required', 'date'],
            'cantidad' => ['required', 'numeric', 'min:0.01'],
            'unidad_medida' => ['required', 'string', 'max:50'],
        ], [
            'id_lote.required' => 'Debes seleccionar el lote cosechado.',
            'id_trabajador.required' => 'Debes seleccionar el trabajador responsable.',
            'cantidad.required' => 'La cantidad producida es obligatoria.',
        ]);

        Produccion::create($validated);

        return redirect()->route('cultivos.index', ['tab' => 'produccion'])
            ->with('success', '¡Registro de producción guardado con éxito!');
    }

    /**
     * Update a harvest / production record.
     */
    public function updateProduccion(Request $request, int $id): RedirectResponse
    {
        $produccion = Produccion::findOrFail($id);

        $validated = $request->validate([
            'id_lote' => ['required', 'exists:lotes,id_lote'],
            'id_trabajador' => ['required', 'exists:trabajadores,id_trabajador'],
            'fecha' => ['required', 'date'],
            'cantidad' => ['required', 'numeric', 'min:0.01'],
            'unidad_medida' => ['required', 'string', 'max:50'],
        ]);

        $produccion->update($validated);

        return redirect()->route('cultivos.index', ['tab' => 'produccion'])
            ->with('success', '¡Registro de producción actualizado!');
    }

    /**
     * Delete a production record.
     */
    public function destroyProduccion(int $id): RedirectResponse
    {
        $produccion = Produccion::findOrFail($id);
        $produccion->delete();

        return redirect()->route('cultivos.index', ['tab' => 'produccion'])
            ->with('success', '¡Registro de producción eliminado!');
    }
}
