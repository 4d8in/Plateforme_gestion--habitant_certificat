<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portail habitant</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 text-slate-100">
    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6">
        <div class="glass border border-white/20 rounded-2xl p-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-10 w-auto text-emerald-400"/>
                <div>
                    <div class="text-sm text-slate-300">Portail habitant</div>
                    @isset($habitant)
                        <div class="font-semibold">{{ $habitant->nom_complet ?? '' }}</div>
                    @endisset
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('portal.dashboard') }}" class="btn-secondary">Tableau de bord</a>
                <a href="{{ route('portal.profile') }}" class="btn-secondary">Profil</a>
                <form method="POST" action="{{ route('portal.logout') }}">
                    @csrf
                    <button class="btn-primary">Déconnexion</button>
                </form>
            </div>
        </div>

        <div class="card p-6">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
