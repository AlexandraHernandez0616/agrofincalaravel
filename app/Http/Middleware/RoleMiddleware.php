<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request by verifying user roles.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = strtoupper(Auth::user()->rol ?? '');

        // Standardize roles array to uppercase
        $allowedRoles = array_map('strtoupper', $roles);

        // Validar si el rol es reconocido en el sistema
        $validRoles = ['ADMINISTRADOR', 'ADMIN', 'MAYORDOMO', 'TRABAJADOR'];
        if (!in_array($userRole, $validRoles, true)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors(['username' => 'Acceso denegado: Rol de usuario no autorizado en el sistema.']);
        }

        // Administrator has access to all modules unless explicitly restricted
        if ($userRole === 'ADMINISTRADOR' || $userRole === 'ADMIN') {
            return $next($request);
        }

        if (in_array($userRole, $allowedRoles, true)) {
            return $next($request);
        }

        // Si es Mayordomo e intenta acceder a sección restringida
        if ($userRole === 'MAYORDOMO') {
            return redirect()->route('mayordomo.dashboard')
                ->with('error', 'No tienes permisos de administrador para acceder a esa sección.');
        }

        // Si es Trabajador e intenta acceder a sección restringida
        if ($userRole === 'TRABAJADOR') {
            return redirect()->route('trabajador.dashboard')
                ->with('error', 'Tu rol solo tiene acceso a tu panel de trabajador.');
        }

        abort(403, 'Acceso denegado. No tienes los permisos requeridos para esta sección.');
    }
}
