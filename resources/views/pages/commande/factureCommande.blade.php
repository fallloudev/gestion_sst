@extends('layouts.main')

@section('page')
    <span>Ventes</span>
@endsection

@section('titre')
    <span>Facturer la commande #{{ $commande->id }}</span>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">

            <div class="card-header">
                <h5>Récapitulatif de la commande</h5>
            </div>

            <div class="card-body">

                {{-- Infos commande --}}
                <div class="mb-3">
                    <p><strong>Client :</strong> {{ $commande->client->nom }}</p>
                    <p><strong>Téléphone :</strong> {{ $commande->client->telephone }}</p>
                    <p><strong>Adresse :</strong> {{ $commande->client->adresse }}</p>
                    <p><strong>Date :</strong> {{ \Carbon\Carbon::parse($commande->date)->format('d/m/Y') }}</p>
                    <p><strong>Total :</strong>
                        <strong>{{ number_format($commande->total, 0, ',', ' ') }} FCFA</strong>
                    </p>
                </div>

                {{-- Lignes --}}
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($commande->lignes as $ligne)
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
                </div>

                {{-- Action --}}
                <form action="{{ route('commande.facture.store', $commande->id) }}" method="POST"
                      onsubmit="return confirm('Confirmer la facturation de cette commande ?');">
                    @csrf

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('commande.list') }}" class="btn btn-secondary">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            ✔️ Confirmer la facturation
                        </button>
                        
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
