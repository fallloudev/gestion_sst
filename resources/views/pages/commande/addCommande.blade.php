@extends('layouts.main')

@section('page')
    <span>Commandes</span>
@endsection

@section('titre')
    <span>Ajouter une commande</span>
@endsection

@section('content')
<div class="row">

    {{-- Messages --}}
    @foreach (['success', 'error'] as $type)
        @if (session($type))
            <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }}">
                {{ session($type) }}
            </div>
        @endif
    @endforeach

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Informations de la commande</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('commande.store') }}" method="POST">
                    @csrf

                    {{-- Infos commande --}}
                    <div class="row g-4">

                        {{-- Client --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="client_id"
                                        class="form-select @error('client_id') is-invalid @enderror">
                                    <option value="">-- Sélectionner le client --</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">
                                            {{ $client->nom }} ({{ $client->type }})
                                        </option>
                                    @endforeach
                                </select>
                                <label>Client</label>
                                @error('client_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date"
                                       name="date"
                                       class="form-control @error('date') is-invalid @enderror"
                                       value="{{ old('date', now()->format('Y-m-d')) }}">
                                <label>Date</label>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <hr>

                    <h6>Lignes de commande</h6>

                    @foreach ($produits as $produit)
                        <div class="row g-2 mb-2 align-items-center">
                            <div class="col-md-5">
                                <input type="hidden" name="produit_ids[]" value="{{ $produit->id }}">
                                <strong>{{ $produit->nom }}</strong>
                                ({{ number_format($produit->prix_vente, 0, ',', ' ') }} FCFA)
                            </div>

                            <div class="col-md-3">
                                <input type="number"
                                       name="quantites[]"
                                       class="form-control"
                                       min="0"
                                       placeholder="Quantité">
                            </div>

                            <div class="col-md-4">
                                <input type="number"
                                       name="prix_unitaires[]"
                                       class="form-control"
                                       value="{{ $produit->prix_vente }}"
                                       step="0.01"
                                       placeholder="Prix unitaire">
                            </div>
                        </div>
                        <hr>
                    @endforeach

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            Enregistrer la commande
                        </button>
                        <a href="{{ route('commande.list') }}" class="btn btn-secondary">
                            Retour
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
