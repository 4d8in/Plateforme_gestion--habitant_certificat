<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nouveau certificat de résidence') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('error'))
                        <div class="mb-4 text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <p class="mb-4">
                        Vous êtes sur le point de créer un certificat de résidence pour :
                    </p>

                    <table class="min-w-full border border-gray-200 mb-6">
                        <tbody>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Nom complet</td>
                                <td class="px-4 py-2 border-b border-gray-200">{{ $habitant->nom_complet }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Email</td>
                                <td class="px-4 py-2 border-b border-gray-200">{{ $habitant->email }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold border-b border-gray-200">Téléphone</td>
                                <td class="px-4 py-2 border-b border-gray-200">{{ $habitant->telephone }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-semibold">Montant</td>
                                <td class="px-4 py-2">
                                    {{ number_format(config('certificat.default_montant', 5000), 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <form method="POST" action="{{ route('certificats.store', $habitant) }}">
                        @csrf

                        <div class="flex items-center justify-end gap-2">
                            <a
                                href="{{ route('habitants.show', $habitant) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                            >
                                Annuler
                            </a>

                            <x-primary-button>
                                Procéder au paiement
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
