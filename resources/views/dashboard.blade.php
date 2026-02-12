<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-white leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card p-5">
                <div class="text-sm text-slate-500">Nombre d'habitants</div>
                <div class="mt-2 text-3xl font-bold">{{ $nombreHabitants }}</div>
            </div>
            <div class="card p-5 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white border-none shadow-lg">
                <div class="text-sm opacity-90">Revenus totaux (payés)</div>
                <div class="mt-2 text-3xl font-bold">{{ number_format($totalRevenus, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="card p-5">
                <div class="text-sm text-slate-500">Certificats en attente</div>
                <div class="mt-2 text-3xl font-bold">{{ $certificatsEnAttente }}</div>
            </div>
            <div class="card p-5 bg-amber-100 text-amber-900 border-amber-200">
                <div class="text-sm font-semibold">En attente &gt; {{ config('certificat.pending_alert_days') }} jours</div>
                <div class="mt-2 text-3xl font-bold">{{ $certificatsRetard }}</div>
                <div class="mt-3">
                    <a href="{{ route('certificats.index', ['retards' => 1]) }}" class="text-sm font-semibold underline">
                        Voir les retards
                    </a>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h3 class="font-semibold text-slate-700 mb-4">Accès rapide</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('habitants.index') }}"
                   class="btn-primary">
                    Gérer les habitants
                </a>
                <a href="{{ route('certificats.index') }}"
                   class="btn-secondary">
                    Gérer les certificats
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
