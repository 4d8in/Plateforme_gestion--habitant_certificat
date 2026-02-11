<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat de résidence</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1, h2, h3, h4 {
            margin: 0;
            padding: 0;
        }

        .entete-table,
        .infos-table,
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .entete-cell {
            border: none;
            padding: 4px;
            font-size: 11px;
        }

        .entete-centre {
            text-align: center;
            font-weight: bold;
        }

        .titre-certificat {
            text-align: center;
            margin: 16px 0;
            text-transform: uppercase;
            font-size: 16px;
            font-weight: bold;
        }

        .infos-table th,
        .infos-table td {
            border: 1px solid #000000;
            padding: 6px;
            text-align: left;
        }

        .signature-table td {
            border: none;
            padding: 6px;
        }

        .texte-certificat {
            margin-bottom: 12px;
            line-height: 1.5;
            text-align: justify;
        }
    </style>
</head>
<body>
    <table class="entete-table">
        <tr>
            <td class="entete-cell">
                <strong>RÉPUBLIQUE DU SÉNÉGAL</strong><br>
                Un Peuple - Un But - Une Foi
            </td>
            <td class="entete-cell entete-centre">
                <strong>COMMUNE DE ................................</strong><br>
                <span>Arrondissement de ............................</span><br>
                <span>Département de ...............................</span>
            </td>
            <td class="entete-cell" style="text-align: right;">
                N&deg; {{ $certificat->id }}/{{ $certificat->date_certificat?->format('Y') }}<br>
                Certificat de résidence
            </td>
        </tr>
    </table>

    <div class="titre-certificat">
        CERTIFICAT DE RÉSIDENCE
    </div>

    <p class="texte-certificat">
        Le Maire de la Commune de ........................................, soussigné, certifie que :
    </p>

    <table class="infos-table">
        <tbody>
            <tr>
                <th>Nom et prénom(s)</th>
                <td>{{ $certificat->habitant->nom_complet }}</td>
            </tr>
            <tr>
                <th>Date et lieu de naissance</th>
                <td>
                    {{ $certificat->habitant->date_naissance?->format('d/m/Y') }}
                    à ............................................................
                </td>
            </tr>
            <tr>
                <th>Nationalité</th>
                <td>............................................................</td>
            </tr>
            <tr>
                <th>Profession</th>
                <td>............................................................</td>
            </tr>
            <tr>
                <th>Adresse</th>
                <td>Quartier {{ $certificat->habitant->quartier }}</td>
            </tr>
        </tbody>
    </table>

    <p class="texte-certificat">
        Est effectivement et habituellement domicilié(e) à l'adresse indiquée ci-dessus dans la
        Commune de .................................................., depuis plus de
        ............................................................ .
    </p>

    <p class="texte-certificat">
        En foi de quoi, le présent certificat lui est délivré pour servir et valoir ce que de droit.
    </p>

    <table class="infos-table">
        <tbody>
            <tr>
                <th>Date du certificat</th>
                <td>{{ $certificat->date_certificat?->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <th>Montant perçu</th>
                <td>{{ number_format($certificat->montant, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <th>Référence paiement PayDunya</th>
                <td>{{ $certificat->reference_paiement }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signature-table">
        <tbody>
            <tr>
                <td>
                    Fait à ........................................, le ........................................
                </td>
            </tr>
            <tr>
                <td style="padding-top: 40px;">
                    Le Maire / L'Autorité compétente<br>
                    (Signature et cachet)
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>

