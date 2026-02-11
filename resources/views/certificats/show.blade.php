<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Certificat de résidence') }} #{{ $certificat->id }}
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

                    @if (session('error'))
                        <div class="mb-4 text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h3 class="font-semibold text-gray-700 mb-4">Informations certificat</h3>

                    <table class="min-w-full border border-gray-200 mb-6">
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Habitant</td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    <a
                                        href="{{ route('habitants.show', $certificat->habitant) }}"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        {{ $certificat->habitant->nom_complet }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Date certificat</td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    {{ $certificat->date_certificat?->format('d/m/Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Statut</td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    {{ ucfirst(str_replace('_', ' ', $certificat->statut)) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Montant</td>
                                <td class="px-4 py-2 border-b border-gray-200">
                                    {{ number_format($certificat->montant, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold">Référence paiement (token)</td>
                                <td class="px-4 py-2">
                                    {{ $certificat->reference_paiement ?? '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="flex items-center justify-between">
                        <a
                            href="{{ route('habitants.show', $certificat->habitant) }}"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                        >
                            Retour à l'habitant
                        </a>

                        <div class="flex items-center gap-2">
                            @if ($certificat->statut === \App\Models\Certificat::STATUT_PAYE || $certificat->statut === \App\Models\Certificat::STATUT_DELIVRE)
                                <a
                                    href="{{ route('certificats.pdf', $certificat) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    Télécharger le PDF
                                </a>
                            @endif

                            <a
                                href="{{ route('certificats.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                            >
                                Tous les certificats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

