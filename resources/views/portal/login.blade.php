<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portail habitant - Connexion</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 text-slate-100 flex items-center justify-center px-4">
    <div class="w-full max-w-md card p-6">
        <div class="flex items-center gap-3 mb-6">
            <x-application-logo class="h-10 w-auto text-emerald-400"/>
            <div>
                <div class="text-sm text-slate-300">Portail habitant</div>
                <div class="font-semibold text-lg">Connexion</div>
            </div>
        </div>

        @if (session('error'))
            <div class="mb-4 text-sm text-red-400">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-400">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('portal.login.post') }}" class="space-y-4">
            @csrf
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1" required autofocus />
            </div>
            <div>
                <x-input-label for="password" value="Mot de passe" />
                <x-text-input id="password" name="password" type="password" class="mt-1" required />
            </div>
            <x-primary-button class="w-full justify-center">Se connecter</x-primary-button>
        </form>
    </div>
</body>
</html>
