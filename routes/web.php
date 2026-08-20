<?php

use App\Http\Controllers\AutorizacionDelegadaController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\LiquidacionController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MayordomoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\Mayordomo\DashboardController as MayordomoDashboardController;
use App\Http\Controllers\Mayordomo\SolicitudController as MayordomoSolicitudController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudRegistroController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\Trabajador\DashboardController as TrabajadorDashboardController;
use App\Http\Controllers\TrabajadorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// =========================================================================
// RUTA CENTRAL DE DASHBOARD (REDIRECCIÓN DINÁMICA POR ROL)
// =========================================================================
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $rol = strtoupper(trim(Auth::user()->rol ?? ''));

    // 1. Si es Administrador -> Dashboard Administrador
    if ($rol === 'ADMINISTRADOR' || $rol === 'ADMIN') {
        return view('admin.dashboard');
    }

    // 2. Si es Mayordomo -> Dashboard Mayordomo
    if ($rol === 'MAYORDOMO') {
        return redirect()->route('mayordomo.dashboard');
    }

    // 3. Si es Trabajador -> Dashboard Trabajador
    if ($rol === 'TRABAJADOR') {
        return redirect()->route('trabajador.dashboard');
    }

    // 4. Si no es ni Administrador, ni Mayordomo, ni Trabajador -> Denegar y cerrar sesión
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('login')
        ->withErrors(['username' => 'Acceso denegado: No cuentas con un rol autorizado en el sistema.']);
})->middleware(['auth', 'verified'])->name('dashboard');

