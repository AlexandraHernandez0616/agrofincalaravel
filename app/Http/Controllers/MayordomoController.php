<?php

namespace App\Http\Controllers;

use App\Models\Mayordomo;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MayordomoController extends Controller
{
    /**
     * Display a listing of mayordomos.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $query = Mayordomo::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'like', "%{$search}%")
                  ->orWhere('apellidos', 'like', "%{$search}%")
                  ->orWhere('documento', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $mayordomos = $query->orderBy('id_usuario', 'desc')->paginate(15)->withQueryString();

        return view('admin.mayordomos.index', compact('mayordomos', 'search'));
    }

    /**
     * Store a newly created mayordomo in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'string', 'max:50', 'unique:usuarios,documento'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'username' => ['required', 'string', 'max:50', 'unique:usuarios,username'],
            'password' => ['required', 'string', 'min:6'],
            'activo' => ['nullable'],
        ], [
            'documento.unique' => 'El documento ya está registrado en el sistema.',
            'username.unique' => 'El nombre de usuario ya se encuentra en uso.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
        ]);

        User::create([
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'documento' => $validated['documento'],
            'telefono' => $validated['telefono'] ?? null,
            'username' => $validated['username'],
            'password_hash' => Hash::make($validated['password']),
            'rol' => 'MAYORDOMO',
            'activo' => $request->has('activo') ? (bool) $request->input('activo') : true,
            'fecha_creacion' => now(),
        ]);

        return redirect()->route('mayordomos.index')->with('success', 'Mayordomo registrado exitosamente.');
    }

    /**
     * Update the specified mayordomo in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $mayordomo = User::where('rol', 'MAYORDOMO')->findOrFail($id);

        $validated = $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'documento' => ['required', 'string', 'max:50', Rule::unique('usuarios', 'documento')->ignore($id, 'id_usuario')],
            'telefono' => ['nullable', 'string', 'max:30'],
            'username' => ['required', 'string', 'max:50', Rule::unique('usuarios', 'username')->ignore($id, 'id_usuario')],
            'password' => ['nullable', 'string', 'min:6'],
            'activo' => ['nullable'],
        ], [
            'documento.unique' => 'El documento ya está registrado en el sistema.',
            'username.unique' => 'El nombre de usuario ya se encuentra en uso.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
        ]);

        $data = [
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'documento' => $validated['documento'],
            'telefono' => $validated['telefono'] ?? null,
            'username' => $validated['username'],
            'activo' => $request->has('activo') ? (bool) $request->input('activo') : false,
        ];

        if (!empty($validated['password'])) {
            $data['password_hash'] = Hash::make($validated['password']);
        }

        $mayordomo->update($data);

        return redirect()->route('mayordomos.index')->with('success', 'Mayordomo actualizado exitosamente.');
    }

    /**
     * Toggle active status of mayordomo.
     */
    public function toggleStatus(int $id): RedirectResponse
    {
        $mayordomo = User::where('rol', 'MAYORDOMO')->findOrFail($id);
        $mayordomo->activo = !$mayordomo->activo;
        $mayordomo->save();

        $estado = $mayordomo->activo ? 'activado' : 'desactivado';
        return redirect()->route('mayordomos.index')->with('success', "Mayordomo {$estado} correctamente.");
    }

    /**
     * Remove the specified mayordomo from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $mayordomo = User::where('rol', 'MAYORDOMO')->findOrFail($id);
        $mayordomo->delete();

        return redirect()->route('mayordomos.index')->with('success', 'Mayordomo eliminado correctamente.');
    }
}
