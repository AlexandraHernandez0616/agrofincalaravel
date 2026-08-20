<?php

namespace App\Http\Controllers\Mayordomo;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\AutorizacionDelegada;
use App\Models\Cultivo;
use App\Models\Lote;
use App\Models\NotificacionOperativa;
use App\Models\Prestamo;
use App\Models\Produccion;
use App\Models\SolicitudRegistro;
use App\Models\Tarea;
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

        // ── Auto-expirar permisos delegados vencidos ──
        AutorizacionDelegada::where('estado', 'ACTIVA')
            ->where('fecha_fin', '<', now()->toDateString())
            ->update(['estado' => 'EXPIRADA']);

        // ── Fila 1 de Métricas: Personal y Asistencia ──
        $trabajadoresActivos = Trabajador::where('estado_trabajador', 'ACTIVO')->count();
        $trabajadoresEnLabor = Trabajador::whereIn('estado_trabajador', ['En labor', 'EN_LABOR', 'EN LABOR'])->count();
        $asistenciaHoy = Asistencia::whereDate('fecha', now()->toDateString())->count();

        // ── Fila 2 de Métricas: Operaciones y Tareas ──
        $solicitudesPendientes = SolicitudRegistro::where('estado', 'PENDIENTE')->count();
        $tareasPendientes = Tarea::where('id_mayordomo', $mayordomoId)->where('estado_tarea', 'PENDIENTE')->count();
        $tareasEnProgreso = Tarea::where('id_mayordomo', $mayordomoId)->where('estado_tarea', 'EN_PROGRESO')->count();

        // ── Fila 3 de Métricas: Bodega y Cosecha ──
        $prestamosPendientes = Prestamo::where('id_mayordomo', $mayordomoId)->where('estado_prestamo', 'PENDIENTE')->count();
        $produccionHoy = (float) Produccion::whereDate('fecha', now()->toDateString())->sum('cantidad');
        $lotesTotal = Lote::count();
        $cultivosTotal = Cultivo::count();

        // ── Permiso Delegado Activo (Liquidaciones Temporales) ──
        $permisoActivo = AutorizacionDelegada::where('id_mayordomo', $mayordomoId)
            ->where('estado', 'ACTIVA')
            ->where('fecha_fin', '>=', now()->toDateString())
            ->first();

        // ── Notificaciones Operativas Recientes ──
        $notificaciones = NotificacionOperativa::where('id_usuario_destino', $mayordomoId)
            ->orderBy('fecha_hora', 'desc')
            ->take(5)
            ->get();

        // ── Asistencias Recientes ──
        $asistenciasRecientes = Asistencia::with('trabajador.usuario')
            ->orderBy('id_asistencia', 'desc')
            ->take(5)
            ->get();

        // ── Cosechas Recientes ──
        $cosechasRecientes = Produccion::with(['lote.cultivo', 'trabajador.usuario'])
            ->orderBy('id_produccion', 'desc')
            ->take(5)
            ->get();

        return view('mayordomo.dashboard', compact(
            'trabajadoresActivos',
            'trabajadoresEnLabor',
            'asistenciaHoy',
            'solicitudesPendientes',
            'tareasPendientes',
            'tareasEnProgreso',
            'prestamosPendientes',
            'produccionHoy',
            'lotesTotal',
            'cultivosTotal',
            'permisoActivo',
            'notificaciones',
            'asistenciasRecientes',
            'cosechasRecientes'
        ));
    }
}
