<?php

use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PublicController::class, 'home'])->name('home');

Route::name('public.')->group(function () {
    Route::post('/appointment', [PublicController::class, 'scheduleAppointment'])
        ->name('schedule-appointment')
        ->middleware([HandlePrecognitiveRequests::class]);
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
