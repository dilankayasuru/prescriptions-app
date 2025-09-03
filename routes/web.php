<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QuotationController;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [PrescriptionController::class, 'index'])->name('home');
    Route::get('dashboard', [PrescriptionController::class, 'index'])->name('dashboard');

    // Prescriptions — controller routes
    Route::get('prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
    Route::get('prescriptions/create', [PrescriptionController::class, 'create'])->name('prescriptions.create');
    Route::post('prescriptions', [PrescriptionController::class, 'store'])->name('prescriptions.store');
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->name('prescriptions.show');
    Route::delete('prescriptions/{prescription}', [PrescriptionController::class, 'destroy'])->name('prescriptions.destroy');

    // Quotations - controller routes
    Route::get('quotations', [QuotationController::class, 'index'])->name('quotations');
    Route::get('quotations/create', [QuotationController::class, 'create'])->middleware(RoleMiddleware::class . ':admin')->name('quotations.create');
    Route::post('quotations', [QuotationController::class, 'store'])->middleware(RoleMiddleware::class . ':admin')->name('quotations.store');
    Route::get('quotations/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('quotations/{quotation}/edit', [QuotationController::class, 'edit'])->middleware(RoleMiddleware::class . ':admin')->name('quotations.edit');
    Route::put('quotations/{quotation}', [QuotationController::class, 'update'])->middleware(RoleMiddleware::class . ':admin')->name('quotations.update');
    Route::patch('quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.updateStatus');
    Route::delete('quotations/{quotation}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
});

require __DIR__ . '/auth.php';
