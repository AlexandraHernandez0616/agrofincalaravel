<?php

namespace App\Http\Controllers\Mayordomo;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\AutorizacionDelegada;
use App\Models\Lote;
use App\Models\Produccion;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Mayordomo operational dashboard.
     */
    public function index(): View
    {
        $mayordomoId = Auth::id();

        // 1. Métricas Operativas
        $trabajadoresActivos = Trabajador::where('estado_trabajador', 'ACTIVO')->count();
        $asistenciasHoy = Asistencia::whereDate('fecha', now()->toDateString())->count();
        $lotesActivos = Lote::count();

        // 2. Permiso Delegado Activo para este Mayordomo
        $permisoActivo = AutorizacionDelegada::where('id_mayordomo', $mayordomoId)
            ->where('estado', 'ACTIVA')
            ->where('fecha_fin', '>=', now()->toDateString())
            ->first();

        // 3. Asistencias recientes de hoy
        $asistenciasRecientes = Asistencia::with('trabajador.usuario')
            ->orderBy('id_asistencia', 'desc')
            ->take(5)
            ->get();

        // 4. Cosechas recientes
        $cosechasRecientes = Produccion::with(['lote.cultivo', 'trabajador.usuario'])
            ->orderBy('id_produccion', 'desc')
            ->take(5)
            ->get();

        return view('mayordomo.dashboard', compact(
            'trabajadoresActivos',
            'asistenciasHoy',
            'lotesActivos',
            'permisoActivo',
            'asistenciasRecientes',
            'cosechasRecientes'
        ));
    }
}
