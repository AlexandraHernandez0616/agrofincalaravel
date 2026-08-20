<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminLayout extends Component
{
    public ?string $title;

    /**
     * Create a new component instance.
     */
    public function __construct(?string $title = null)
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represents the component dynamically based on authenticated user role.
     */
    public function render(): View
    {
        $rol = strtoupper(trim(Auth::user()->rol ?? ''));

        // Si el usuario autenticado es Mayordomo -> Renderiza con el Layout y Sidebar del Mayordomo
        if ($rol === 'MAYORDOMO') {
            return view('mayordomo.layouts.master');
        }

        // Si el usuario autenticado es Trabajador -> Renderiza con el Layout del Trabajador
        if ($rol === 'TRABAJADOR') {
            return view('trabajador.layouts.master');
        }

        // Por defecto -> Layout del Administrador
        return view('admin.layouts.master');
    }
}
