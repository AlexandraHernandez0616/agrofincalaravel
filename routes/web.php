<?php

use App\Http\Controllers\MayordomoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Módulo Mayordomos
    Route::get('/mayordomos', [MayordomoController::class, 'index'])->name('mayordomos.index');
    Route::post('/mayordomos', [MayordomoController::class, 'store'])->name('mayordomos.store');
    Route::put('/mayordomos/{mayordomo}', [MayordomoController::class, 'update'])->name('mayordomos.update');
    Route::patch('/mayordomos/{mayordomo}/toggle-status', [MayordomoController::class, 'toggleStatus'])->name('mayordomos.toggle-status');
    Route::delete('/mayordomos/{mayordomo}', [MayordomoController::class, 'destroy'])->name('mayordomos.destroy');
});

require __DIR__.'/auth.php';

