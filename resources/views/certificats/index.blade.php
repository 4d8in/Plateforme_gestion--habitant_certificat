<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Certificats') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-slate-50 via-white to-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($retardCountTotal > 0)
                        <div class="mb-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
                            <div class="mt-0.5 h-3 w-3 rounded-full bg-amber-500" aria-hidden="true"></div>
                            <div>
                                <p class="font-semibold">Certificats en attente depuis plus de {{ $pendingAlertDays }} jours : {{ $retardCountTotal }}</p>
                                <p class="text-sm text-amber-800">
                                    Filtre “Retards” pour les isoler rapidement.
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between mb-6">
                        <form method="GET" action="{{ route('certificats.index') }}" class="flex flex-wrap gap-3 items-end w-full">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Habitant, quartier"
                                aria-label="Recherche par habitant ou quartier"
                                class="w-full sm:w-64 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >

                            <select
                                name="statut"
                                    class="w-full sm:w-auto border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Tous les statuts</option>
                                @foreach (config('certificat.statuses') as $value => $meta)
                                    <option value="{{ $value }}" @selected(request('statut') === $value)>
                                        {{ $meta['label'] ?? ucfirst($value) }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="flex gap-2 items-end">
                                <div>
                                    <x-input-label for="date_from" value="Du" />
                                    <input
                                        type="date"
                                        id="date_from"
                                        name="date_from"
                                        value="{{ $dateFrom }}"
                                        class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                </div>
                                <div>
                                    <x-input-label for="date_to" value="Au" />
                                    <input
                                        type="date"
                                        id="date_to"
                                        name="date_to"
                                        value="{{ $dateTo }}"
                                        class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                </div>
                            </div>

                            <div class="flex gap-2 items-end">
                                <div>
                                    <x-input-label for="montant_min" value="Montant min" />
                                    <input
                                        type="number"
                                        id="montant_min"
                                        name="montant_min"
                                        value="{{ $montantMin }}"
                                        min="0"
                                    class="w-full sm:w-28 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="0"
                                    >
                                </div>
                                <div>
                                    <x-input-label for="montant_max" value="Montant max" />
                                    <input
                                        type="number"
                                        id="montant_max"
                                        name="montant_max"
                                        value="{{ $montantMax }}"
                                        min="0"
                                    class="w-full sm:w-28 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="5000"
                                    >
                                </div>
                            </div>

                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    name="retards"
                                    value="1"
                                    @checked($onlyLate)
                                    class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500"
                                >
                                Retards (>{{ $pendingAlertDays }} j)
                            </label>

                            <x-primary-button class="w-full sm:w-auto">
                                Rechercher
                            </x-primary-button>
                            <a
                                href="{{ route('certificats.export', request()->query()) }}"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 w-full sm:w-auto"
                            >
                                Export CSV
                            </a>
                            <a
                                href="{{ route('certificats.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 w-full sm:w-auto"
                            >
                                Export PDF
                            </a>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Habitant
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Montant
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Référence
                                    </th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($certificats as $certificat)
                                    @php
                                        $enRetard = $certificat->statut === \App\Models\Certificat::STATUT_EN_ATTENTE
                                            && $certificat->created_at?->lte(now()->subDays($pendingAlertDays));
                                    @endphp
                                    <tr class="{{ $enRetard ? 'bg-amber-50/70' : '' }}">
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <a
                                                href="{{ route('habitants.show', $certificat->habitant) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                {{ $certificat->habitant->nom_complet }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $certificat->date_certificat?->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <x-status-badge :statut="$certificat->statut" />
                                            @if ($enRetard)
                                                <span class="ml-2 text-xs font-medium text-amber-700">> {{ $pendingAlertDays }} j</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ number_format($certificat->montant, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            {{ $certificat->reference_paiement ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a
                                                href="{{ route('certificats.show', $certificat) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                                Voir
                                            </a>
                                            <a
                                                href="{{ route('certificats.edit', $certificat) }}"
                                                class="text-indigo-600 hover:text-indigo-900"
                                            >
                                            Modifier
                                            </a>
                                            <form
                                                action="{{ route('certificats.destroy', $certificat) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Supprimer ce certificat ?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Aucun certificat trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $certificats->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