// =========================================================================
// RUTAS AUTENTICADAS GENERALES (PERFIL DE USUARIO)
// =========================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================================
// RUTAS DEL ADMINISTRADOR (TODOS LOS MÓDULOS DE GESTIÓN Y AUDITORÍA)
// =========================================================================
Route::middleware(['auth', 'role:ADMINISTRADOR'])->group(function () {
    
    // Módulo Mayordomos
    Route::get('/mayordomos', [MayordomoController::class, 'index'])->name('mayordomos.index');
    Route::post('/mayordomos', [MayordomoController::class, 'store'])->name('mayordomos.store');
    Route::put('/mayordomos/{mayordomo}', [MayordomoController::class, 'update'])->name('mayordomos.update');
    Route::patch('/mayordomos/{mayordomo}/toggle-status', [MayordomoController::class, 'toggleStatus'])->name('mayordomos.toggle-status');
    Route::delete('/mayordomos/{mayordomo}', [MayordomoController::class, 'destroy'])->name('mayordomos.destroy');

    // Módulo Trabajadores
    Route::get('/trabajadores', [TrabajadorController::class, 'index'])->name('trabajadores.index');
    Route::put('/trabajadores/{trabajador}', [TrabajadorController::class, 'update'])->name('trabajadores.update');
    Route::patch('/trabajadores/{trabajador}/toggle-status', [TrabajadorController::class, 'toggleStatus'])->name('trabajadores.toggle-status');
    Route::delete('/trabajadores/{trabajador}', [TrabajadorController::class, 'destroy'])->name('trabajadores.destroy');

    // Solicitudes de Registro
    Route::get('/solicitudes-registro', [SolicitudRegistroController::class, 'index'])->name('solicitudes.index');
    Route::patch('/solicitudes-registro/{id}/aprobar', [SolicitudRegistroController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::patch('/solicitudes-registro/{id}/rechazar', [SolicitudRegistroController::class, 'rechazar'])->name('solicitudes.rechazar');

    // Módulo Gestión de Inventarios (Herramientas e Insumos)
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario/herramientas', [InventarioController::class, 'storeHerramienta'])->name('inventario.herramientas.store');
    Route::put('/inventario/herramientas/{id}', [InventarioController::class, 'updateHerramienta'])->name('inventario.herramientas.update');
    Route::delete('/inventario/herramientas/{id}', [InventarioController::class, 'destroyHerramienta'])->name('inventario.herramientas.destroy');
    Route::post('/inventario/insumos', [InventarioController::class, 'storeInsumo'])->name('inventario.insumos.store');
    Route::put('/inventario/insumos/{id}', [InventarioController::class, 'updateInsumo'])->name('inventario.insumos.update');
    Route::delete('/inventario/insumos/{id}', [InventarioController::class, 'destroyInsumo'])->name('inventario.insumos.destroy');

    // Módulo Lotes, Cultivos y Producción
    Route::get('/cultivos', [LoteController::class, 'index'])->name('cultivos.index');
    Route::get('/lotes', [LoteController::class, 'index'])->name('lotes.index');
    Route::get('/producciones', [LoteController::class, 'index'])->name('producciones.index');

    // Lotes CRUD
    Route::post('/lotes', [LoteController::class, 'storeLote'])->name('lotes.store');
    Route::put('/lotes/{lote}', [LoteController::class, 'updateLote'])->name('lotes.update');
    Route::delete('/lotes/{lote}', [LoteController::class, 'destroyLote'])->name('lotes.destroy');

    // Cultivos CRUD
    Route::post('/cultivos', [LoteController::class, 'storeCultivo'])->name('cultivos.store');
    Route::put('/cultivos/{cultivo}', [LoteController::class, 'updateCultivo'])->name('cultivos.update');
    Route::delete('/cultivos/{cultivo}', [LoteController::class, 'destroyCultivo'])->name('cultivos.destroy');

    // Producciones CRUD
    Route::post('/producciones', [LoteController::class, 'storeProduccion'])->name('producciones.store');
    Route::put('/producciones/{produccion}', [LoteController::class, 'updateProduccion'])->name('producciones.update');
    Route::delete('/producciones/{produccion}', [LoteController::class, 'destroyProduccion'])->name('producciones.destroy');

    // Módulo Tarifas de Pago
    Route::get('/tarifas', [TarifaController::class, 'index'])->name('tarifas.index');
    Route::post('/tarifas', [TarifaController::class, 'store'])->name('tarifas.store');
    Route::put('/tarifas/{tarifa}', [TarifaController::class, 'update'])->name('tarifas.update');
    Route::patch('/tarifas/{tarifa}/toggle-status', [TarifaController::class, 'toggleStatus'])->name('tarifas.toggle-status');
    Route::delete('/tarifas/{tarifa}', [TarifaController::class, 'destroy'])->name('tarifas.destroy');

    // Módulo Liquidaciones
    Route::get('/liquidaciones', [LiquidacionController::class, 'index'])->name('liquidaciones.index');
    Route::post('/liquidaciones', [LiquidacionController::class, 'store'])->name('liquidaciones.store');
    Route::put('/liquidaciones/{id}', [LiquidacionController::class, 'update'])->name('liquidaciones.update');
    Route::patch('/liquidaciones/{id}/estado/{estado}', [LiquidacionController::class, 'cambiarEstado'])->name('liquidaciones.cambiar-estado');
    Route::delete('/liquidaciones/{id}', [LiquidacionController::class, 'destroy'])->name('liquidaciones.destroy');

    // Módulo Pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
    Route::delete('/pagos/{id}', [PagoController::class, 'destroy'])->name('pagos.destroy');

    // Módulo Autorizaciones Delegadas (Liquidaciones Temporales)
    Route::get('/autorizaciones', [AutorizacionDelegadaController::class, 'index'])->name('autorizaciones.index');
    Route::post('/autorizaciones', [AutorizacionDelegadaController::class, 'store'])->name('autorizaciones.store');
    Route::patch('/autorizaciones/{id}/revocar', [AutorizacionDelegadaController::class, 'revocar'])->name('autorizaciones.revocar');
    Route::patch('/autorizaciones/{id}/reactivar', [AutorizacionDelegadaController::class, 'reactivar'])->name('autorizaciones.reactivar');
    Route::delete('/autorizaciones/{id}', [AutorizacionDelegadaController::class, 'destroy'])->name('autorizaciones.destroy');

    // Módulo Bitácora de Operaciones (Auditoría)
    Route::get('/bitacoras', [BitacoraController::class, 'index'])->name('bitacoras.index');
    Route::delete('/bitacoras/limpiar', [BitacoraController::class, 'limpiar'])->name('bitacoras.limpiar');

    // Módulo Reportes del Sistema
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/export/excel', [ReporteController::class, 'exportExcel'])->name('reportes.export.excel');
});

// =========================================================================
// RUTAS DEL ROL MAYORDOMO
// =========================================================================
Route::middleware(['auth', 'role:MAYORDOMO'])->prefix('mayordomo')->name('mayordomo.')->group(function () {
    Route::get('/dashboard', [MayordomoDashboardController::class, 'index'])->name('dashboard');

    // Módulo Solicitudes de Registro (Aprobación y Rechazo)
    Route::get('/solicitudes', [MayordomoSolicitudController::class, 'index'])->name('solicitudes.index');
    Route::patch('/solicitudes/{id}/aprobar', [MayordomoSolicitudController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::patch('/solicitudes/{id}/rechazar', [MayordomoSolicitudController::class, 'rechazar'])->name('solicitudes.rechazar');
});

// =========================================================================
// RUTAS DEL ROL TRABAJADOR
// =========================================================================
Route::middleware(['auth', 'role:TRABAJADOR'])->prefix('trabajador')->name('trabajador.')->group(function () {
    Route::get('/dashboard', [TrabajadorDashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
