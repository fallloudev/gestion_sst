@extends('layouts.main')

@section('page')
    <span>Ventes</span>
@endsection

@section('titre')
    <span>Détails du paiement</span>
@endsection

@section('content')

<div class="row">
    <div class="col-md-10 offset-md-1">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Paiement 💳</h5>
                <a href="{{ route('paiement.list') }}" class="btn btn-secondary btn-sm">
                    Retour
                </a>
            </div>

            <div class="card-body">

                {{-- Infos Paiement --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Date :</strong><br>
                        {{ \Carbon\Carbon::parse($paiement->date)->format('d/m/Y') }}
                    </div>

                    <div class="col-md-4">
                        <strong>Mode Paiement:</strong><br>
                        <span class="badge bg-info">
                            {{ $paiement->mode_paiement }}
                        </span>
                    </div>

                    <div class="col-md-4">
                        <strong>Montant :</strong><br>
                        <span class="text-success fw-bold">
                            {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Référence paiement :</strong><br>
                        {{ $paiement->reference ?? '—' }}
                    </div>

                    <div class="col-md-6">
                        <strong>Facture :</strong><br>
                        {{ $paiement->facture->numero }}
                    </div>
                </div>

                <hr>

                {{-- Infos Client --}}
                <h6>Client 👤</h6>
                <p>
                    <strong>{{ $paiement->facture->commande->client->nom ?? '-' }}</strong><br>
                    {{ $paiement->facture->commande->client->telephone ?? '-' }}<br>
                    {{ $paiement->facture->commande->client->adresse ?? '-' }}
                </p>

                <hr>

                {{-- Lignes de commande --}}
                <h6>Produits facturés 🧾</h6>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Produit</th>
                                <th>Qté</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiement->facture->commande->lignes as $ligne)
                                <tr>
                                    <td>{{ $ligne->produit->nom }}</td>
                                    <td>{{ $ligne->quantite }}</td>
                                    <td>
                                        {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td>
                                        {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <strong>Total facture :</strong>
                    {{ number_format($paiement->facture->total_ttc, 0, ',', ' ') }} FCFA
                </div>

            </div>
        </div>

    </div>
</div>

@endsection
