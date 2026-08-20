<?php

namespace App\Http\Controllers;

use App\Models\Herramienta;
use App\Models\Insumo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class InventarioController extends Controller
{
    /**
     * Display inventory management dashboard (Herramientas & Insumos).
     */
    public function index(Request $request): View
    {
        $tab = $request->input('tab', 'herramientas');

        $herramientas = Herramienta::orderBy('id_herramienta', 'desc')->get();
        $insumos = Insumo::orderBy('id_insumo', 'desc')->get();

        // KPIs de Inventario
        $kpiDisponibles = Herramienta::where(function ($q) {
            $q->where('estado', 'Disponible')->orWhereNull('estado');
        })->count();

        $kpiMantenimiento = Herramienta::where('estado', 'En Mantenimiento')->count();

        $kpiDanadas = Herramienta::whereIn('estado', ['Dañada', 'Danada', 'Dañadas'])->count();

        $kpiInsumosAlerta = Insumo::where(function ($q) {
            $q->whereColumn('stock_actual', '<=', 'cantidad_minima')
              ->orWhere(function ($vq) {
                  $vq->whereNotNull('fecha_vencimiento')
                     ->where('fecha_vencimiento', '<=', now()->toDateString());
              });
        })->count();

        return view('admin.inventario.index', compact(
            'herramientas',
            'insumos',
            'tab',
            'kpiDisponibles',
            'kpiMantenimiento',
            'kpiDanadas',
            'kpiInsumosAlerta'
        ));
    }

    /**
     * Store a new herramienta in Bodega 1.
     */
    public function storeHerramienta(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'cantidad_total' => ['required', 'integer', 'min:0'],
            'estado' => ['required', 'string', 'max:50'],
            'fecha_registro' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ], [
            'nombre.required' => 'El nombre de la herramienta es obligatorio.',
            'cantidad_total.required' => 'La cantidad total es obligatoria.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $destPath = public_path('uploads/herramientas');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move($destPath, $fileName);
            $fotoPath = 'uploads/herramientas/' . $fileName;
        }

        Herramienta::create([
            'nombre' => $validated['nombre'],
            'cantidad_total' => $validated['cantidad_total'],
            'estado' => $validated['estado'],
            'foto_referencia' => $fotoPath,
            'fecha_registro' => $validated['fecha_registro'] ?? now()->toDateString(),
        ]);

        return redirect()->route('inventario.index', ['tab' => 'herramientas'])->with('success', 'Herramienta registrada exitosamente en Bodega 1.');
    }

    /**
     * Update an existing herramienta in Bodega 1.
     */
    public function updateHerramienta(Request $request, int $id): RedirectResponse
    {
        $herramienta = Herramienta::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'cantidad_total' => ['required', 'integer', 'min:0'],
            'estado' => ['required', 'string', 'max:50'],
            'fecha_registro' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'cantidad_total' => $validated['cantidad_total'],
            'estado' => $validated['estado'],
            'fecha_registro' => $validated['fecha_registro'] ?? $herramienta->fecha_registro,
        ];

        if ($request->hasFile('foto')) {
            $destPath = public_path('uploads/herramientas');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move($destPath, $fileName);
            $data['foto_referencia'] = 'uploads/herramientas/' . $fileName;
        }

        $herramienta->update($data);

        return redirect()->route('inventario.index', ['tab' => 'herramientas'])->with('success', 'Herramienta actualizada exitosamente.');
    }

    /**
     * Remove a herramienta from Bodega 1.
     */
    public function destroyHerramienta(int $id): RedirectResponse
    {
        $herramienta = Herramienta::findOrFail($id);
        $herramienta->delete();

        return redirect()->route('inventario.index', ['tab' => 'herramientas'])->with('success', 'Herramienta eliminada del inventario.');
    }

    /**
     * Store a new insumo in Bodega 2.
     */
    public function storeInsumo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'cantidad_minima' => ['required', 'numeric', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'fecha_registro' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ], [
            'nombre.required' => 'El nombre del insumo es obligatorio.',
            'stock_actual.required' => 'El stock actual es obligatorio.',
            'unidad_medida.required' => 'La unidad de medida es obligatoria.',
            'cantidad_minima.required' => 'El stock mínimo es obligatorio.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $destPath = public_path('uploads/insumos');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move($destPath, $fileName);
            $fotoPath = 'uploads/insumos/' . $fileName;
        }

        Insumo::create([
            'nombre' => $validated['nombre'],
            'stock_actual' => $validated['stock_actual'],
            'unidad_medida' => $validated['unidad_medida'],
            'cantidad_minima' => $validated['cantidad_minima'],
            'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? null,
            'foto_referencia' => $fotoPath,
            'fecha_registro' => $validated['fecha_registro'] ?? now()->toDateString(),
        ]);

        return redirect()->route('inventario.index', ['tab' => 'insumos'])->with('success', 'Insumo registrado exitosamente en Bodega 2.');
    }

    /**
     * Update an existing insumo in Bodega 2.
     */
    public function updateInsumo(Request $request, int $id): RedirectResponse
    {
        $insumo = Insumo::findOrFail($id);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'stock_actual' => ['required', 'numeric', 'min:0'],
            'unidad_medida' => ['required', 'string', 'max:50'],
            'cantidad_minima' => ['required', 'numeric', 'min:0'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'fecha_registro' => ['nullable', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'stock_actual' => $validated['stock_actual'],
            'unidad_medida' => $validated['unidad_medida'],
            'cantidad_minima' => $validated['cantidad_minima'],
            'fecha_vencimiento' => $validated['fecha_vencimiento'] ?? null,
            'fecha_registro' => $validated['fecha_registro'] ?? $insumo->fecha_registro,
        ];

        if ($request->hasFile('foto')) {
            $destPath = public_path('uploads/insumos');
            if (!File::exists($destPath)) {
                File::makeDirectory($destPath, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->move($destPath, $fileName);
            $data['foto_referencia'] = 'uploads/insumos/' . $fileName;
        }

        $insumo->update($data);

        return redirect()->route('inventario.index', ['tab' => 'insumos'])->with('success', 'Insumo actualizado exitosamente.');
    }

    /**
     * Remove an insumo from Bodega 2.
     */
    public function destroyInsumo(int $id): RedirectResponse
    {
        $insumo = Insumo::findOrFail($id);
        $insumo->delete();

        return redirect()->route('inventario.index', ['tab' => 'insumos'])->with('success', 'Insumo eliminado del inventario.');
    }
}
