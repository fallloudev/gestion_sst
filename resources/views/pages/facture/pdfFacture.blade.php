@php
    use App\Constant;
    $entreprise = Constant::ENTREPRISE;
@endphp

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $facture->reference }}</title>

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
        <img src="{{ public_path($entreprise['logo']) }}" class="logo-lg img-fluid"
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

{{-- 🔹 INFOS FACTURE --}}
<div class="info">
    <p><strong>FACTURE</strong></p>
    <p><strong>Référence :</strong> {{ $facture->reference }}</p>
    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}</p>
    <p><strong>Client :</strong> {{ $facture->commande->client->nom }}</p>
    <p><strong>Statut :</strong> {{ $facture->statut }}</p>
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
        @foreach ($facture->commande->lignes as $ligne)
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

{{-- 🔹 TOTAL --}}
<p class="total">
    TOTAL À PAYER :
    {{ number_format($facture->total_ttc, 0, ',', ' ') }} FCFA
</p>

{{-- 🔹 PIED DE PAGE --}}
<div class="footer">
    Merci pour votre confiance 🙏 <br>
    {{ $entreprise['nom'] }} — {{ $entreprise['site'] }}
</div>

</body>
</html>
