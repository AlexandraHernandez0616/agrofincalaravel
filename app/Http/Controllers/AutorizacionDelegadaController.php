<?php

namespace App\Http\Controllers;

use App\Models\AutorizacionDelegada;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AutorizacionDelegadaController extends Controller
{
    /**
     * Display the temporary liquidation permissions (autorizaciones delegadas).
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $estado = $request->input('estado', 'todos');

        // Actualizar automáticamente a VENCIDA las autorizaciones cuya fecha_fin ya pasó
        AutorizacionDelegada::where('estado', 'ACTIVA')
            ->where('fecha_fin', '<', now()->toDateString())
            ->update(['estado' => 'VENCIDA']);

        $query = AutorizacionDelegada::with(['administrador', 'mayordomo', 'liquidaciones'])
            ->withCount('liquidaciones');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('mayordomo', function ($mq) use ($search) {
                    $mq->where('nombres', 'like', "%{$search}%")
                       ->orWhere('apellidos', 'like', "%{$search}%")
                       ->orWhere('documento', 'like', "%{$search}%");
                })->orWhereHas('administrador', function ($aq) use ($search) {
                    $aq->where('nombres', 'like', "%{$search}%")
                       ->orWhere('apellidos', 'like', "%{$search}%");
                })->orWhere('acciones_permitidas', 'like', "%{$search}%");
            });
        }

        if (!empty($estado) && strtolower($estado) !== 'todos') {
            $query->where('estado', strtoupper($estado));
        }

        $autorizaciones = $query->orderBy('id_autorizacion', 'desc')->get();

        // Tarjetas KPI
        $totalPermisos = AutorizacionDelegada::count();
        $activos = AutorizacionDelegada::where('estado', 'ACTIVA')->count();
        $expirados = AutorizacionDelegada::where('estado', 'VENCIDA')->count();
        $revocados = AutorizacionDelegada::where('estado', 'REVOCADA')->count();

        // Lista de mayordomos disponibles para asignar permisos
        $mayordomos = User::where('rol', 'MAYORDOMO')
            ->where('activo', 1)
            ->get();

        return view('admin.autorizaciones.index', compact(
            'autorizaciones',
            'search',
            'estado',
            'totalPermisos',
            'activos',
            'expirados',
            'revocados',
            'mayordomos'
        ));
    }

    /**
     * Store a newly granted temporary authorization.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_mayordomo' => ['required', 'exists:usuarios,id_usuario'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'acciones_permitidas' => ['required', 'string', 'max:255'],
            'monto_maximo' => ['nullable', 'numeric', 'min:0'],
        ], [
            'id_mayordomo.required' => 'Debes seleccionar un Mayordomo.',
            'fecha_inicio.required' => 'La fecha de inicio de vigencia es obligatoria.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la de inicio.',
            'acciones_permitidas.required' => 'Las acciones permitidas son obligatorias.',
        ]);

        AutorizacionDelegada::create([
            'id_administrador' => Auth::id() ?? 1,
            'id_mayordomo' => $validated['id_mayordomo'],
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin'],
            'acciones_permitidas' => $validated['acciones_permitidas'],
            'monto_maximo' => $validated['monto_maximo'] ?? null,
            'estado' => 'ACTIVA',
        ]);

        return redirect()->route('autorizaciones.index')
            ->with('success', '¡Permiso temporal otorgado exitosamente al Mayordomo!');
    }

    /**
     * Revoke an active temporary authorization.
     */
    public function revocar(int $id): RedirectResponse
    {
        $autorizacion = AutorizacionDelegada::with('mayordomo')->findOrFail($id);
        $autorizacion->estado = 'REVOCADA';
        $autorizacion->save();

        $nombre = $autorizacion->mayordomo?->name ?? 'Mayordomo';

        return redirect()->route('autorizaciones.index')
            ->with('success', "El permiso temporal para {$nombre} ha sido Revocado.");
    }

    /**
     * Reactivate a revoked or expired authorization.
     */
    public function reactivar(Request $request, int $id): RedirectResponse
    {
        $autorizacion = AutorizacionDelegada::with('mayordomo')->findOrFail($id);
        $autorizacion->estado = 'ACTIVA';
        if ($autorizacion->fecha_fin < now()->toDateString()) {
            $autorizacion->fecha_fin = now()->addDays(2)->toDateString();
        }
        $autorizacion->save();

        $nombre = $autorizacion->mayordomo?->name ?? 'Mayordomo';

        return redirect()->route('autorizaciones.index')
            ->with('success', "El permiso temporal para {$nombre} ha sido Reactivado exitosamente.");
    }

    /**
     * Remove the specified authorization from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $autorizacion = AutorizacionDelegada::findOrFail($id);
        $autorizacion->delete();

        return redirect()->route('autorizaciones.index')
            ->with('success', '¡Registro de autorización eliminado correctamente!');
    }
}
