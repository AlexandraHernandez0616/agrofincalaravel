<?php

namespace App\Http\Controllers\Mayordomo;

use App\Http\Controllers\Controller;
use App\Models\BitacoraOperacion;
use App\Models\SolicitudRegistro;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SolicitudController extends Controller
{
    /**
     * Display pending registration requests for the Mayordomo.
     */
    public function index(): View
    {
        $solicitudes = SolicitudRegistro::where('estado', 'PENDIENTE')
            ->orderBy('id_solicitud', 'desc')
            ->get();

        return view('mayordomo.solicitudes.index', compact('solicitudes'));
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

            // 2. Crear el perfil laboral en la tabla trabajadores
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
                'observacion' => $request->input('observacion', 'Solicitud aprobada por el Mayordomo.'),
            ]);

            // 4. Registro en Bitácora
            try {
                BitacoraOperacion::log(
                    'Aprobación Solicitud',
                    'Trabajadores',
                    "El mayordomo aprobó la solicitud de registro del trabajador: {$solicitud->nombres} {$solicitud->apellidos} (Doc: {$solicitud->documento})",
                    Auth::id()
                );
            } catch (\Exception $e) {
                // Silencioso
            }
        });

        return back()->with('success', "La solicitud de {$solicitud->nombres} {$solicitud->apellidos} ha sido aprobada exitosamente. El trabajador ya puede acceder.");
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

        $motivo = $request->input('observacion', 'Solicitud rechazada por el Mayordomo.');

        $solicitud->update([
            'estado' => 'RECHAZADA',
            'id_mayordomo' => Auth::id(),
            'fecha_gestion' => now(),
            'observacion' => $motivo,
        ]);

        // Registro en Bitácora
        try {
            BitacoraOperacion::log(
                'Rechazo Solicitud',
                'Trabajadores',
                "El mayordomo rechazó la solicitud de registro del trabajador: {$solicitud->nombres} {$solicitud->apellidos}. Motivo: {$motivo}",
                Auth::id()
            );
        } catch (\Exception $e) {
            // Silencioso
        }

        return back()->with('success', "La solicitud de {$solicitud->nombres} {$solicitud->apellidos} ha sido rechazada.");
    }
}
