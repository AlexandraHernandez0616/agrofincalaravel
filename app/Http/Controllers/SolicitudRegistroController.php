<?php

namespace App\Http\Controllers;

use App\Models\SolicitudRegistro;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SolicitudRegistroController extends Controller
{
    /**
     * Display a listing of registration requests.
     */
    public function index(Request $request): View
    {
        $estado = $request->input('estado', 'PENDIENTE');

        $query = SolicitudRegistro::with('mayordomo');

        if (!empty($estado) && strtolower($estado) !== 'todos') {
            $query->where('estado', strtoupper($estado));
        }

        $solicitudes = $query->orderBy('id_solicitud', 'desc')->paginate(15)->withQueryString();

        return view('admin.solicitudes.index', compact('solicitudes', 'estado'));
    }

    /**
     * Approve a worker registration request.
     */
    public function aprobar(Request $request, int $id): RedirectResponse
    {
        $solicitud = SolicitudRegistro::findOrFail($id);

        if ($solicitud->estado !== 'PENDIENTE') {
            return back()->with('error', 'Esta solicitud ya ha sido gestionada previamente.');
        }

        DB::transaction(function () use ($solicitud, $request) {
            // 1. Crear el usuario en la tabla usuarios con rol TRABAJADOR
            $user = User::create([
                'nombres' => $solicitud->nombres,
                'apellidos' => $solicitud->apellidos,
                'documento' => $solicitud->documento,
                'telefono' => $solicitud->telefono,
                'username' => $solicitud->username,
                'password_hash' => $solicitud->password_hash,
                'rol' => 'TRABAJADOR',
                'activo' => true,
                'fecha_creacion' => now(),
            ]);

            // 2. Crear el registro laboral en la tabla trabajadores
            Trabajador::create([
                'id_usuario' => $user->id_usuario,
                'eps' => $solicitud->eps,
                'rh' => $solicitud->rh,
                'estado_trabajador' => 'ACTIVO',
                'fecha_ingreso' => now()->toDateString(),
                'hora_registro' => now(),
            ]);

            // 3. Actualizar la solicitud a APROBADA
            $solicitud->update([
                'estado' => 'APROBADA',
                'id_mayordomo' => Auth::id(),
                'fecha_gestion' => now(),
                'observacion' => $request->input('observacion', 'Solicitud aprobada exitosamente por el Mayordomo.'),
            ]);
        });

        return back()->with('success', "La solicitud de {$solicitud->name} ha sido aprobada. El trabajador ya puede iniciar sesión.");
    }

    /**
     * Reject a worker registration request.
     */
    public function rechazar(Request $request, int $id): RedirectResponse
    {
        $solicitud = SolicitudRegistro::findOrFail($id);

        if ($solicitud->estado !== 'PENDIENTE') {
            return back()->with('error', 'Esta solicitud ya ha sido gestionada previamente.');
        }

        $solicitud->update([
            'estado' => 'RECHAZADA',
            'id_mayordomo' => Auth::id(),
            'fecha_gestion' => now(),
            'observacion' => $request->input('observacion', 'Solicitud rechazada.'),
        ]);

        return back()->with('success', "La solicitud de {$solicitud->name} ha sido rechazada.");
    }
}
