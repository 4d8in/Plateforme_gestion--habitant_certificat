<?php

use App\Http\Controllers\CertificatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitantController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::middleware('verified')->group(function (): void {
        Route::resource('habitants', HabitantController::class);

            // Certificats CRUD et recherche
            Route::get('certificats', [CertificatController::class, 'index'])
                ->name('certificats.index');
        Route::get('habitants/{habitant}/certificats/create', [CertificatController::class, 'create'])
            ->name('certificats.create');
        Route::post('habitants/{habitant}/certificats', [CertificatController::class, 'store'])
            ->name('certificats.store');

        Route::get('certificats/{certificat}', [CertificatController::class, 'show'])
            ->name('certificats.show');
            Route::get('certificats/{certificat}/edit', [CertificatController::class, 'edit'])
                ->name('certificats.edit');
            Route::put('certificats/{certificat}', [CertificatController::class, 'update'])
                ->name('certificats.update');
            Route::delete('certificats/{certificat}', [CertificatController::class, 'destroy'])
                ->name('certificats.destroy');
        Route::get('certificats/{certificat}/pdf', [CertificatController::class, 'pdf'])
            ->name('certificats.pdf');

        // Routes de redirection PayDunya
        Route::get('paiements/paydunya/{certificat}/retour', [CertificatController::class, 'handleReturn'])
            ->name('paydunya.return');
        Route::get('paiements/paydunya/{certificat}/annule', [CertificatController::class, 'handleCancel'])
            ->name('paydunya.cancel');
        Route::post('paiements/paydunya/callback', [CertificatController::class, 'handleCallback'])
            ->name('paydunya.callback');
    });
});

require __DIR__.'/auth.php';
