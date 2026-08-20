<?php

namespace App\Http\Controllers;

use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TrabajadorController extends Controller
{
    /**
     * Display a listing of workers.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $estado = $request->input('estado');

        $query = Trabajador::with('usuario');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('usuario', function ($uq) use ($search) {
                    $uq->where('nombres', 'like', "%{$search}%")
                       ->orWhere('apellidos', 'like', "%{$search}%")
                       ->orWhere('documento', 'like', "%{$search}%")
                       ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('eps', 'like', "%{$search}%");
            });
        }

        if (!empty($estado) && strtolower($estado) !== 'todos') {
            $query->where('estado_trabajador', strtoupper($estado));
        }

        $trabajadores = $query->orderBy('id_trabajador', 'desc')->paginate(15)->withQueryString();

        return view('admin.trabajadores.index', compact('trabajadores', 'search', 'estado'));
    }

    /**
     * Update the specified worker in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $trabajador = Trabajador::with('usuario')->findOrFail($id);
        $userId = $trabajador->usuario?->id_usuario;

        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'string', 'max:50', Rule::unique('usuarios', 'documento')->ignore($userId, 'id_usuario')],
            'telefono' => ['nullable', 'string', 'max:30'],
            'username' => ['nullable', 'string', 'max:50', Rule::unique('usuarios', 'username')->ignore($userId, 'id_usuario')],
            'password' => ['nullable', 'string', 'min:6'],
            'eps' => ['nullable', 'string', 'max:100'],
            'rh' => ['nullable', 'string', 'max:10'],
            'estado_trabajador' => ['nullable', 'string'],
            'fecha_ingreso' => ['nullable', 'date'],
        ], [
            'documento.unique' => 'El número de documento ya pertenece a otro usuario.',
            'username.unique' => 'El nombre de usuario ya está ocupado.',
        ]);

        DB::transaction(function () use ($trabajador, $validated, $request) {
            $estado = $request->input('estado_trabajador', $trabajador->estado_trabajador);
            $isActive = strtoupper($estado) === 'ACTIVO';

            // 1. Actualizar usuario asociado
            if ($trabajador->usuario) {
                $userData = [
                    'nombres' => $validated['nombres'],
                    'apellidos' => $validated['apellidos'],
                    'documento' => $validated['documento'],
                    'telefono' => $validated['telefono'] ?? null,
                    'activo' => $isActive,
                ];

                if (!empty($validated['username'])) {
                    $userData['username'] = $validated['username'];
                }

                if (!empty($validated['password'])) {
                    $userData['password_hash'] = Hash::make($validated['password']);
                }

                $trabajador->usuario->update($userData);
            }

            // 2. Actualizar datos específicos en tabla trabajadores
            $trabajador->update([
                'eps' => $validated['eps'] ?? null,
                'rh' => $validated['rh'] ?? null,
                'estado_trabajador' => strtoupper($estado),
                'fecha_ingreso' => $validated['fecha_ingreso'] ?? $trabajador->fecha_ingreso,
            ]);
        });

        return redirect()->route('trabajadores.index')->with('success', 'Información del trabajador y usuario actualizada exitosamente.');
    }

    /**
     * Toggle active status of worker.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $trabajador = Trabajador::with('usuario')->findOrFail($id);
        $nuevoEstado = $trabajador->is_active ? 'INACTIVO' : 'ACTIVO';

        DB::transaction(function () use ($trabajador, $nuevoEstado) {
            $trabajador->estado_trabajador = $nuevoEstado;
            $trabajador->save();

            if ($trabajador->usuario) {
                $trabajador->usuario->activo = ($nuevoEstado === 'ACTIVO');
                $trabajador->usuario->save();
            }
        });

        $msg = $nuevoEstado === 'ACTIVO' ? 'activado' : 'desactivado';
        return redirect()->route('trabajadores.index')->with('success', "Trabajador {$msg} correctamente.");
    }

    /**
     * Remove the specified worker from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $trabajador = Trabajador::with('usuario')->findOrFail($id);

        DB::transaction(function () use ($trabajador) {
            $user = $trabajador->usuario;
            $trabajador->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('trabajadores.index')->with('success', 'Trabajador y usuario eliminados del sistema.');
    }
}
