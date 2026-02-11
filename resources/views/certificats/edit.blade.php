<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier certificat') }} #{{ $certificat->id }}
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

                    <form method="POST" action="{{ route('certificats.update', $certificat) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="habitant" value="Habitant" />
                            <div class="mt-1 text-sm text-gray-700">
                                {{ $certificat->habitant->nom_complet }} ({{ $certificat->habitant->email }})
                            </div>
                        </div>

                        <div>
                            <x-input-label for="date_certificat" value="Date du certificat" />
                            <x-text-input
                                id="date_certificat"
                                name="date_certificat"
                                type="date"
                                class="mt-1 block w-full"
                                :value="old('date_certificat', $certificat->date_certificat?->format('Y-m-d'))"
                                required
                            />
                            <x-input-error :messages="$errors->get('date_certificat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="statut" value="Statut" />
                            <select
                                id="statut"
                                name="statut"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required
                            >
                                @foreach (config('certificat.statuses') as $value => $meta)
                                    <option value="{{ $value }}" @selected(old('statut', $certificat->statut) === $value)>
                                        {{ $meta['label'] ?? ucfirst($value) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('statut')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="montant" value="Montant (FCFA)" />
                            <x-text-input
                                id="montant"
                                name="montant"
                                type="number"
                                min="0"
                                step="1"
                                class="mt-1 block w-full"
                                :value="old('montant', $certificat->montant)"
                                required
                            />
                            <x-input-error :messages="$errors->get('montant')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="reference_paiement" value="Référence paiement (token PayDunya)" />
                            <x-text-input
                                id="reference_paiement"
                                name="reference_paiement"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('reference_paiement', $certificat->reference_paiement)"
                            />
                            <x-input-error :messages="$errors->get('reference_paiement')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <a
                                href="{{ route('certificats.show', $certificat) }}"
                                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
                            >
                                Annuler
                            </a>

                            <x-primary-button>
                                Enregistrer
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
