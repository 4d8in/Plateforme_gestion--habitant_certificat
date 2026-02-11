<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Certificats') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <form method="GET" action="{{ route('certificats.index') }}" class="flex flex-wrap gap-2 items-center">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Rechercher par habitant ou quartier"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >

                            <select
                                name="statut"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Tous les statuts</option>
                                <option value="{{ \App\Models\Certificat::STATUT_EN_ATTENTE }}" @selected(request('statut') === \App\Models\Certificat::STATUT_EN_ATTENTE)>
                                    En attente
                                </option>
                                <option value="{{ \App\Models\Certificat::STATUT_PAYE }}" @selected(request('statut') === \App\Models\Certificat::STATUT_PAYE)>
                                    Payé
                                </option>
                                <option value="{{ \App\Models\Certificat::STATUT_DELIVRE }}" @selected(request('statut') === \App\Models\Certificat::STATUT_DELIVRE)>
                                    Délivré
                                </option>
                            </select>

                            <x-primary-button>
                                Rechercher
                            </x-primary-button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
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
                                    <tr>
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
                                            {{ ucfirst(str_replace('_', ' ', $certificat->statut)) }}
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

