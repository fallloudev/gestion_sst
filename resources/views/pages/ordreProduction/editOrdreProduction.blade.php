@extends('layouts.main')

@section('page')
    <span>Production</span>
@endsection

@section('titre')
    <span>Modifier l’ordre de production</span>
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
                <h5>Modification de l’ordre ({{ $ordre->reference }})</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('ordreProduction.update', $ordre->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        {{-- Produit --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="produit_id" class="form-select">
                                    @foreach ($produits as $produit)
                                        <option value="{{ $produit->id }}"
                                            {{ $ordre->produit_id == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Produit</label>
                            </div>
                        </div>

                        {{-- Quantité prévue --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control"
                                       name="quantite_prevue"
                                       value="{{ old('quantite_prevue', $ordre->quantite_prevue) }}">
                                <label>Quantité prévue</label>
                            </div>
                        </div>

                        {{-- Quantité produite --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="number" class="form-control"
                                       name="quantite_produite"
                                       value="{{ old('quantite_produite', $ordre->quantite_produite) }}">
                                <label>Quantité produite</label>
                            </div>
                        </div>

                        {{-- Statut --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select name="statut" class="form-select">
                                    @foreach (\App\Constant::STATUT_ORDRE_PRODUCTION as $statut)
                                        <option value="{{ $statut }}"
                                            {{ $ordre->statut === $statut ? 'selected' : '' }}>
                                            {{ $statut }}
                                        </option>
                                    @endforeach
                                </select>
                                <label>Statut</label>
                            </div>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local" class="form-control"
                                       name="date"
                                       value="{{ \Carbon\Carbon::parse($ordre->date)->format('Y-m-d\TH:i') }}">
                                <label>Date</label>
                            </div>
                        </div>

                    </div>

                    <hr>

                    <h6>Matières premières consommées (lecture seule)</h6>
                    <ul>
                        @foreach($ordre->consommation_m_p_s as $conso)
                            <li>
                                {{ $conso->matiere_premiere->nom }} :
                                {{ $conso->quantite }} {{ $conso->matiere_premiere->unite }}
                            </li>
                        @endforeach
                    </ul>

                    <br>

                    <div class="d-flex justify-content-end gap-2">
                        <button class="btn btn-primary">Mettre à jour</button>
                        <a href="{{ route('ordreProduction.list') }}" class="btn btn-secondary">Retour</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
