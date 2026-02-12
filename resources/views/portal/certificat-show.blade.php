@php /** @var \App\Models\Certificat $certificat */ @endphp
<x-portal.layout :habitant="$certificat->habitant">
    <h2 class="text-xl font-bold mb-4">Certificat #{{ $certificat->id }}</h2>

    @if (session('error'))
        <div class="mb-4 text-sm text-red-400">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="mb-4 text-sm text-emerald-400">{{ session('success') }}</div>
    @endif

    <div class="grid gap-4 md:grid-cols-2">
        <div class="card p-4">
            <div class="text-sm text-slate-500 mb-1">Habitant</div>
            <div class="font-semibold">{{ $certificat->habitant->nom_complet }}</div>
            <div class="text-sm text-slate-400">{{ $certificat->habitant->email }}</div>
        </div>
        <div class="card p-4">
            <div class="text-sm text-slate-500 mb-1">Statut</div>
            <x-status-badge :statut="$certificat->statut" />
        </div>
        <div class="card p-4">
            <div class="text-sm text-slate-500 mb-1">Date</div>
            <div class="font-semibold">{{ $certificat->date_certificat?->format('d/m/Y') }}</div>
        </div>
        <div class="card p-4">
            <div class="text-sm text-slate-500 mb-1">Montant</div>
            <div class="font-semibold">{{ number_format($certificat->montant, 0, ',', ' ') }} FCFA</div>
        </div>
        <div class="card p-4">
            <div class="text-sm text-slate-500 mb-1">Référence PayDunya</div>
            <div class="font-semibold">{{ $certificat->reference_paiement ?? '-' }}</div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        @if ($certificat->statut === \App\Models\Certificat::STATUT_PAYE || $certificat->statut === \App\Models\Certificat::STATUT_DELIVRE)
            <a href="{{ route('certificats.pdf', $certificat) }}" class="btn-primary">Télécharger le PDF</a>
        @endif
        @if ($certificat->statut !== \App\Models\Certificat::STATUT_PAYE)
            <form action="{{ route('portal.certificats.pay', $certificat) }}" method="POST">
                @csrf
                <button class="btn-secondary">Payer</button>
            </form>
        @endif
        <a href="{{ route('portal.certificats') }}" class="btn-secondary">Retour</a>
    </div>
</x-portal.layout>
