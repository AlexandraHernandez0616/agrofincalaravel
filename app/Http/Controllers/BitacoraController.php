<?php

namespace App\Http\Controllers;

use App\Models\BitacoraOperacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BitacoraController extends Controller
{
    /**
     * Display the audit log (Bitácora de Operaciones).
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $modulo = $request->input('modulo', 'todos');
        $accion = $request->input('accion', 'todos');

        $query = BitacoraOperacion::with('usuario');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('operacion', 'like', "%{$search}%")
                  ->orWhere('modulo', 'like', "%{$search}%")
                  ->orWhere('detalle', 'like', "%{$search}%")
                  ->orWhereHas('usuario', function ($uq) use ($search) {
                      $uq->where('nombres', 'like', "%{$search}%")
                         ->orWhere('apellidos', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%")
                         ->orWhere('rol', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($modulo) && strtolower($modulo) !== 'todos') {
            $query->where('modulo', 'like', "%{$modulo}%");
        }

        if (!empty($accion) && strtolower($accion) !== 'todos') {
            $query->where('operacion', 'like', "%{$accion}%");
        }

        $bitacoras = $query->orderBy('id_bitacora', 'desc')->get();

        // Métricas KPI
        $totalRegistros = BitacoraOperacion::count();
        $operacionesHoy = BitacoraOperacion::whereDate('fecha_hora', now()->toDateString())->count();
        $usuariosActivos = BitacoraOperacion::distinct('id_usuario')->count('id_usuario');
        $modulosRegistrados = BitacoraOperacion::distinct('modulo')->count('modulo');

        // Módulos sugeridos para el filtro
        $dbModulos = BitacoraOperacion::select('modulo')->distinct()->pluck('modulo')->toArray();
        $defaultModulos = ['Liquidaciones', 'Pagos', 'Inventario', 'Lotes y Cultivos', 'Tarifas', 'Trabajadores', 'Mayordomos', 'Autorizaciones', 'Seguridad'];
        $modulosDisponibles = array_values(array_unique(array_filter(array_merge($dbModulos, $defaultModulos))));

        // Acciones sugeridas para el filtro
        $dbAcciones = BitacoraOperacion::select('operacion')->distinct()->pluck('operacion')->toArray();
        $defaultAcciones = ['Creación', 'Actualización', 'Eliminación', 'Liquidación', 'Registro de Pago', 'Aprobación', 'Revocación', 'Inicio de Sesión'];
        $accionesDisponibles = array_values(array_unique(array_filter(array_merge($dbAcciones, $defaultAcciones))));

        return view('admin.bitacoras.index', compact(
            'bitacoras',
            'search',
            'modulo',
            'accion',
            'totalRegistros',
            'operacionesHoy',
            'usuariosActivos',
            'modulosRegistrados',
            'modulosDisponibles',
            'accionesDisponibles'
        ));
    }

    /**
     * Clear bitácora logs.
     */
    public function limpiar(Request $request): RedirectResponse
    {
        BitacoraOperacion::truncate();

        BitacoraOperacion::log('Limpieza', 'Auditoría', 'Se realizó el vaciado de los registros de la bitácora.');

        return redirect()->route('bitacoras.index')
            ->with('success', '¡Bitácora de operaciones vaciada correctamente!');
    }
}
