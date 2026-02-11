<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export certificats</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 24px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f3f4f6; }
        h1 { margin-bottom: 12px; }
        .meta { margin-bottom: 12px; color: #555; }
    </style>
</head>
<body>
    <h1>Certificats exportés</h1>
    <p class="meta">Généré le {{ $generatedAt->format('d/m/Y H:i') }} — Total : {{ $certificats->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Habitant</th>
                <th>Email</th>
                <th>Quartier</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Montant</th>
                <th>Référence</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($certificats as $certificat)
                <tr>
                    <td>{{ $certificat->id }}</td>
                    <td>{{ $certificat->habitant->nom_complet }}</td>
                    <td>{{ $certificat->habitant->email }}</td>
                    <td>{{ $certificat->habitant->quartier }}</td>
                    <td>{{ $certificat->date_certificat?->format('d/m/Y') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $certificat->statut)) }}</td>
                    <td>{{ number_format($certificat->montant, 0, ',', ' ') }} FCFA</td>
                    <td>{{ $certificat->reference_paiement }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
