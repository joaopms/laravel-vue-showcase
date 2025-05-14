<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');

Route::name('public')->group(function () {
    Route::post('/appointment', [PublicController::class, 'scheduleAppointment'])
        ->name('.schedule-appointment')
        ->middleware([HandlePrecognitiveRequests::class]);
});

Route::name('dashboard')
    ->prefix('dashboard')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/', function () {
            // Temporary until we have a proper dashboard
            return to_route('dashboard.appointments.index');
            // return Inertia::render('dashboard/Index');
        });

        Route::get('/appointments', [AppointmentController::class, 'index'])->name('.appointments.index');
        Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('.appointments.show');
        Route::put('/appointments/{appointment}', [AppointmentController::class, 'update'])
            ->name('.appointments.update')
            ->middleware([HandlePrecognitiveRequests::class]);
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
