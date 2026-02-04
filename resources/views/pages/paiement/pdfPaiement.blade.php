@php
    use App\Constant;
    $entreprise = Constant::ENTREPRISE;
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu de paiement {{ $paiement->facture->numero }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .header-left {
            display: table-cell;
            width: 30%;
        }

        .header-right {
            display: table-cell;
            width: 70%;
            text-align: right;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>

{{-- 🔹 EN-TÊTE ENTREPRISE --}}
<div class="header">
    <div class="header-left">
        <img src="{{ public_path($entreprise['logo']) }}"  class="logo-lg img-fluid"
        style="max-width: 100px; height: auto;">
    </div>

    <div class="header-right">
        <div class="company-name">{{ $entreprise['nom'] }}</div>
        <div>{{ $entreprise['adresse'] }}</div>
        <div>Tél : {{ $entreprise['telephone'] }}</div>
        <div>Email : {{ $entreprise['email'] }}</div>
        <div>Site : {{ $entreprise['site'] }}</div>
        <div>NINEA : {{ $entreprise['ninea'] }}</div>
        <div>RC : {{ $entreprise['rc'] }}</div>
    </div>
</div>

<hr>

{{-- 🔹 INFOS REÇU --}}
<div class="info">
    <p><strong>REÇU DE PAIEMENT</strong></p>
    <p><strong>Facture :</strong> {{ $paiement->facture->numero }}</p>
    <p><strong>Date paiement :</strong>
        {{ \Carbon\Carbon::parse($paiement->date)->format('d/m/Y') }}
    </p>
    <p><strong>Mode de paiement :</strong> {{ $paiement->mode_paiement }}</p>
    <p><strong>Référence :</strong> {{ $paiement->reference ?? '—' }}</p>
    <p><strong>Client :</strong> {{ $paiement->facture->commande->client->nom }}</p>
</div>

{{-- 🔹 TABLE PRODUITS --}}
<table>
    <thead>
        <tr>
            <th>Produit</th>
            <th>Qté</th>
            <th>Prix unitaire</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paiement->facture->commande->lignes as $ligne)
            <tr>
                <td>{{ $ligne->produit->nom }}</td>
                <td>{{ $ligne->quantite }}</td>
                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                <td>
                    {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- 🔹 TOTAUX --}}
<p class="total">
    TOTAL FACTURE :
    {{ number_format($paiement->facture->total_ttc, 0, ',', ' ') }} FCFA
</p>

<p class="total">
    MONTANT PAYÉ :
    {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
</p>

{{-- 🔹 PIED DE PAGE --}}
<div class="footer">
    Paiement validé ✔️<br>
    Merci pour votre confiance 🙏<br>
    {{ $entreprise['nom'] }} — {{ $entreprise['site'] }}
</div>

</body>
</html>
