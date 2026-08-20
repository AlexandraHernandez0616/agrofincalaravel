<?php

namespace App\Http\Controllers\Trabajador;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\Liquidacion;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Trabajador dashboard.
     */
    public function index(): View
    {
        $userId = Auth::id();
        $trabajador = Trabajador::with('usuario')->where('id_usuario', $userId)->first();

        $asistenciasCount = 0;
        $liquidacionesCount = 0;
        $asistenciasRecientes = collect();
        $liquidacionesRecientes = collect();

        if ($trabajador) {
            $asistenciasCount = Asistencia::where('id_trabajador', $trabajador->id_trabajador)->count();
            $liquidacionesCount = Liquidacion::where('id_trabajador', $trabajador->id_trabajador)->count();

            $asistenciasRecientes = Asistencia::where('id_trabajador', $trabajador->id_trabajador)
                ->orderBy('fecha', 'desc')
                ->take(7)
                ->get();

            $liquidacionesRecientes = Liquidacion::with('tarifa')
                ->where('id_trabajador', $trabajador->id_trabajador)
                ->orderBy('id_liquidacion', 'desc')
                ->take(5)
                ->get();
        }

        return view('trabajador.dashboard', compact(
            'trabajador',
            'asistenciasCount',
            'liquidacionesCount',
            'asistenciasRecientes',
            'liquidacionesRecientes'
        ));
    }
}
