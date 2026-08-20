<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\BitacoraOperacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Verificar si el usuario está activo
        if ($user && isset($user->activo) && !$user->activo) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['username' => 'Tu cuenta se encuentra inactiva. Por favor contacta al administrador.']);
        }

        $request->session()->regenerate();

        // Obtener rol normalizado
        $rol = strtoupper(trim($user->rol ?? ''));

        // Registrar inicio de sesión en bitácora
        try {
            BitacoraOperacion::log('Inicio de Sesión', 'Seguridad', "Inicio de sesión exitoso ({$rol}) desde " . $request->ip(), $user->id_usuario);
        } catch (\Exception $e) {
            // Continuar sin interrumpir el login si la bitácora falla
        }

        // 1. Verificar si es ADMINISTRADOR
        if ($rol === 'ADMINISTRADOR' || $rol === 'ADMIN') {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // 2. Si no es administrador, verificar si es MAYORDOMO
        if ($rol === 'MAYORDOMO') {
            return redirect()->intended(route('mayordomo.dashboard', absolute: false));
        }

        // 3. Si no es ni administrador ni mayordomo, verificar si es TRABAJADOR
        if ($rol === 'TRABAJADOR') {
            return redirect()->intended(route('trabajador.dashboard', absolute: false));
        }

        // 4. Si no es ni Administrador, ni Mayordomo, ni Trabajador: Denegar acceso inmediatamente
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withErrors(['username' => 'Acceso denegado: Tu cuenta no posee un rol autorizado para ingresar al sistema.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $userId = Auth::id();

        try {
            if ($userId) {
                BitacoraOperacion::log('Cierre de Sesión', 'Seguridad', 'Cierre de sesión finalizado.', $userId);
            }
        } catch (\Exception $e) {
            // Continuar
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

