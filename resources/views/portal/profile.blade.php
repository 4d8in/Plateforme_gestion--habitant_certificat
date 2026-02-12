@php /** @var \App\Models\Habitant $habitant */ @endphp
<x-portal.layout :habitant="$habitant">
    <h2 class="text-xl font-bold mb-4">Votre profil</h2>

    @if (session('success'))
        <div class="mb-4 text-sm text-emerald-400">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-400">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('portal.profile.update') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="nom" value="Nom" />
            <x-text-input id="nom" name="nom" type="text" class="mt-1" value="{{ old('nom', $habitant->nom) }}" required />
        </div>
        <div>
            <x-input-label for="prenom" value="Prénom" />
            <x-text-input id="prenom" name="prenom" type="text" class="mt-1" value="{{ old('prenom', $habitant->prenom) }}" required />
        </div>
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1" value="{{ old('email', $habitant->email) }}" required />
        </div>
        <div>
            <x-input-label for="telephone" value="Téléphone" />
            <x-text-input id="telephone" name="telephone" type="text" class="mt-1" value="{{ old('telephone', $habitant->telephone) }}" />
        </div>
        <div>
            <x-input-label for="quartier" value="Quartier" />
            <x-text-input id="quartier" name="quartier" type="text" class="mt-1" value="{{ old('quartier', $habitant->quartier) }}" required />
        </div>
        <div>
            <x-input-label for="password" value="Nouveau mot de passe (optionnel)" />
            <x-text-input id="password" name="password" type="password" class="mt-1" />
        </div>
        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1" />
        </div>
        <x-primary-button>Enregistrer</x-primary-button>
    </form>
</x-portal.layout>
