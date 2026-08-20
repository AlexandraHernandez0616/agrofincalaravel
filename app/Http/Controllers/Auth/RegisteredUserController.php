<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SolicitudRegistro;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration / request view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request from a worker.
     * The worker is NOT activated immediately; a pending request is created for the Mayordomo to review.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'string', 'max:50', 'unique:usuarios,documento'],
            'telefono' => ['required', 'string', 'max:30'],
            'eps' => ['required', 'string', 'max:100'],
            'RH' => ['required', 'string', 'max:10'],
            'username' => ['required', 'string', 'max:50', 'unique:usuarios,username'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'documento.unique' => 'Este número de documento ya se encuentra registrado en el sistema.',
            'username.unique' => 'Este nombre de usuario ya está en uso. Por favor elige otro.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe contener al menos :min caracteres.',
        ]);

        // Verificar si ya existe una solicitud PENDIENTE con este documento o usuario
        $solicitudExistente = SolicitudRegistro::where('estado', 'PENDIENTE')
            ->where(function ($q) use ($validated) {
                $q->where('documento', $validated['documento'])
                  ->orWhere('username', $validated['username']);
            })
            ->first();

        if ($solicitudExistente) {
            throw ValidationException::withMessages([
                'documento' => 'Ya existe una solicitud pendiente de revisión con este documento o nombre de usuario.',
            ]);
        }

        // Crear la solicitud de registro para aprobación del Mayordomo
        SolicitudRegistro::create([
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'documento' => $validated['documento'],
            'telefono' => $validated['telefono'],
            'eps' => $validated['eps'],
            'rh' => $validated['RH'],
            'username' => $validated['username'],
            'password_hash' => Hash::make($validated['password']),
            'estado' => 'PENDIENTE',
            'fecha_solicitud' => now(),
        ]);

        return redirect()->route('login')->with('status', '¡Solicitud de registro enviada con éxito! Tu solicitud está en revisión. El Mayordomo debe aprobar tu acceso antes de que puedas ingresar al sistema.');
    }
}
