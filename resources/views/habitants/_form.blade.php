@props(['habitant' => null])

@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <x-input-label for="nom" value="Nom" />
        <x-text-input
            id="nom"
            name="nom"
            type="text"
            class="mt-1 block w-full"
            :value="old('nom', $habitant?->nom)"
            required
        />
        <x-input-error :messages="$errors->get('nom')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="prenom" value="Prénom" />
        <x-text-input
            id="prenom"
            name="prenom"
            type="text"
            class="mt-1 block w-full"
            :value="old('prenom', $habitant?->prenom)"
            required
        />
        <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input
            id="email"
            name="email"
            type="email"
            class="mt-1 block w-full"
            :value="old('email', $habitant?->email)"
            required
        />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="telephone" value="Téléphone" />
        <x-text-input
            id="telephone"
            name="telephone"
            type="text"
            class="mt-1 block w-full"
            :value="old('telephone', $habitant?->telephone)"
        />
        <x-input-error :messages="$errors->get('telephone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="date_naissance" value="Date de naissance" />
        <x-text-input
            id="date_naissance"
            name="date_naissance"
            type="date"
            class="mt-1 block w-full"
            :value="old('date_naissance', optional($habitant?->date_naissance)->format('Y-m-d'))"
            required
        />
        <x-input-error :messages="$errors->get('date_naissance')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="quartier" value="Quartier" />
        <x-text-input
            id="quartier"
            name="quartier"
            type="text"
            class="mt-1 block w-full"
            :value="old('quartier', $habitant?->quartier)"
            required
        />
        <x-input-error :messages="$errors->get('quartier')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-2">
    <a
        href="{{ route('habitants.index') }}"
        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50"
    >
        Annuler
    </a>

    <x-primary-button>
        {{ $habitant ? 'Mettre à jour' : 'Enregistrer' }}
    </x-primary-button>
</div>

