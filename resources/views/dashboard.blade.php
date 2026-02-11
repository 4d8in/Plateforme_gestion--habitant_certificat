<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-slate-50 via-white to-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                @if (session('success'))
                    <div class="mb-4 text-sm text-green-600">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 text-sm text-red-600">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">
                            Nombre d'habitants
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $nombreHabitants }}
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-indigo-600 to-sky-500 text-white overflow-hidden shadow-sm sm:rounded-2xl">
                    <div class="p-6">
                        <div class="text-sm font-medium text-indigo-50">
                            Revenus totaux (payés)
                        </div>
                        <div class="mt-2 text-3xl font-bold">
                            {{ number_format($totalRevenus, 0, ',', ' ') }} FCFA
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">
                            Certificats en attente
                        </div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $certificatsEnAttente }}
                        </div>
                    </div>
                </div>

                <div class="bg-amber-50 overflow-hidden shadow-sm sm:rounded-2xl border border-amber-200">
                    <div class="p-6">
                        <div class="text-sm font-medium text-amber-800">
                            En attente &gt; {{ config('certificat.pending_alert_days') }} jours
                        </div>
                        <div class="mt-2 text-3xl font-bold text-amber-900">
                            {{ $certificatsRetard }}
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('certificats.index', ['retards' => 1]) }}" class="text-sm font-semibold text-amber-800 hover:text-amber-900 underline">
                                Voir les retards
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-gray-700 mb-4">Accès rapide</h3>

                    <div class="flex flex-wrap gap-4">
                        <a
                            href="{{ route('habitants.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Gérer les habitants
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
