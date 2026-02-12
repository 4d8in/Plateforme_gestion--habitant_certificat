<?php

use App\Http\Controllers\CertificatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HabitantController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\PortalCertificatController;
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
        // Export must be defined before param routes to avoid conflicts
        Route::get('certificats/export', [CertificatController::class, 'export'])
            ->name('certificats.export');
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

// Portail habitant (accès individuel)
Route::prefix('portal')->group(function (): void {
    Route::get('login', [PortalController::class, 'showLogin'])->name('portal.login');
    Route::post('login', [PortalController::class, 'login'])->name('portal.login.post');
    Route::post('logout', [PortalController::class, 'logout'])->name('portal.logout');
    Route::get('set-password', [PortalController::class, 'showSetPassword'])->name('portal.set_password');
    Route::post('set-password', [PortalController::class, 'setPassword'])->name('portal.set_password.post');
    Route::get('forgot-password', [PortalController::class, 'showForgotPassword'])->name('portal.forgot_password');
    Route::post('forgot-password', [PortalController::class, 'sendResetLink'])->name('portal.forgot_password.post');
    Route::get('reset-password', function () {
        return redirect()->route('portal.forgot_password');
    });
    Route::get('reset-password/{token}', [PortalController::class, 'showResetPassword'])->name('portal.reset_password');
    Route::post('reset-password', [PortalController::class, 'resetPassword'])->name('portal.reset_password.post');

    Route::middleware('portal.auth')->group(function (): void {
        Route::get('dashboard', [PortalController::class, 'dashboard'])->name('portal.dashboard');
        Route::get('profile', [PortalController::class, 'editProfile'])->name('portal.profile');
        Route::post('profile', [PortalController::class, 'updateProfile'])->name('portal.profile.update');
        Route::get('certificats', [PortalCertificatController::class, 'index'])->name('portal.certificats');
        Route::get('certificats/{certificat}', [PortalCertificatController::class, 'show'])->name('portal.certificats.show');
        Route::post('certificats/{certificat}/payer', [PortalCertificatController::class, 'pay'])->name('portal.certificats.pay');
    });
});
