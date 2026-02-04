@extends('layouts.main')

@section('page')
    <span>Commandes</span>
@endsection

@section('titre')
    <span>Détails de la commande #{{ $commande->id }}</span>
@endsection

@section('content')

<div class="card mb-3">
    <div class="card-body">
        <strong>Client :</strong> {{ $commande->client->nom }} <br>
        <strong>Date :</strong> {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }} <br>
        <strong>Statut :</strong> {{ $commande->statut }}
    </div>
</div>

<div class="card table-card">
    <div class="card-header">
        <h5 class="mb-0">Lignes de commande</h5>
    </div>

    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total ligne</th>
                </tr>
            </thead>

            <tbody>
                @foreach($commande->lignes as $ligne)
                    <tr>
                        <td>{{ $ligne->produit->nom }}</td>
                        <td>{{ $ligne->quantite }}</td>
                        <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                        <td>
                            <strong>
                                {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                            </strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <h5 class="text-end">
            Total commande :
            {{ number_format($commande->total, 0, ',', ' ') }} FCFA
        </h5>
    </div>
</div>

<a href="{{ route('commande.list') }}" class="btn btn-secondary mt-3">
    Retour
</a>

@endsection
