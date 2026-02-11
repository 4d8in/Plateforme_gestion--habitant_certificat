<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $habitant->nom_complet }}
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2">Informations</h3>
                            <p><span class="font-semibold">Nom :</span> {{ $habitant->nom }}</p>
                            <p><span class="font-semibold">Prénom :</span> {{ $habitant->prenom }}</p>
                            <p><span class="font-semibold">Email :</span> {{ $habitant->email }}</p>
                            <p><span class="font-semibold">Téléphone :</span> {{ $habitant->telephone }}</p>
                            <p>
                                <span class="font-semibold">Date de naissance :</span>
                                {{ $habitant->date_naissance?->format('d/m/Y') }}
                            </p>
                            <p><span class="font-semibold">Quartier :</span> {{ $habitant->quartier }}</p>
                        </div>

                        <div class="flex flex-col items-end gap-2">
                            <a
                                href="{{ route('habitants.edit', $habitant) }}"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Modifier
                            </a>
                            <a
                                href="{{ route('habitants.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                            >
                                Retour à la liste
                            </a>
                        </div>
                    </div>

                    <hr class="my-6">

                    <h3 class="font-semibold text-gray-700 mb-4">Certificats de résidence</h3>

                    <div class="mb-4 flex items-center justify-between">
                        <a
                            href="{{ route('certificats.create', $habitant) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Nouveau certificat
                        </a>

                        <a
                            href="{{ route('certificats.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                        >
                            Tous les certificats
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
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
                                        Référence paiement
                                    </th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($habitant->certificats as $certificat)
                                    <tr>
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                            Aucun certificat pour cet habitant.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

