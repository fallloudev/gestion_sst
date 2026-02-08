@extends('layouts.main')

@section('page')
    <span>Production</span>
@endsection

@section('titre')
    <span>Ajouter un ordre de production</span>
@endsection

@section('content')
    <div class="row">

        {{-- Messages --}}
        @foreach (['success', 'error', 'warning'] as $type)
            @if (session($type))
                <div class="alert alert-{{ $type == 'success' ? 'success' : 'danger' }}">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Informations de l’ordre de production</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('ordreProduction.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">

                            {{-- Produit --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="produit_id" class="form-select @error('produit_id') is-invalid @enderror">
                                        <option value="">-- Sélectionner le produit --</option>
                                        @foreach ($produits as $produit)
                                            <option value="{{ $produit->id }}">
                                                {{ $produit->nom }} ({{ $produit->poids }} {{ $produit->unite }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Produit</label>
                                    @error('produit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Quantité prévue --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                        class="form-control @error('quantite_prevue') is-invalid @enderror"
                                        name="quantite_prevue" placeholder="Quantité prévue">
                                    <label>Quantité prévue</label>
                                    @error('quantite_prevue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Statut --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="statut" class="form-select">
                                        @foreach (\App\Constant::STATUT_ORDRE_PRODUCTION as $statut)
                                            <option value="{{ $statut }}">{{ $statut }}</option>
                                        @endforeach
                                    </select>
                                    <label>Statut</label>
                                </div>
                            </div>

                            {{-- Quantité produite --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number"
                                        class="form-control @error('quantite_produite') is-invalid @enderror"
                                        name="quantite_produite" placeholder="Quantité produite">
                                    <label>Quantité produite</label>
                                    @error('quantite_produite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Date --}}
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="datetime-local" class="form-control @error('date') is-invalid @enderror"
                                        name="date" value="{{ old('date', now()->format('Y-m-d\TH:i')) }}">
                                    <label>Date</label>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <hr>

                        <h6>Matières premières consommées</h6>

                        @foreach ($matierePremieres as $mp)
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <input type="hidden" name="mp_ids[]" value="{{ $mp->id }}">
                                    <strong>{{ $mp->nom }}</strong> ({{ $mp->unite }})
                                </div>
                                <div class="col-md-6">
                                    <input type="number" name="quantites[]" class="form-control"
                                        placeholder="Quantité consommée">
                                </div>
                            </div>
                        @endforeach

                        <br>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                Créer l’ordre
                            </button>
                            <a href="{{ route('ordreProduction.list') }}" class="btn btn-secondary">
                                Retour
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
