<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Certificat;
use App\Models\Habitant;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function __invoke(): View
    {
        $totalRevenus = Certificat::whereIn('statut', [
            Certificat::STATUT_PAYE,
            Certificat::STATUT_DELIVRE,
        ])->sum('montant');

        $nombreHabitants = Habitant::count();

        $certificatsEnAttente = Certificat::where('statut', Certificat::STATUT_EN_ATTENTE)->count();
        $certificatsRetard = Certificat::where('statut', Certificat::STATUT_EN_ATTENTE)
            ->whereDate('created_at', '<=', now()->subDays((int) config('certificat.pending_alert_days', 7)))
            ->count();

        return view('dashboard', [
            'totalRevenus' => $totalRevenus,
            'nombreHabitants' => $nombreHabitants,
            'certificatsEnAttente' => $certificatsEnAttente,
            'certificatsRetard' => $certificatsRetard,
        ]);
    }
}
