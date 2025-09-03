<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PrescriptionController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Prescriptions — controller routes
    Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
    Route::get('prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');

    Route::view('orders', 'orders.index')->name('orders')->middleware(RoleMiddleware::class . ':admin');
    Route::view('quotations', 'quotations.index')->name('quotations');
});

require __DIR__ . '/auth.php';
