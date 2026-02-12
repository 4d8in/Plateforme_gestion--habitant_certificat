@php /** @var \App\Models\Habitant $habitant */ @endphp
<x-portal.layout :habitant="$habitant">
    <h2 class="text-2xl font-bold mb-4">Bonjour {{ $habitant->prenom }}</h2>
    <p class="text-slate-300 mb-6">Retrouvez vos certificats et vos informations personnelles.</p>

    <div class="grid gap-4 md:grid-cols-3">
        <div class="card p-4">
            <div class="text-sm text-slate-500">Certificats totaux</div>
            <div class="text-3xl font-bold mt-1">{{ $habitant->certificats->count() }}</div>
        </div>
        <div class="card p-4">
            <div class="text-sm text-slate-500">En attente</div>
            <div class="text-3xl font-bold mt-1">{{ $habitant->certificats->where('statut', \App\Models\Certificat::STATUT_EN_ATTENTE)->count() }}</div>
        </div>
        <div class="card p-4">
            <div class="text-sm text-slate-500">Payés</div>
            <div class="text-3xl font-bold mt-1">{{ $habitant->certificats->where('statut', \App\Models\Certificat::STATUT_PAYE)->count() }}</div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Vos certificats</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-soft">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Montant</th>
                        <th>Référence</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/30">
                    @forelse($habitant->certificats as $certificat)
                        <tr>
                            <td>{{ $certificat->date_certificat?->format('d/m/Y') }}</td>
                            <td><x-status-badge :statut="$certificat->statut" /></td>
                            <td>{{ number_format($certificat->montant, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $certificat->reference_paiement ?? '-' }}</td>
                            <td class="text-right space-x-2">
                                <a href="{{ route('portal.certificats.show', $certificat) }}" class="text-sky-400 hover:underline">Voir</a>
                                @if($certificat->statut !== \App\Models\Certificat::STATUT_PAYE)
                                    <form action="{{ route('portal.certificats.pay', $certificat) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-emerald-400 hover:underline">Payer</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-400">Aucun certificat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-portal.layout>
