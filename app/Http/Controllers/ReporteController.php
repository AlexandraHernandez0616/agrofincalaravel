<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Herramienta;
use App\Models\Insumo;
use App\Models\Liquidacion;
use App\Models\Pago;
use App\Models\Produccion;
use App\Models\Trabajador;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    /**
     * Display reports dashboard and generate filtered reports.
     */
    public function index(Request $request): View
    {
        $tipoReporte = $request->input('tipo_reporte', 'asistencia');
        $idTrabajador = $request->input('id_trabajador', 'todos');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $trabajadores = Trabajador::with('usuario')->get();
        $resultados = $this->obtenerDatosReporte($tipoReporte, $idTrabajador, $fechaInicio, $fechaFin);

        return view('admin.reportes.index', compact(
            'tipoReporte',
            'idTrabajador',
            'fechaInicio',
            'fechaFin',
            'trabajadores',
            'resultados'
        ));
    }

    /**
     * Helper method to query data based on report type and filters.
     */
    private function obtenerDatosReporte(string $tipoReporte, ?string $idTrabajador, ?string $fechaInicio, ?string $fechaFin)
    {
        switch ($tipoReporte) {
            case 'liquidaciones':
                $query = Liquidacion::with(['trabajador.usuario', 'tarifa']);
                if (!empty($idTrabajador) && $idTrabajador !== 'todos') {
                    $query->where('id_trabajador', $idTrabajador);
                }
                if (!empty($fechaInicio)) {
                    $query->where('periodo_inicio', '>=', $fechaInicio);
                }
                if (!empty($fechaFin)) {
                    $query->where('periodo_fin', '<=', $fechaFin);
                }
                return $query->orderBy('id_liquidacion', 'desc')->get();

            case 'pagos':
                $query = Pago::with(['liquidacion.trabajador.usuario', 'registrador']);
                if (!empty($idTrabajador) && $idTrabajador !== 'todos') {
                    $query->whereHas('liquidacion', function ($lq) use ($idTrabajador) {
                        $lq->where('id_trabajador', $idTrabajador);
                    });
                }
                if (!empty($fechaInicio)) {
                    $query->where('fecha_pago', '>=', $fechaInicio);
                }
                if (!empty($fechaFin)) {
                    $query->where('fecha_pago', '<=', $fechaFin);
                }
                return $query->orderBy('fecha_pago', 'desc')->get();

            case 'inventario':
                $herramientas = Herramienta::all()->map(function ($h) {
                    return (object)[
                        'codigo' => 'HRR-' . str_pad($h->id_herramienta, 3, '0', STR_PAD_LEFT),
                        'nombre' => $h->nombre,
                        'tipo' => 'Herramienta',
                        'cantidad' => $h->cantidad,
                        'estado' => $h->estado,
                    ];
                });
                $insumos = Insumo::all()->map(function ($i) {
                    return (object)[
                        'codigo' => 'INS-' . str_pad($i->id_insumo, 3, '0', STR_PAD_LEFT),
                        'nombre' => $i->nombre,
                        'tipo' => 'Insumo (' . $i->unidad_medida . ')',
                        'cantidad' => $i->stock_actual,
                        'estado' => $i->stock_actual > $i->stock_minimo ? 'DISPONIBLE' : 'AGOTÁNDOSE',
                    ];
                });
                return $herramientas->concat($insumos);

            case 'produccion':
                $query = Produccion::with(['lote', 'cultivo', 'usuario']);
                if (!empty($fechaInicio)) {
                    $query->where('fecha_registro', '>=', $fechaInicio);
                }
                if (!empty($fechaFin)) {
                    $query->where('fecha_registro', '<=', $fechaFin);
                }
                return $query->orderBy('fecha_registro', 'desc')->get();

            case 'asistencia':
            default:
                $query = Asistencia::with('trabajador.usuario');
                if (!empty($idTrabajador) && $idTrabajador !== 'todos') {
                    $query->where('id_trabajador', $idTrabajador);
                }
                if (!empty($fechaInicio)) {
                    $query->where('fecha', '>=', $fechaInicio);
                }
                if (!empty($fechaFin)) {
                    $query->where('fecha', '<=', $fechaFin);
                }
                return $query->orderBy('fecha', 'desc')->get();
        }
    }

    /**
     * Export report data to Excel / CSV format.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $tipoReporte = $request->input('tipo_reporte', 'asistencia');
        $idTrabajador = $request->input('id_trabajador', 'todos');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $resultados = $this->obtenerDatosReporte($tipoReporte, $idTrabajador, $fechaInicio, $fechaFin);
        $filename = "reporte_{$tipoReporte}_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($tipoReporte, $resultados) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($tipoReporte === 'asistencia') {
                fputcsv($handle, ['Fecha', 'Trabajador', 'Hora Entrada', 'Hora Salida', 'Total Horas']);
                foreach ($resultados as $row) {
                    fputcsv($handle, [
                        $row->formatted_fecha,
                        $row->trabajador_nombre,
                        $row->formatted_hora_entrada,
                        $row->formatted_hora_salida,
                        $row->total_horas,
                    ]);
                }
            } elseif ($tipoReporte === 'liquidaciones') {
                fputcsv($handle, ['ID', 'Trabajador', 'Tarifa', 'Periodo Inicio', 'Periodo Fin', 'Monto COP', 'Estado']);
                foreach ($resultados as $row) {
                    fputcsv($handle, [
                        'LIQ-' . str_pad($row->id_liquidacion, 3, '0', STR_PAD_LEFT),
                        $row->trabajador_nombre,
                        $row->tipo_tarifa_nombre,
                        $row->periodo_inicio,
                        $row->periodo_fin,
                        $row->formatted_valor,
                        $row->estado,
                    ]);
                }
            } elseif ($tipoReporte === 'pagos') {
                fputcsv($handle, ['ID', 'Trabajador', 'Liquidación', 'Fecha Pago', 'Monto COP', 'Método', 'Referencia']);
                foreach ($resultados as $row) {
                    fputcsv($handle, [
                        $row->id_pago,
                        $row->trabajador_nombre,
                        $row->liquidacion_codigo,
                        $row->formatted_fecha_pago,
                        $row->formatted_monto,
                        $row->metodo_pago,
                        $row->referencia_pago ?? '—',
                    ]);
                }
            } elseif ($tipoReporte === 'inventario') {
                fputcsv($handle, ['Código', 'Nombre', 'Tipo', 'Cantidad / Stock', 'Estado']);
                foreach ($resultados as $row) {
                    fputcsv($handle, [
                        $row->codigo,
                        $row->nombre,
                        $row->tipo,
                        $row->cantidad,
                        $row->estado,
                    ]);
                }
            } elseif ($tipoReporte === 'produccion') {
                fputcsv($handle, ['Fecha', 'Lote', 'Cultivo', 'Cantidad (Kg)', 'Registrado Por']);
                foreach ($resultados as $row) {
                    fputcsv($handle, [
                        $row->fecha_registro,
                        $row->lote?->nombre_lote ?? 'Lote',
                        $row->cultivo?->nombre_cultivo ?? 'Cultivo',
                        $row->cantidad_recolectada,
                        $row->usuario?->name ?? 'Admin',
                    ]);
                }
            }

            fclose($handle);
        }, 200, $headers);
    }
}
