<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portail habitant - Créer mon mot de passe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 text-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md card p-6">
        <div class="flex items-center gap-3 mb-6">
            <x-application-logo class="h-10 w-auto text-emerald-400"/>
            <div>
                <div class="text-sm text-slate-300">Portail habitant</div>
                <div class="font-semibold text-lg">Créer mon mot de passe</div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 text-sm text-emerald-400">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-400">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('portal.set_password.post') }}" class="space-y-4">
            @csrf
            <div>
                <x-input-label for="email" value="Email (doit exister dans la base)" />
                <x-text-input id="email" name="email" type="email" class="mt-1" value="{{ old('email') }}" required autofocus />
            </div>
            <div>
                <x-input-label for="password" value="Mot de passe" />
                <x-text-input id="password" name="password" type="password" class="mt-1" required />
            </div>
            <div>
                <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1" required />
            </div>
            <x-primary-button class="w-full justify-center">Enregistrer</x-primary-button>
        </form>

        <div class="mt-4 text-sm text-slate-300">
            <a href="{{ route('portal.login') }}" class="text-sky-300 hover:underline">Déjà un mot de passe ? Connexion</a>
        </div>
    </div>
</body>
</html>
